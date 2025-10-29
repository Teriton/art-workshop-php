<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
}

$con = mysqli_connect('localhost', 'root', '', 'art_test');
if (!$con) die("DB connection failed");

// AJAX-обработчик удаления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    $user_id = (int)$_POST['user_id'];
    mysqli_query($con, "DELETE FROM booking WHERE user_id = $user_id");
    $stmt = mysqli_prepare($con, "DELETE FROM user WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
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

  <!-- Bootstrap и библиотеки -->
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
    .navbar a.navbar-brand { color: #fff !important; font-weight: bold; letter-spacing: 1px; }
    .navbar .nav-link { color: #fdd !important; transition: 0.3s; }
    .navbar .nav-link:hover { color: #fff; text-shadow: 0 0 8px #ff3333; }

    .card { background-color: #2b0000; border: 2px solid #ff8a8a; box-shadow: 0 0 15px rgb(255 140 140 / 30%); border-radius: 12px; }
    h2 { color: #ff8a8a; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 15px 0; }

    .table { color: #fff; border-collapse: collapse; width: 100%; }
    .table thead { background: linear-gradient(90deg, #b76161, #713030); }
    .table th, .table td { padding: 12px; text-align: center; }
    .table th { color: #fff; font-size: 1rem; }
    .table td { background-color: #3d1212; border-bottom: 1px solid #ff9494; }
    .table tr:hover td { background-color: #330000; transition: 0.3s; }

    .btn-outline-danger { color: #ff6666; border-color: #ff6666; }
    .btn-outline-danger:hover { background-color: #ff6666; color: #fff; }

    .scrollable-table { max-height: 420px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #b30000 #240000; }
    .scrollable-table::-webkit-scrollbar { width: 8px; }
    .scrollable-table::-webkit-scrollbar-thumb { background-color: #b30000; border-radius: 5px; }
  </style>
</head>
<body id="page-top">

<!-- Navbar -->
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

<!-- Контент -->
<div id="home" class="intro route bg-image" style="background-image: url(img/info_back1.jpg); background-size: cover;">
  <div class="overlay-itro"></div>
  <br><br><br><hr>

  <div class="container mt-5">
    <div class="card p-3">
      <h2 class="text-center">Все пользователи</h2>
      <div class="scrollable-table">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Имя</th>
              <th>Email</th>
              <th>Login</th>
              <th>Действие</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $sql = "SELECT user_id, first_name, last_name, email, login FROM user ORDER BY first_name";
          $result = mysqli_query($con, $sql);
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $id = (int)$row['user_id'];
                  $name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                  $email = htmlspecialchars($row['email']);
                  $login = htmlspecialchars($row['login']);
                  echo "
                  <tr>
                    <td>$id</td>
                    <td>$name</td>
                    <td>$email</td>
                    <td>$login</td>
                    <td>
                      <button class='btn btn-sm btn-outline-danger delete-btn' data-id='$id'>Удалить</button>
                    </td>
                  </tr>";
              }
          } else {
              echo "<tr><td colspan='5' class='text-center'>Пользователи не найдены</td></tr>";
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
        if (!confirm('Удалить этого пользователя? Это действие нельзя отменить.')) return;
        $.post('', { action: 'delete_user', user_id: id }, function(res) {
            if (res.success) location.reload();
            else alert('Ошибка при удалении.');
        }, 'json');
    });
});
</script>

</body>
</html>
