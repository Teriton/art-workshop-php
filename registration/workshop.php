<?php
   include('../action.php');
   if (!isset($_SESSION['name'])) {
       header('location:../index.php');
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Form</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
      $con=mysqli_connect('localhost','root','','art_test');
?>
    <div class="main">
<section class="signup">
    <div class="container">
        <div class="signup-content">
            <form method="POST" id="signup-form" class="signup-form">
                <h2 class="form-title">Новый мастер-класс</h2>

                <div class="form-group">
                    <input type="text" class="form-input" name="wname" placeholder="Название" required />
                </div>
                <div class="form-group">
                    <input type="text" class="form-input" name="venue" placeholder="Адрес" required/>
                </div>
                <div class="form-group">
                    <select name="status" class="form-input" required>
                        <option value="">Выберите статус</option>
                        <option value="актуальный">Актуальный</option>
                        <option value="отмененный">Отмененный</option>
                        <option value="закрытый">Закрытый</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="status" class="form-input" required>
                        <option value="">Выберите уровень сложности</option>
                        <option value="актуальный">Новичок (справится даже ребенок)</option>
                        <option value="отмененный">Средний (потребуется точность и аккуратность)</option>
                        <option value="закрытый">Продвинутый (для людей с некоторым опытом, желающих научиться новым приемам)</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea class="form-input" name="discrition" placeholder="Описание" required></textarea>
                </div>
                <div class="form-group">
                    <select name="technique_id" class="form-input" required>
                        <option value="">Выберите технику</option>
                        <?php
                        $result = mysqli_query($con, "SELECT technique_id, name FROM technique ORDER BY name");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . (int)$row['technique_id'] . '">' 
                                . htmlspecialchars($row['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="master_id" class="form-input" required>
                        <option value="">Выберите мастера</option>
                        <?php
                        $result = mysqli_query($con, "SELECT master_id, first_name FROM master ORDER BY first_name");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . (int)$row['master_id'] . '">' 
                                . htmlspecialchars($row['first_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="number" step="0.01" class="form-input" name="fees" placeholder="Стоимость" required/>
                </div>
                <div class="form-group">
                    <label>Дата Мастеркласса:</label>
                    <input type="date" name="date" required/>
                </div>
                <div class="form-group">
                    <input type="number" class="form-input" name="duration" placeholder="Продолжительность (в минутах)" required/>
                </div>

                <!-- === Материалы (выбор из существующих) === -->
<h3>Необходимые материалы</h3>
<div id="materials-container">
    <div class="material-item form-group" style="border: 1px solid #eee; padding: 10px; margin-bottom: 10px; border-radius: 4px;">
        <select name="materials[][material_id]" class="form-input" required>
            <option value="">Выберите материал</option>
            <?php
            $matResult = mysqli_query($con, "SELECT material_id, name FROM material ORDER BY name");
            while ($m = mysqli_fetch_assoc($matResult)) {
                echo '<option value="' . (int)$m['material_id'] . '">' 
                    . htmlspecialchars($m['name']) . '</option>';
            }
            ?>
        </select>
        <input type="number" step="0.01" name="materials[][quantity]" placeholder="Количество" required />
        <button type="button" class="remove-material" style="color: red; background: none; border: none; cursor: pointer;">✕ Удалить</button>
    </div>
</div>
<button type="button" id="add-material" style="margin: 10px 0;">➕ Добавить материал</button>

<!-- JavaScript для динамики -->
<script>
document.getElementById('add-material').addEventListener('click', function() {
    const container = document.getElementById('materials-container');
    const newItem = document.createElement('div');
    newItem.classList.add('material-item', 'form-group');
    newItem.style.cssText = 'border: 1px solid #eee; padding: 10px; margin-bottom: 10px; border-radius: 4px;';

    // Получаем список материалов (нужно сгенерировать на PHP один раз)
    const materialOptions = `<?php
        $matResult = mysqli_query($con, "SELECT material_id, name FROM material ORDER BY name");
        $opts = '<option value="">Выберите материал</option>';
        while ($m = mysqli_fetch_assoc($matResult)) {
            $opts .= '<option value="' . (int)$m['material_id'] . '">' 
                   . htmlspecialchars($m['name'], ENT_QUOTES, 'UTF-8') . '</option>';
        }
        echo $opts;
    ?>`;

    newItem.innerHTML = `
        <select name="materials[][material_id]" class="form-input" required>
            ${materialOptions}
        </select>
        <input type="number" step="0.01" name="materials[][quantity]" placeholder="Количество" required />
        <button type="button" class="remove-material" style="color: red; background: none; border: none; cursor: pointer;">✕ Удалить</button>
    `;
    container.appendChild(newItem);
});

document.getElementById('materials-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-material')) {
        e.target.closest('.material-item').remove();
    }
});
</script>
        <div class="form-group">
            <input type="submit" name="workshop" id="submit" class="form-submit" value="Submit"/>
        </div>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>