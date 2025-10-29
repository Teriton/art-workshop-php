<?php
   include('../action.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up Form</title>

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
                        <h2 class="form-title">Новый мастер</h2>
                        <div class="form-group">
                            <input type="text" class="form-input" name="name" id="name" placeholder="Имя" required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="last_name" id="name" placeholder="Фамилия" required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="photo" id="name" placeholder="Ссылка на фотографию" required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="exp" id="name" placeholder="Опыт" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="specialization" id="name" placeholder="Специализация" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="bio" id="name" placeholder="Биография" required/>
                        </div>
                        <div class="form-group">
                        <input type="submit" name="tsignup" id="submit" class="form-submit" value="Сохранить"/>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>