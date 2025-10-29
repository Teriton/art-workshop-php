<?php
   include('../action.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form method="POST" id="signup-form" class="signup-form">
                        <h2 class="form-title">Регистрация пользователя</h2>
                        <div class="form-group">
                            <input type="text" class="form-input" name="name" id="name" placeholder="Имя" required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="last_name" id="name" placeholder="Фамилия" required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="uname" id="name" placeholder="Логин" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="phone_number" id="date" placeholder="Номер телфона" required/>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-input" name="email" id="email" placeholder="Email"/>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="psw" id="password" placeholder="Пароль"required/>
                            <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="repsw" id="re_password" placeholder="Повторите пароль" required/>
                        </div>
                        <div class="form-group">
                        <input type="submit" name="signup" id="submit" class="form-submit" value="Регистрация"/>
                        </div>
                    </form>
                    <p class="loginhere">
                        Уже есть аккаунт ? <a href="login.php" class="loginhere-link">Вход</a>
                    </p>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>