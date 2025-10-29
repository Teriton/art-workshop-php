<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
}

$con = mysqli_connect('localhost', 'root', '', 'art_test');
if (!$con) die("DB connection failed");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_master') {
    $master_id = (int)$_POST['master_id'];
    mysqli_query($con, "DELETE FROM workshop WHERE master_id = $master_id");
    $stmt = mysqli_prepare($con, "DELETE FROM master WHERE master_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $master_id);
    $success = mysqli_stmt_execute($stmt);
    echo json_encode(['success' => $success]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Art WorkShop — Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="css/style-red.css" rel="stylesheet">

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
      box-shadow: 0 0 15px rgb(255 140 140 / 30%);
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

    .btn-outline-danger {
      color: #ff6666;
      border-color: #ff6666;
    }

    .btn-outline-danger:hover {
      background-color: #ff6666;
      color: #fff;
    }

    .btn-outline-success {
      color: #80ff90;
      border-color: #80ff90;
    }

    .btn-outline-success:hover {
      background-color: #80ff90;
      color: #fff;
    }

    .scrollable-table {
      max-height: 420px;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: #b30000 #240000;
    }

    .scrollable-table::-webkit-scrollbar {
      width: 8px;
    }
    .scrollable-table::-webkit-scrollbar-thumb {
      background-color: #b30000;
      border-radius: 5px;
    }
  </style>
</head>

<body id="page-top">

<nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll" href="#page-top">Art WorkShop</a>
    <div class="navbar-collapse collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="admin.php">Назад</a></li>
      </ul>
    </div>
  </div>
</nav>

<div id="home" class="intro route bg-image" style="background-image: url(img/info_back1.jpg); background-size: cover;">
  <div class="overlay-itro"></div>
  <br><br><br><hr>

  <div class="container mt-5">
    <div class="card p-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-center flex-grow-1">Все мастера</h2>
        <a href="registration/tsignup.php" class="btn btn-outline-success">Добавить мастера</a>
      </div>

      <div class="scrollable-table">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Имя</th>
              <th>Специализация</th>
              <th>Количество мастер-классов</th>
              <th>Действие</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $sql = "
              SELECT 
                  m.master_id,
                  m.first_name,
                  m.last_name,
                  m.specialization,
                  COUNT(w.workshop_id) AS workshop_count
              FROM master m
              LEFT JOIN workshop w ON m.master_id = w.master_id
              GROUP BY m.master_id
              ORDER BY m.first_name
          ";
          $result = mysqli_query($con, $sql);
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                  $tech = htmlspecialchars($row['specialization'] ?? '—');
                  $count = (int)$row['workshop_count'];
                  $master_id = (int)$row['master_id'];
                  echo "
                  <tr>
                    <td>$name</td>
                    <td>$tech</td>
                    <td>$count</td>
                    <td>
                      <button class='btn btn-sm btn-outline-danger delete-btn' data-id='$master_id'>Удалить</button>
                    </td>
                  </tr>";
              }
          } else {
              echo "<tr><td colspan='4' class='text-center'>Мастера не найдены</td></tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="lib/jquery/jquery.min.js"></script>
<script>
$(document).ready(function() {
  $('.delete-btn').click(function() {
    const id = $(this).data('id');
    if (!confirm('Удалить мастера и все его мастер-классы? Это действие нельзя отменить.')) return;
    $.post('', { action: 'delete_master', master_id: id }, function(res) {
      if (res.success) location.reload();
      else alert('Ошибка при удалении мастера.');
    }, 'json');
  });
});
</script>

</body>
</html>
