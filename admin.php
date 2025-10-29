<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header('location:index.php');
  } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dance WorkShop</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/wed3.jpg" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

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

    <style>
    /* Центрирование содержимого по вертикали и горизонтали */
    .service-box {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Эффект при наведении: увеличение и тень */
    .service-box:hover {
      transform: scale(1.03);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      z-index: 2;
    }

    /* Убедимся, что текст остаётся поверх фона */
    .service-content {
      text-align: center;
      z-index: 3;
    }

    /* Стили для блока мастеров */
    .masters-carousel {
      overflow-x: auto;
      padding: 20px 30px;
      white-space: nowrap;
    }

    .masters-carousel::-webkit-scrollbar {
      height: 5px;
    }

    .masters-carousel::-webkit-scrollbar-thumb {
      background: rgba(255, 124, 124, 1);
      border-radius: 4px;
    }

    .masters-row {
      display: inline-block;
    }

    .master-card {
      display: inline-block;
      width: 360px;
      height: 650px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      margin: 0 5px;
      vertical-align: top;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      white-space: normal;
      overflow: hidden;
    }

    .master-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .master-img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }

    .master-info {
      padding: 20px;
    }

    .master-name {
      font-size: 1.4rem;
      margin: 0 0 8px;
      color: #333;
    }

    .master-specialty {
      font-weight: 600;
      color: #e63946;
      margin: 0 0 6px;
    }

    .master-experience {
      font-style: italic;
      color: #666;
      margin: 0 0 12px;
    }

    .master-desc {
      font-size: 0.95rem;
      color: #444;
      line-height: 1.5;
      margin: 0;
    }

    .greetings-text {
      font-size: 3rem;
    }
  </style>
</head>

<body id="page-top">

  <!--/ Nav Star /-->
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
          <li class="nav-item">
            <a class="nav-link js-scroll active" href="#home">В начало</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll" href="performerdetail.php">Пользователи</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll" href="coachdetail.php">Мастера</a>
          </li>
           <li class="nav-item">
            <a class="nav-link js-scroll" href="pbookingAdmin.php">Мастер-класс</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll" href="registration/logout.php">Выйти</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--/ Nav End /-->

   <!--/ Intro Skew Star /-->
<div id="home" class="intro route bg-image" style="background-image: url(img/pastel1.jpg); background-size: cover;">
  <div class="overlay-itro"></div>
  <div class="intro-content display-table">
    <div class="table-cell">
      <div class="container">
        <!-- Новая приветственная надпись -->
        <p class="greetings-text">
          <b>Мастерская приветствует вас!<br>
          Открыта запись на наш новый МК. <br>
          </b>
        </p>
        <hr>
        <?php
          $con = mysqli_connect('localhost','root','','art_test');
          $sql = "SELECT * FROM workshop WHERE status='актуальный' ORDER BY workshop_id DESC LIMIT 1";
          $run = mysqli_query($con, $sql);
          if (mysqli_num_rows($run) == 0) {
            echo "<h1 class='intro-title mb-4'>Мастерская искусств</h1>
                  <p class='intro-subtitle'><span class='text-slider-items'>Описание</span><strong class='text-slider'></strong></p>";
          } else {
            $row = mysqli_fetch_array($run);
            $wid = $row['workshop_id'];
            $wname = $row['name'];
            $wtime = $row['duration_minutes'];
            $wstatus = $row['status'];
            $wfees = $row['cost'];
            $wdifficulty_level = $row['difficulty_level'];
            echo "
              <p class='display-6 color-d'><b>Продолжительность: </b>$wtime мин</p>
              <p class='display-6 color-d'><b>Сложность: </b> $wdifficulty_level</p>
              <h1 class='intro-title mb-4'>$wname</h1>
              <p class='intro-subtitle'><span class='text-slider-items'>$wstatus</span><strong class='text-slider'></strong></p>
              <p class='display-6 color-d'>$wfees руб.</p>
            ";
          }
        ?>
      </div>
    </div>
  </div>
</div>
  <!--/ Intro Skew End /-->
  

  <!--/ Section Services Star /-->
  <div class="section-counter paralax-mf bg-image" style="background-image: url(img/andrey-k-PACV8fFDv6Y-unsplash.jpg);background-size: cover;">
    <div class="overlay-mf"></div> 
  <section id="service" class="services-mf route">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="title-box text-center">
            <h3 class="title-a" style="color: white">
              Техники
            </h3>
           
            <div class="line-mf"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="service-box" style="background-image:url(img/watercolor.jpg); background-size: cover; height: 300px;" data-name="Акварель">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white mr-5" style="text-shadow: 0px 0px 10px black; margin-left: 40px;"> <b>Акварель</b></h2>
             
            </div>
          </div>
        </div>
        <div class="col-md-3">
           <div class="service-box" style="background-image:url(img/alcoholink.jpg); background-size: cover;height: 300px;" data-name="Спиртовые чернила">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white" style="text-shadow: 0px 0px 10px black;"> <b>Спиртовые чернила</b></h2>
            
            </div>
          </div>
        </div>
        <div class="col-md-3">
           <div class="service-box" style="background-image:url(img/decoupage.jpg); background-size: cover;height: 300px;" data-name="Декупаж">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white " style="text-shadow: 0px 0px 10px black;" > <b>Декупаж</b></h2>
              
            </div>
          </div>
        </div>
        <div class="col-md-3">
           <div class="service-box" style="background-image:url(img/epoxyresin.jpg); background-size: cover;height: 300px;" data-name="Эпоксидная смола">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white" style="text-shadow: 0px 0px 10px black;"> <b>Эпоксидная смола</b></h2>
             
            </div>
          </div>
        </div>
        <div class="col-md-3">
           <div class="service-box" style="background-image:url(img/ceramics.jpg); background-size: cover;height: 300px;" data-name="Керамика">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white" style="text-shadow: 0px 0px 10px black;"> <b>Керамика</b></h2>
              
            </div>
          </div>
        </div>
        <div class="col-md-3">
           <div class="service-box" style="background-image:url(img/texture.jpg); background-size: cover;height: 300px;" data-name="Текстурная паста">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white" style="text-shadow: 0px 0px 10px black;"> <b>Текстурная паста</b></h2>
              
            </div>
          </div>
        </div>
        <div class="col-md-3">
           <div class="service-box" style="background-image:url(img/mosaic.jpg); background-size: cover;height: 300px;" data-name="Мозаика">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white" style="text-shadow: 0px 0px 10px black;"> <b>Мозаика</b></h2>
              
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="service-box" style="background-image:url(img/ebru.jpg); background-size: cover;height: 300px;" data-name="Эбру">
            <div class="service-ico">
            </div>
            <div class="service-content ">
              <h2 class="s-title text-white" style="text-shadow: 0px 0px 10px black;"> <b>Эбру</b></h2>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
  <!--/ Section Services End /-->

  
  <div class="section-counter paralax-mf bg-image" style="background-image: url(img/andrey-k-PACV8fFDv6Y-unsplash.jpg); background-size: cover;">
  <section id="masters" class="portfolio-mf sect-pt4 route">
    <div class="container-fluid px-0">
      <div class="row">
        <div class="col-sm-12">
          <div class="title-box text-center">
            <h3 class="title-a" style="color: white">
              Наши мастера
            </h3>
            <div class="line-mf"></div>
          </div>
        </div>
      </div>

      <!-- Обёртка для горизонтальной прокрутки -->
      <div class="masters-carousel">
        <div class="masters-row">
          <?php
          // Подключение к БД (если ещё не подключено)
          // $con = mysqli_connect('localhost', 'root', '', 'dance');
          $con = mysqli_connect('localhost','root','','art_test');

          $result = mysqli_query($con, "SELECT first_name, last_name, specialization, experience_years, bio, photo FROM master ORDER BY first_name");
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
              $specialization = htmlspecialchars($row['specialization'] ?? '—');
              $experience = (int)($row['experience_years'] ?? 0);
              $bio = htmlspecialchars($row['bio'] ?? 'Информация отсутствует');
              $photo = htmlspecialchars(!empty($row['photo']) ? $row['photo'] : 'https://thumbs.dreamstime.com/b/hushing-46700864.jpg');
              ?>
              <div class="master-card">
                <img src="<?= $photo ?>" alt="Мастер" class="master-img">
                <div class="master-info">
                  <h4 class="master-name"><?= $name ?></h4>
                  <p class="master-specialty"><?= $specialization ?></p>
                  <p class="master-experience">Опыт: <?= $experience ?> лет</p>
                  <p class="master-desc"><?= $bio ?></p>
                </div>
              </div>
              <?php
            }
          } else {
            echo '<div class="master-card"><p class="text-white text-center">Мастера пока не добавлены.</p></div>';
          }
          ?>
        </div>
      </div>
    </div>
  </section>
</div>

<!--/ Section About Studio Star /-->
<div class="section-counter paralax-mf bg-image" style="background-image: url(img/back_33.jpg); background-size: cover;">
  <div class="overlay-mf"></div>
  <section id="about" class="about-mf sect-pt4 route">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="about-img">
            <img src="https://artistgenerations.com/cdn/shop/files/IMG_3114.jpg?v=1707595425&width=3840" class="img-fluid rounded b-shadow-a" alt="Art Studio">
          </div>
        </div>
        <div class="col-md-6">
          <div class="about-content pt-4 pt-md-0">
            <div class="title-box-2">
              <h3 class="title-left text-white">
                О нашей студии
              </h3>
            </div>
            <p class="text-white lead">
              Art Workshop — это пространство, где рождается вдохновение. Мы открыли нашу студию в 2022 году с целью сделать искусство доступным каждому, независимо от возраста и опыта. У нас вы не просто научитесь новой технике — вы погрузитесь в атмосферу творчества, уюта и поддержки.
            </p>
            <p class="text-white lead">
              Все материалы предоставляются, а занятия ведут профессиональные художники с многолетним опытом. Мы верим, что каждый человек — творец, и наша задача — помочь ему это раскрыть.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!--/ Section About Studio End /-->


  <!--/ Section Testimonials Star /-->
  <div class="testimonials paralax-mf bg-image" style="background-image: url(img/back_33.jpg)">
      <div class="overlay-mf"></div>
      <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="title-box text-center">
          <h3 class="title-a" style="color: white">
            Отзывы
          </h3>
          <div class="line-mf"></div>
        </div>
      </div>
    </div>
  </div>
    
      <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div id="testimonial-mf" class="owl-carousel owl-theme">
            <div class="testimonial-box">
              <div class="author-test">
                <img src="img/doge1.jpg" class="rounded-circle b-shadow-a" width="150" height="150">
                <span class="author">Happy happy happy</span>
              </div>
              <div class="content-test">
                <p class="description lead">
                  Посетила мастер-класс по акварели — впервые в жизни рисовала так, чтобы получилось красиво! Преподаватель терпеливо объяснял каждый шаг, атмосфера была очень уютной и вдохновляющей. Ушла с готовой картиной и желанием прийти снова!
                </p>
                <span class="comit"><i class="fa fa-quote-right"></i></span>
              </div>
            </div>
            <div class="testimonial-box">
              <div class="author-test">
                <img src="img/doge2.png" class="rounded-circle b-shadow-a" width="150" height="150">
                <span class="author">Bark</span>
              </div>
              <div class="content-test">
                <p class="description lead">
                  Решил попробовать что-то новое и записался на мастер-класс по эпоксидной смоле. Думал, это сложно, но оказалось увлекательно и даже медитативно! Всё оборудование и материалы — качественные, инструктор — настоящий профессионал. Теперь у меня дома стоит уникальная подставка под кофе, сделанная своими руками.
                </p>
                <span class="comit"><i class="fa fa-quote-right"></i></span>
              </div>
            </div>
            <div class="testimonial-box">
              <div class="author-test">
                <img src="img/doge3.jpg" class="rounded-circle b-shadow-a" width="150" height="150">
                <span class="author">Meow</span>
              </div>
              <div class="content-test">
                <p class="description lead">
                  Пришла на мастер-класс по декупажу с подругой — провели чудесный вечер! Всё было продумано до мелочей: от музыки и чая до мельчайших деталей в технике. За пару часов создали две красивые шкатулки. Это не просто обучение — это эмоции, творчество и отдых для души. Обязательно вернёмся!
                </p>
                <span class="comit"><i class="fa fa-quote-right"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <section class="paralax-mf footer-paralax bg-image  route" style="background-image: url(img/back_33.jpg)">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="contact-mf">
            <div id="contact">
              <div class="row">
                <div class="col-md-6">
                  <div class="title-box-2 pt-4 pt-md-0">
                    <h5 class="title-left text-white">
                      Связаться с нами
                    </h5>
                  </div>
                  <div class="more-info">
                    <ul class="list-ico text-white">
                      <li><span class="ion-ios-location"></span>просп. Независимости, 62</li>
                      <li><span class="ion-ios-telephone"></span> +375 (29) 221-01-53</li>
                      <li><span class="ion-email"></span> artstudio@gmail.com</li>
                    </ul>
                  </div>
                  <div class="socials">
                    <ul>
                      <li><a href=""><span class="ico-circle"><i class="ion-social-facebook"></i></span></a></li>
                      <li><a href=""><span class="ico-circle"><i class="ion-social-instagram"></i></span></a></li>
                      <li><a href=""><span class="ico-circle"><i class="ion-social-twitter"></i></span></a></li>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="copyright-box">
                    <p class="copyright" style="color:white">&copy; Copyright <strong>Art Workshop</strong>. All Rights Reserved</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ Section Contact-footer End /-->

  <!-- Modal -->
<div class="modal fade" id="techniqueModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTechniqueTitle">Техника</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalTechniqueDescription">
        Описание техники...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
  <!--/ Section Contact-footer End /-->

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
  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    // Объект с описаниями техник
    const techniqueDescriptions = {
      "Акварель": "Акварель — это волшебство воды и цвета! На мастер-классе вы познакомитесь с одной из самых живых и воздушных техник рисования. Её очарование — в прозрачности красок и мягких переливах, которые создаются на бумаге. Вы научитесь управлять текучей стихией, смешивать оттенки прямо на листе и создавать легкие, наполненные светом работы — от нежных пейзажей до ярких флоральных зарисовок. Это искусство тонкой импровизации, где каждая картина получается уникальной.",
      "Спиртовые чернила": "Спиртовые чернила — это взрыв цвета и полет фантазии! Наш МК познакомит вас с невероятно эффектными чернилами на спиртовой основе. Они живут своей жизнью: растекаются, смешиваются и создают фантастические узоры, напоминающие космические туманности или драгоценный мрамор. Вы будете работать с ними на глянцевых поверхностях, управляя узорами с помощью спирта, и создадите потрясающие абстрактные картины, украшения или предметы декора. Это техника, где невозможно ошибиться, а результат всегда поражает воображение!",
      "Декупаж": "Декупаж — это искусство преображения обычных вещей! С помощью этой техники вы легко превратите простую деревянную шкатулку, тарелку или бутылку в изысканный антикварный предмет. Секрет в переносе на поверхность тонкого бумажного изображения (из специальных салфеток или декупажных карт) и покрытии его лаком. После нашего мастер-класса вы сможете самостоятельно декорировать любые предметы в домашних условиях, создавая уникальные подарки и элементы интерьера с эффектом старины или росписи.",
      "Эпоксидная смола": "Эпоксидная смола — это магия прозрачности и объема. На мастер-классе вы поработаете с современным полимерным материалом, который после смешивания с отвердителем превращается в прочное, кристально-прозрачное стекло. Мы покажем вам, как создавать речные столы с 3D-эффектом, заливать украшения с сухоцветами, добавлять перламутровые пигменты и люминофоры. Это увлекательный процесс, где вы сами станете творцом своего «идеального льда» или «застывшего озера», а результат — профессиональное и невероятно стильное изделие.",
      "Керамика": "Керамика — это танец глины и ваших рук! Приглашаем вас погрузиться в медитативный и невероятно тактильный мир создания керамики. На занятии вы познакомитесь с натуральной глиной, узнаете основы работы на гончарном круге или технику ручной лепки. Вы почувствуете, как бесформенный комок материала оживает и послушно принимает форму чашки, вазы или тарелки. А после обжига в печи ваше творение станет полноценным и функциональным произведением искусства, хранящим тепло ваших рук.",
      "Текстурная паста": "Текстурная паста (паста для моделирования) — это ваши картины в 3D! С помощью этой удивительной техники вы выйдете за рамки плоского изображения и научитесь создавать настоящий рельеф на холсте. Паста по консистенции напоминает густую шпаклевку и наносится шпателем или мастихином. Вы сможете лепить объемные элементы, имитировать камень, кору деревьев, морские волны или фактурные абстракции. Это придает картинам невероятную выразительность и динамику, игра света и тени на рельефе делает работу живой.",
      "Мозаика": "Мозаика — это искусство складывать картину из маленьких кусочков! Как пазл, где вы сами создаете каждую деталь. На мастер-классе вы освоите технику набора мозаики из смальты, стекла или керамической плитки. Вы узнаете, как правильно колоть материал, выкладывать узор и затирать швы, чтобы получилась целостная и прочная картина или декоративное панно. Это медитативный и очень красивый процесс, результат которого будет радовать вас долгие годы, будь то интерьерное украшение или столешница для садового столика.",
      "Эбру": "Эбру — волшебное рисование на воде! Это древняя техника, которая никого не оставляет равнодушным. Вы будете создавать уникальные узоры не на бумаге, а на особой густой воде, а затем переносить их на бумагу, ткань или дерево. Краски, не смешиваясь, растекаются по поверхности, образуя причудливые разводы. С помощью шила и гребней вы научитесь управлять этой красотой, создавая удивительные орнаменты, похожие на цветы (особенно тюльпаны), которые невозможно повторить. Каждая работа, созданная в технике эбру, единственна и неповторима!"
    };

    // Обработчик клика по карточке
    document.querySelectorAll('.service-box').forEach(box => {
      box.addEventListener('click', function () {
        const name = this.getAttribute('data-name');
        const description = techniqueDescriptions[name] || 'Описание недоступно.';

        document.getElementById('modalTechniqueTitle').textContent = name;
        document.getElementById('modalTechniqueDescription').textContent = description;

        // Открыть модальное окно (Bootstrap 4+)
        $('#techniqueModal').modal('show');
      });
    });
  });
  </script>
</body>
</html>
