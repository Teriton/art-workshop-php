<?php
  session_start();
  if(!isset($_SESSION['uid'])){
    header('location:index.php');
  } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Art WorkShop</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Favicons -->
  <link href="img/wed3.jpg" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Bootstrap JS (с Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style-red.css" rel="stylesheet">
</head>

<body id="page-top">
<style>
  body {
    background-color: #1a0000;
    color: #fff;
    font-family: "Segoe UI", sans-serif;
  }

  .navbar {
    background: linear-gradient(90deg, #b76161, #713030);
  }

  .navbar a.navbar-brand {
    color: #fff !important;
    font-weight: bold;
    letter-spacing: 1px;
  }

  .navbar .nav-link {
    color: #fdd !important;
    transition: 0.3s;
  }

  .navbar .nav-link:hover {
    color: #fff;
    text-shadow: 0 0 8px #ff3333;
  }

  .card {
    background-color: #2b0000;
    border: 2px solid #ff8a8a;
    box-shadow: 0 0 15px rgb(255 140 140 / 30%);;
    border-radius: 12px;
  }

  h2 {
    color: #ff8a8a;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 15px 0;
  }

  .table {
    color: #fff;
    border-collapse: collapse;
    width: 100%;
  }

  .table thead {
    background: linear-gradient(90deg, #b76161, #713030);
  }

  .table th {
    color: #fff;
    text-align: center;
    padding: 12px;
    font-size: 1rem;
  }

  .table td {
    background-color: #3d1212;
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ff9494;
  }

  .table tr:hover td {
    background-color: #330000;
    transition: 0.3s;
  }

  .btn-outline-info {
    color: #87c2d7;
    border-color: #87c2d7;
  }

  .btn-outline-info:hover {
    background-color: #87c2d7;
    color: #fff;
  }

  .btn-outline-success {
    color: #80ff90;
    border-color: #80ff90;
  }

  .btn-outline-success:hover {
    background-color: #80ff90;
    color: #ffffffff;
  }

  .btn-outline-danger {
    color: #ff6666;
    border-color: #ff6666;
  }

  .btn-outline-danger:hover {
    background-color: #ff6666;
    color: #fff;
  }

  /* ===== СТИЛЬ МОДАЛЬНОГО ОКНА ===== */
  .modal-content {
    background: #1b1b1b;
    color: #f5f5f5;
    border: 1px solid #ff8d8d;
    border-radius: 10px;
    box-shadow: 0 0 25px rgb(255 193 193 / 25%);
    backdrop-filter: blur(6px);
    transition: all 0.3s ease-in-out;
  }

  .modal-header {
    background-color: #411b1bba;
    border-bottom: 1px solid #ff8d8d;
    color: #ffcccc;
  }

  .modal-title {
    font-weight: 600;
    color: white;
  }

  .modal-body p {
    color: #ddd;
    font-size: 0.95rem;
    line-height: 1.5;
  }

  .modal-body strong {
    color: #ff6666;
  }

  .modal-footer {
    border-top: 1px solid #ff8d8d;
    background-color: #411b1bba;
  }

  .modal-footer .btn-secondary {
    background-color: #411b1bba;
    border: none;
    color: #fff;
    transition: 0.2s;
  }

  .modal-footer .btn-secondary:hover {
    color: #fff;
    text-decoration: underline;
  }

  /* Мягкая анимация появления */
  .modal.fade .modal-dialog {
    transform: scale(0.9);
    opacity: 0;
    transition: all 0.25s ease-in-out;
  }

  .modal.fade.show .modal-dialog {
    transform: scale(1);
    opacity: 1;
  }
  
  .bg-dark {
    background-color: #411b1bba !important;
  }

  .scrollable-workshops {
    max-height: 420px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #b30000 #240000;
  }

  .scrollable-workshops::-webkit-scrollbar {
    width: 8px;
  }
  .scrollable-workshops::-webkit-scrollbar-thumb {
    background-color: #b30000;
    border-radius: 5px;
  }
</style>

<!--/ Nav Star /-->
<nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll" href="#page-top">Art WorkShop</a>
    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarDefault"
      aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span></span><span></span><span></span>
    </button>
    <div class="navbar-collapse collapse justify-content-end" id="navbarDefault">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link js-scroll active" href="profile.php">Назад</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!--/ Nav End /-->

<!--/ Intro Skew Star /-->
<div id="home" class="intro route bg-image" style="background-image: url(img/info_back1.jpg); background-size: cover;">
  <div class="overlay-itro"></div>
  <br><br><br><hr>

  <!-- Только платежи -->
  <div class="container mt-5" id="payments">
    <div class="card p-3">
      <h2 class="text-center">Мои заказы</h2>

      <?php
      $con = mysqli_connect('localhost', 'root', '', 'art_test');
      if (!$con) {
          die("Connection failed: " . mysqli_connect_error());
      }

      if (!isset($_SESSION['uid'])) {
          echo "<p class='text-white text-center'>Пожалуйста, войдите в систему, чтобы увидеть ваши платежи.</p>";
      } else {
          $user_id = (int)$_SESSION['uid'];

          // Запрос платежей
          $sql = "
              SELECT 
                  p.payment_id,
                  p.amount,
                  p.status AS payment_status,
                  p.payment_date,
                  p.payment_method,
                  w.name AS workshop_name,
                  s.session_date,
                  s.start_time,
                  s.end_time
              FROM payment p
              INNER JOIN booking b ON p.booking_id = b.booking_id
              INNER JOIN session s ON b.session_id = s.session_id
              INNER JOIN workshop w ON s.workshop_id = w.workshop_id
              WHERE p.user_id = ?
              ORDER BY p.payment_date DESC";

          $stmt = mysqli_prepare($con, $sql);
          mysqli_stmt_bind_param($stmt, "i", $user_id);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if (mysqli_num_rows($result) > 0) {
              echo '<div class="table-responsive mt-3">';
              echo '<table class="table table-bordered table-dark text-white">';
              echo '<thead><tr>
                      <th>Воркшоп</th>
                      <th>Дата сеанса</th>
                      <th>Сумма</th>
                      <th>Статус</th>
                      <th>Дата платежа</th>
                      <th>Действие</th>
                    </tr></thead><tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                $date_time = date("d.m.Y", strtotime($row['session_date'])) . 
                            " (" . substr($row['start_time'], 0, 5) . "–" . substr($row['end_time'], 0, 5) . ")";

                // Определяем отображаемый статус
                if ($row['payment_status'] === 'pending') {
                    $displayStatus = 'Не оплачено';
                    $statusClass = 'text-warning';
                } elseif ($row['payment_status'] === 'completed') {
                    if ($row['payment_method'] === 'cash') {
                        $displayStatus = 'Оплата на месте';
                        $statusClass = 'text-info';
                    } else {
                        $displayStatus = 'Оплачено';
                        $statusClass = 'text-success';
                    }
                } else {
                    $displayStatus = htmlspecialchars($row['payment_status']);
                    $statusClass = 'text-secondary';
                }

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['workshop_name']) . "</td>";
                echo "<td>$date_time</td>";
                echo "<td>" . number_format($row['amount'], 2) . "</td>";
                echo "<td class='$statusClass'>$displayStatus</td>";
                echo "<td>" . ($row['payment_date'] ? date("d.m.Y H:i", strtotime($row['payment_date'])) : '—') . "</td>";
                echo "<td>";
                if ($row['payment_status'] === 'pending') {
                    echo "<a href='registration/pay.php?payment_id=" . $row['payment_id'] . "' class='btn btn-sm btn-primary'>Оплатить</a>";
                } else {
                    echo "<span class='text-muted'>—</span>";
                }
                echo "</td>";
                echo "</tr>";
            }
              echo '</tbody></table></div>';
          } else {
              echo "<p class='text-white text-center mt-4'>У вас пока нет заказов.</p>";
          }
      }
      mysqli_close($con);
      ?>
    </div>
  </div>
</div>

<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
<div id="preloader"></div>

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/popper/popper.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/counterup/jquery.waypoints.min.js"></script>
  <script src="lib/counterup/jquery.counterup.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/typed/typed.min.js"></script>
  <script src="contactform/contactform.js"></script>
  <script src="js/main.js"></script>

</body>
</html>
