<?php

/**
 * Отменяет бронь сеанса у пользователя
 * @param mysqli $con — соединение с БД
 * @param int $user_id — ID пользователя
 * @param int $session_id — ID сеанса
 * @return bool — true при успехе
 */
function cancelBooking($con, $user_id, $session_id) {
    $stmt = mysqli_prepare($con, "DELETE FROM booking WHERE user_id = ? AND session_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $session_id);
    return mysqli_stmt_execute($stmt);
}

/**
 * Бронирует сеанс для пользователя
 * @param mysqli $con — соединение с БД
 * @param int $user_id — ID пользователя
 * @param int $session_id — ID сеанса
 * @return bool — true при успехе
 */
function bookSession($con, $user_id, $session_id) {
    // Проверяем, не забронирован ли уже
    $stmt = mysqli_prepare($con, "SELECT 1 FROM booking WHERE user_id = ? AND session_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $session_id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
        return false; // уже забронировано
    }

    // Проверяем, существует ли сеанс
    $stmt = mysqli_prepare($con, "SELECT 1 FROM session WHERE session_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $session_id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_get_result($stmt)->num_rows === 0) {
        return false; // сеанс не существует
    }

    // Создаём бронь
    $stmt = mysqli_prepare($con, "INSERT INTO booking (user_id, session_id, status) VALUES (?, ?, 'confirmed')");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $session_id);
    return mysqli_stmt_execute($stmt);
}

   $con=mysqli_connect('localhost','root','','art_test');
   session_start();
  if (isset($_POST['admin'])) {
  	$name=$_POST['name'];
  	$psw=$_POST['psw'];
  	$sql="SELECT *  FROM admin WHERE name='$name'AND psw='$psw'";
  	$run=mysqli_query($con,$sql);
  	if(mysqli_num_rows($run)==1){
       $_SESSION['name']=$name;
       header('location:../admin.php');
  	}
  }
  if (isset($_POST['signup'])) {
  	$name=$_POST['name'];
    $last_name=$_POST['last_name'];
  	$uname=$_POST['uname'];
  	$email=$_POST['email'];
    $phone_number=$_POST['phone_number'];
  	$psw=$_POST['psw'];
  	$repsw=$_POST['repsw'];
  	if ($psw==$repsw) {
  	   $sql="SELECT * FROM user WHERE login='$uname'";
  	   $run=mysqli_query($con,$sql);
  	   if(mysqli_num_rows($run)>0){
         echo "<h2>ПОЛЬЗОВОТЕЛЬ УЖЕ СУЩЕСТВУЕТ!</h2>";
     	}else{
     		$sql="INSERT INTO `user` (`first_name`, `last_name`, `email`, `phone`, `login`, `password`)
         VALUES ('$name', '$last_name', '$email', '$phone_number', '$uname', '$psw')";
     	    $run=mysqli_query($con,$sql);
     	    if ($run) {
     	    	header('location:login.php');
     	    }
     	}	
  	}else{
  		 echo "<h2>Пароли не совпадают</h2>";
  	}
  }
if (isset($_POST['login'])) {
    $name=$_POST['name'];
    $psw=$_POST['psw'];
    $sql="SELECT * FROM user WHERE login='$name' AND password='$psw'";
    $run=mysqli_query($con,$sql);
    $row=mysqli_fetch_array($run);
    if(mysqli_num_rows($run)==1){
       $_SESSION['uname']=$name;
       $_SESSION['uid']=$row['user_id'];
       $_SESSION['urname']=$row['first_name'];
       header('location:../profile.php');
    }
  }
if (isset($_POST['tsignup'])) {
    $name=$_POST['name'];
    $last_name=$_POST['last_name'];
    $uname=$_POST['uname'];
    $email=$_POST['email'];
    $exp=$_POST['exp'];
    $bio=$_POST['bio'];
    $spec=$_POST['specialization'];
    $photo=$_POST['photo'];
    // $psw=$_POST['psw'];
    // $repsw=$_POST['repsw'];
    $sql="SELECT * FROM master WHERE first_name='$name'";
    $run=mysqli_query($con,$sql);
    if(mysqli_num_rows($run)>0){
      echo "<h2>ПОЛЬЗОВОТЕЛЬ УЖЕ СУЩЕСТВУЕТ!</h2>";
    }else{
      $sql="INSERT INTO `master` (`first_name`, `last_name`, `specialization`, `experience_years`, `bio`, `photo`)
        VALUES ('$name', '$last_name', '$spec', '$exp', '$bio', '$photo')";
        $run=mysqli_query($con,$sql);
        if ($run) {
          header('location:../coachdetail.php');
        }
      } 
  }

  if (isset($_POST['book_session_id'])) {
    $session_id = (int)$_POST['book_session_id'];
    $user_id = (int)$_SESSION['uid']; 
    if (bookSession($con, $user_id, $session_id)) {
        $_SESSION['message'] = "Бронирование успешно создано!";
    } else {
        $_SESSION['error'] = "Не удалось забронировать (возможно, уже забронировано).";
    }
    header("Location: pbooking.php");
    exit();
}

if (isset($_POST['cancel_session_id'])) {
    $session_id = (int)$_POST['cancel_session_id'];
    $user_id = (int)$_SESSION['uid']; 
    if (cancelBooking($con, $user_id, $session_id)) {
        $_SESSION['message'] = "Бронь отменена.";
    } else {
        $_SESSION['error'] = "Не удалось отменить бронь.";
    }
    header("Location: pbooking.php");
    exit();
}

  if (isset($_POST['tlogin'])) {
  	$name=$_POST['name'];
  	$psw=$_POST['psw'];
  	$sql="SELECT *  FROM master WHERE first_name='$name'AND psw='$psw'";
  	$run=mysqli_query($con,$sql);
  	$row=mysqli_fetch_array($run);
  	if(mysqli_num_rows($run)==1){
       $_SESSION['cname']=$name;
       $_SESSION['cid']=$row['master_id'];
       $_SESSION['crname']=$row['first_name'];
       header('location:../coach.php');
  	}
  }
  
if (isset($_POST['workshop'])) {
    // 1. Получаем данные из формы
    $wname     = trim($_POST['wname'] ?? '');
    $venue     = trim($_POST['venue'] ?? '');
    $status    = trim($_POST['status'] ?? '');
    $technique_id = (int)($_POST['technique_id'] ?? 0);
    $level_id     = ($_POST['diff_level'] ?? '');
    $master_id    = (int)($_POST['master_id'] ?? 0); // ← важно: имя поля должно быть master_id!
    $fees      = (float)($_POST['fees'] ?? 0);
    $desc      = trim($_POST['desc'] ?? '');
    $date      = $_POST['date'] ?? ''; // для session.session_date
    $duration  = (int)($_POST['duration'] ?? 0);
    $materials = $_POST['materials'] ?? [];
    $descrition = $_POST['discrition'];

    // 2. Валидация обязательных полей
    if (empty($wname) || empty($venue) || empty($date)  || empty($descrition)|| $duration <= 0 || $fees < 0) {
        die("Ошибка: заполните все обязательные поля корректно.");
    }

    // 3. Проверка допустимых значений статуса (согласно ENUM в БД)
    $allowed_statuses = ['актуальный', 'отмененный', 'закрытый'];
    if (!in_array($status, $allowed_statuses)) {
        die("Ошибка: недопустимый статус.");
    }

    // 4. Начинаем транзакцию (всё или ничего)
    mysqli_autocommit($con, false);

    try {
        // === Шаг 1: Вставить мастер-класс в `workshop` ===
        $stmt = mysqli_prepare($con, 
            "INSERT INTO workshop (name, technique_id, difficulty_level, master_id, duration_minutes, cost, status, description)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sisiidss", 
            $wname, $technique_id, $level_id, $master_id, $duration, $fees, $status, $descrition);
        mysqli_stmt_execute($stmt);
        $workshop_id = mysqli_insert_id($con);

        // === Шаг 2: Вставить сеанс в `session` ===
        // Предположим, что время начала — 10:00, окончания — 10:00 + duration минут
        $start_time = '10:00:00';
        $end_time = date('H:i:s', strtotime("10:00") + $duration * 60);
        $capacity = 20; // можно сделать настраиваемым

        $stmt2 = mysqli_prepare($con,
            "INSERT INTO session (workshop_id, session_date, start_time, end_time, location, capacity)
             VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt2, "issssi", 
            $workshop_id, $date, $start_time, $end_time, $venue, $capacity);
        mysqli_stmt_execute($stmt2);

        // === Шаг 3: Обработать материалы ===
        if (!empty($materials)) {
            foreach ($materials as $item) {
                $material_id = (int)($item['material_id'] ?? 0);
                $quantity = (float)($item['quantity'] ?? 0);

                if ($material_id <= 0 || $quantity <= 0) continue;

                // Проверка существования материала (опционально, но рекомендуется)
                $check = mysqli_query($con, "SELECT 1 FROM material WHERE material_id = $material_id");
                if (!mysqli_num_rows($check)) continue;

                $stmt3 = mysqli_prepare($con,
                    "INSERT INTO workshop_material (workshop_id, material_id, quantity)
                     VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt3, "iid", $workshop_id, $material_id, $quantity);
                mysqli_stmt_execute($stmt3);
            }
        }

        // Подтверждаем транзакцию
        mysqli_commit($con);
        header('Location: ../admin.php?success=1');
        exit();

    } catch (Exception $e) {
        mysqli_rollback($con);
        die("Ошибка при создании мастер-класса: " . $e->getMessage());
    }
}


    if (isset($_POST['pjoin1'])) {
        $wid=$_POST['wid'];
        $tid=$_POST['tid'];
        $did=$_POST['did'];
        $pid=$_SESSION['uid'];
        $bdate=date("Y-m-d");
        $sql="UPDATE performer SET wid='$wid',ddate='$did',dtime='$tid',bookingdate='$bdate',payment='yes' WHERE pid='$pid'";
        $run=mysqli_query($con,$sql);
        if ($run) {
           echo "thanks for joining";
      }
     } 
  if (isset($_POST['dance1'])||isset($_POST['dance2'])||isset($_POST['dance3'])||isset($_POST['dance4'])||isset($_POST['dance5'])||isset($_POST['dance6'])||isset($_POST['dance7'])||isset($_POST['dance8'])) {
       $did=$_POST['did'];
        $pid=$_SESSION['uid'];
        $sql="UPDATE performer SET dance_style='$did' WHERE pid='$pid'";
        $run=mysqli_query($con,$sql);
        if ($run) {
           echo "Your Dance Style :"." ".$did." "." is Selected";
      }
      
  }
    if (isset($_POST['tjoin1'])) {
        $wid=$_POST['wid'];
        $cid=$_SESSION['cid'];
        $sql="UPDATE coach SET wid='$wid', cselect='In Action' WHERE cid='$cid'";
        $run=mysqli_query($con,$sql);
        if ($run) {
           echo "thanks for joining";
      }
     } 
  if (isset($_POST['dance11'])||isset($_POST['dance21'])||isset($_POST['dance31'])||isset($_POST['dance41'])||isset($_POST['dance51'])||isset($_POST['dance61'])||isset($_POST['dance71'])||isset($_POST['dance81'])) {
       $did=$_POST['did'];
        $cid=$_SESSION['cid'];
        $sql="UPDATE coach SET dstyle='$did' WHERE cid='$cid'";
        $run=mysqli_query($con,$sql);
        if ($run) {
           echo "Your Dance Style :"." ".$did." "." is Selected";
      }
      
  }
if (isset($_POST['cdetail'])) {
   $sql="SELECT * FROM coach WHERE cselect='In Action'";
   $run=mysqli_query($con,$sql);
   while ($row=mysqli_fetch_array($run)) {
         $cname=$row['cname'];
         $cid=$row['cid'];
         $dstyle=$row['dstyle'];
         $gender=$row['gender'];
            echo " <div class='row'>
          <div class='col-md-3'>
            <h4 class='text-center text-white'> $cname</h4>
          </div>
          <div class='col-md-2'>
            <h4 class='text-center text-white'>$gender</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$dstyle</h4>
          </div>
          <div class='col-md-2'>
               <h4 class='text-center text-white'><div class='btn btn-outline-info select' cid='$cid'>Select</div></h4>
          </div>
          <div class='col-md-2'>
              <h4 class='text-center text-white'><div class='btn btn-outline-danger reject' cid='$cid'>Reject</div></h4>
          </div>
        </div>";
   }
}
if (isset($_POST['selected1'])) {
   $sql="SELECT * FROM coach WHERE cselect='Selected'";
   $run=mysqli_query($con,$sql);
   while ($row=mysqli_fetch_array($run)) {
         $cname=$row['cname'];
         $cid=$row['cid'];
         $dstyle=$row['dstyle'];
         $gender=$row['gender'];
         $cselect=$row['cselect'];
            echo " <div class='row'>
          <div class='col-md-3'>
            <h4 class='text-center text-white'> $cname</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$gender</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$dstyle</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$cselect</h4>
          </div>
          
        </div>";
   }
}
if (isset($_POST['cselect'])) {
   $cid=$_POST['cid'];
   $sql="UPDATE coach SET  cselect='Selected' WHERE cid='$cid'";
        $run=mysqli_query($con,$sql);
        if ($run) {
           echo "Coach Selected";
      }
}
if (isset($_POST['creject'])) {
   $cid=$_POST['cid'];
   $sql="UPDATE coach SET  cselect='Not Selected' WHERE cid='$cid'";
        $run=mysqli_query($con,$sql);
        if ($run) {
           echo "Coach not Selected";
      }
}
if (isset($_POST['submit1'])) {
    $did=$_POST['did'];
   $sql="SELECT * FROM coach WHERE cselect='In Action'AND dstyle='$did'";
   $run=mysqli_query($con,$sql);
   while ($row=mysqli_fetch_array($run)) {
         $cname=$row['cname'];
         $cid=$row['cid'];
         $dstyle=$row['dstyle'];
         $gender=$row['gender'];
            echo " <div class='row'>
          <div class='col-md-3'>
            <h4 class='text-center text-white'> $cname</h4>
          </div>
          <div class='col-md-2'>
            <h4 class='text-center text-white'>$gender</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$dstyle</h4>
          </div>
          <div class='col-md-2'>
               <h4 class='text-center text-white'><div class='btn btn-outline-info select' cid='$cid'>Select</div></h4>
          </div>
          <div class='col-md-2'>
              <h4 class='text-center text-white'><div class='btn btn-outline-danger reject' cid='$cid'>Reject</div></h4>
          </div>
        </div>";
   }

}
if (isset($_POST['psubmit1'])) {
  $did=$_POST['did'];
  $bid=$_POST['bid'];
  $sql="SELECT * FROM performer WHERE dance_style='$did'AND bookingdate='$bid' ";
  $run=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($run)) {
    $pname=$row['pname'];
    $age=$row['age'];
    $dstyle=$row['dance_style'];
    $bdate=$row['bookingdate'];
     echo " <div class='row'>
          <div class='col-md-3'>
            <h4 class='text-center text-white'> $pname</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$age</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$dstyle</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$bdate</h4>
          </div>
          
        </div>";
  }
}
if (isset($_POST['preg1'])) {
  $sql="SELECT * FROM performer WHERE payment='yes' ";
  $run=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($run)) {
    $pname=$row['pname'];
    $age=$row['age'];
    $dstyle=$row['dance_style'];
    $bdate=$row['bookingdate'];
     echo " <div class='row'>
          <div class='col-md-3'>
            <h4 class='text-center text-white'> $pname</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$age</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$dstyle</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$bdate</h4>
          </div>
          
        </div>";
  }
}
if (isset($_POST['ptreg1'])) {
  $bid=date("Y-m-d");
  $sql="SELECT * FROM performer WHERE bookingdate='$bid'AND payment='yes'";
  $run=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($run)) {
    $pname=$row['pname'];
    $age=$row['age'];
    $dstyle=$row['dance_style'];
    $bdate=$row['bookingdate'];
     echo " <div class='row'>
          <div class='col-md-3'>
            <h4 class='text-center text-white'> $pname</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$age</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$dstyle</h4>
          </div>
          <div class='col-md-3'>
            <h4 class='text-center text-white'>$bdate</h4>
          </div>
          
        </div>";
  }
}

if (isset($_POST['uptw'])) {
   $lastdate=date("Y-m-d");
      $sql="SELECT * FROM workshop WHERE wshow='1'AND wdate='$lastdate' ";
      $run=mysqli_query($con,$sql);
    if (mysqli_num_rows($run)==1){
       $sql="UPDATE workshop SET wshow='0' WHERE wdate='$lastdate'";
       $run=mysqli_query($con,$sql);
       
    }
  }
if (isset($_POST['cancle1'])) {
    $pid=$_POST['pid'];
    $sql="DELETE FROM performer WHERE pid='$pid' ";
    $run=mysqli_query($con,$sql);
    if ($run) {
        unset($_SESSION['uid']);
    }
  }
?>