<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('location:index.php');
    exit();
}

// Обработка формы редактирования профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $con = mysqli_connect('localhost', 'root', '', 'art_test');
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id = (int)$_SESSION['uid'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = "First name, last name and email are required.";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $stmt = mysqli_prepare($con, "UPDATE user SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $phone, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['name'] = $first_name;
                $success = "Profile updated successfully!";
            } else {
                $error = "Failed to update profile: " . mysqli_error($con);
            }
        }
    }
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Art WorkShop - Profile</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <link href="img/wed3.jpg" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="css/style-red.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
  body {
    background: linear-gradient(135deg, #5a1a1a 30%, #f4d6d6 100%);
    color: #3b0a0a;
    min-height: 100vh;
  }
  .navbar {
    background: linear-gradient(90deg, #b76161, #713030);
    backdrop-filter: blur(4px);
  }

  .card {
    background: rgba(255, 240, 240, 0.9);
    border: 1px solid #f1b7c4;
    border-radius: 15px;
    color: #4a0000;
    transition: all 0.3s ease;
    max-width: 700px;
    box-shadow: 0 8px 30px rgba(90, 10, 10, 0.35);
    padding: 40px;
    animation: fadeIn 0.8s ease;
  }

  .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(128, 0, 32, 0.25);
  }

  .card h2 {
    color: #6f1414;
      text-align: center;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 25px;
  }
  
  .form-label {
    color: #660000;
    font-weight: 600;
  }

  .form-control {
    background-color: #fff9fa;
    border: 1px solid #f1b7c4;
    border-radius: 10px;
    color: #4a0000;
    transition: 0.3s;
  }

  .form-control:focus {
    background-color: #fff0f2;
    border-color: #b33b3b;
    box-shadow: 0 0 6px rgba(179, 59, 59, 0.4);
  }

  .btn-primary {
    background-color: #6f1414;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #800000;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(179, 59, 59, 0.4);
  }

  .alert-success {
    background-color: #ffe6ea;
    border-left: 5px solid #cc3366;
    color: #660000;
  }

  .alert-danger {
    background-color: #fff0f2;
    border-left: 5px solid #b33b3b;
    color: #660000;
  }

  .profile-container {
    padding: 120px 20px 60px;
  }

  /* Ссылки и заголовки в футере */
  .footer-paralax h5,
  .footer-paralax p,
  .footer-paralax a {
    color: #ffe6ea !important;
  }

  /* Для плавного появления карточки */
  .card {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease forwards;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
</head>

<body id="page-top"  style="background-image: url(img/info_back1.jpg); background-size: cover;">

  <!-- NAVBAR (оставляем как есть) -->
  <nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll" href="#page-top">Мастерская искусств</a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarDefault"
        aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <div class="navbar-collapse collapse justify-content-end" id="navbarDefault">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link js-scroll active" href="profile.php">Назад</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Контейнер профиля -->
  <div class="container profile-container">
    <div class="card p-4 mx-auto" style="max-width: 600px;">
      <h2 class="text-center mb-4">Профиль</h2>
      <div class="card-body">

        <?php
        $con = mysqli_connect('localhost', 'root', '', 'art_test');
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $user_id = (int)$_SESSION['uid'];
        $sql = "SELECT first_name, last_name, email, phone, login FROM user WHERE user_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $first_name = htmlspecialchars($row['first_name']);
            $last_name = htmlspecialchars($row['last_name']);
            $email = htmlspecialchars($row['email']);
            $phone = htmlspecialchars($row['phone']);
            $login = htmlspecialchars($row['login']);

            if (isset($success)) echo "<div class='alert alert-success'>$success</div>";
            if (isset($error)) echo "<div class='alert alert-danger'>$error</div>";

            echo '
            <form method="POST" action="">
              <div class="mb-3">
                <label for="first_name" class="form-label">Имя</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="' . $first_name . '" required>
              </div>
              <div class="mb-3">
                <label for="last_name" class="form-label">Фамилия</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="' . $last_name . '" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="' . $email . '" required>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Номер телефона</label>
                <input type="text" class="form-control" id="phone" name="phone" value="' . $phone . '">
              </div>
              <div class="mb-3">
                <label for="login" class="form-label">Логин (невозможно изменить)</label>
                <input type="text" class="form-control" id="login" value="' . $login . '" readonly>
              </div>
              <button type="submit" name="update_profile" class="btn btn-primary w-100 mt-2">Сохранить</button>
            </form>';
        } else {
            echo "<p class='text-center text-muted'>Пользователь не найден.</p>";
        }

        mysqli_close($con);
        ?>
      </div>
    </div>
  </div>
  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <div id="preloader"></div>

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
