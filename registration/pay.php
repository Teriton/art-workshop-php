<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['uid'])) {
    die("<p class='text-center mt-5'>Пожалуйста, войдите в систему.</p>");
}

$user_id = (int)$_SESSION['uid'];
$payment_id = isset($_GET['payment_id']) ? (int)$_GET['payment_id'] : 0;

$con = mysqli_connect('localhost', 'root', '', 'art_test');
if (!$con) {
    die("Ошибка подключения к БД: " . mysqli_connect_error());
}

// Получаем данные платежа и проверяем, что он принадлежит пользователю и не оплачен
$paymentSql = "
    SELECT 
        p.payment_id,
        p.amount,
        p.status AS payment_status,
        w.name AS workshop_name
    FROM payment p
    INNER JOIN booking b ON p.booking_id = b.booking_id
    INNER JOIN session s ON b.session_id = s.session_id
    INNER JOIN workshop w ON s.workshop_id = w.workshop_id
    WHERE p.payment_id = ? AND p.user_id = ?
";

$stmt = mysqli_prepare($con, $paymentSql);
mysqli_stmt_bind_param($stmt, "ii", $payment_id, $user_id);
mysqli_stmt_execute($stmt);
$payment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$payment || $payment['payment_status'] !== 'pending') {
    mysqli_close($con);
    die("<p class='text-center mt-5 text-danger'>Платёж не найден или уже оплачен.</p>");
}

$amount = $payment['amount'];
$workshop_name = htmlspecialchars($payment['workshop_name']);
$success = $error = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $method = $_POST['payment_method'] ?? '';
    
    if (!in_array($method, ['online', 'cash'])) {
        $error = "Выберите корректный способ оплаты.";
    } else {
        $payment_method = ($method === 'online') ? 'card' : 'cash';

        // Для демо: если онлайн — требуем заполнение карты
        if ($method === 'online') {
            $card_number = trim($_POST['card_number'] ?? '');
            $card_exp = trim($_POST['card_exp'] ?? '');
            $card_cvc = trim($_POST['card_cvc'] ?? '');

            if (empty($card_number) || empty($card_exp) || empty($card_cvc)) {
                $error = "Пожалуйста, заполните все данные карты.";
            } elseif (!preg_match('/^\d{16}$/', str_replace(' ', '', $card_number))) {
                $error = "Неверный формат номера карты (должно быть 16 цифр).";
            } elseif (!preg_match('/^\d{2}\/\d{2}$/', $card_exp)) {
                $error = "Срок действия должен быть в формате MM/YY.";
            } elseif (!preg_match('/^\d{3,4}$/', $card_cvc)) {
                $error = "CVC должен содержать 3–4 цифры.";
            }
        }

        if (empty($error)) {
            // Обновляем платёж: статус + метод
            $updateStmt = mysqli_prepare($con, "
                UPDATE payment 
                SET status = 'completed', 
                    payment_method = ?, 
                    payment_date = NOW() 
                WHERE payment_id = ?
            ");
            mysqli_stmt_bind_param($updateStmt, "si", $payment_method, $payment_id);

            if (mysqli_stmt_execute($updateStmt)) {
                $success = true;
            } else {
                $error = "Ошибка при сохранении платежа.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .alert { padding: 10px; margin: 15px 0; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        .card-fields { display: none; margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="main">
    <section class="signup">
        <div class="container">
            <div class="signup-content">
                <form method="POST" class="signup-form" id="paymentForm">
                    <h2 class="form-title">Оплата участия</h2>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            Платёж успешно оплачен! Спасибо за участие в мастер-классе «<?= $workshop_name ?>».
                        </div>
                        <div class="form-group text-center">
                            <a href="../porders.php" class="form-submit" style="text-decoration: none; display: inline-block;">Назад к платежам</a>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <p><strong>Мастер-класс:</strong> <?= $workshop_name ?></p>
                        </div>
                        <div class="form-group">
                            <p><strong>Сумма:</strong> <?= number_format($amount, 2) ?> ₽</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Способ оплаты:</label><br>
                            <label style="display: inline-block; margin-right: 20px;">
                                <input type="radio" name="payment_method" value="online" required> Онлайн (банковской картой)
                            </label>
                            <label style="display: inline-block;">
                                <input type="radio" name="payment_method" value="cash" required> Наличными на месте
                            </label>
                        </div>

                        <!-- Поля для карты -->
                        <div class="card-fields" id="cardFields">
                            <h5>Данные карты</h5>
                            <div class="form-group">
                                <label for="card_number">Номер карты (16 цифр)</label>
                                <input type="text" class="form-input" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-group" style="display: flex; gap: 10px;">
                                <div style="flex: 1;">
                                    <label for="card_exp">Срок действия (MM/YY)</label>
                                    <input type="text" class="form-input" id="card_exp" name="card_exp" placeholder="12/25" maxlength="5">
                                </div>
                                <div style="flex: 1;">
                                    <label for="card_cvc">CVC/CVV</label>
                                    <input type="text" class="form-input" id="card_cvc" name="card_cvc" placeholder="123" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="payment_id" value="<?= $payment_id ?>">
                            <input type="submit" name="confirm_payment" class="form-submit" value="Подтвердить оплату">
                        </div>
                    <?php endif; ?>

                    <p class="loginhere">
                        <a href="../porders.php" class="loginhere-link">← Вернуться к списку платежей</a>
                    </p>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const cardFields = document.getElementById('cardFields');
            if (this.value === 'online') {
                cardFields.style.display = 'block';
            } else {
                cardFields.style.display = 'none';
            }
        });
    });

    // Форматирование номера карты (опционально)
    document.getElementById('card_number')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.substring(0, 16);
        let formatted = value.match(/.{1,4}/g)?.join(' ') || '';
        e.target.value = formatted;
    });

    document.getElementById('card_exp')?.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length >= 2) {
            v = v.substring(0, 2) + '/' + v.substring(2, 4);
        }
        e.target.value = v;
    });

    document.getElementById('card_cvc')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
    });
</script>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>