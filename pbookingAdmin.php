<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('location:index.php');
    exit();
}

$con = mysqli_connect('localhost', 'root', '', 'art_test');
if (!$con) {
    die(json_encode(['success' => false, 'message' => 'DB connection failed']));
}

// === AJAX: обновление ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_workshop') {
    header('Content-Type: application/json');

    $id = (int)$_POST['workshop_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $cost = (float)$_POST['cost'];
    $difficulty = trim($_POST['difficulty_level']);

    if ($id <= 0 || $name === '') {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit();
    }

    $stmt = mysqli_prepare($con, "UPDATE workshop SET name=?, description=?, cost=?, difficulty_level=? WHERE workshop_id=?");
    mysqli_stmt_bind_param($stmt, "ssdsi", $name, $description, $cost, $difficulty, $id);
    $ok = mysqli_stmt_execute($stmt);
    echo json_encode(['success' => $ok]);
    exit();
}

// === AJAX: удаление ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_workshop') {
    header('Content-Type: application/json');
    $id = (int)$_POST['workshop_id'];
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit();
    }
    $ok = mysqli_query($con, "DELETE FROM workshop WHERE workshop_id=$id");
    echo json_encode(['success' => $ok]);
    exit();
}
?>
<!DOCTYPE html>
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

  <style>
    body {
      background-color: #1a0000;
      color: #fff;
      font-family: "Segoe UI", sans-serif;
      background-image: url(img/info_back1.jpg);
      background-size: cover;
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
      margin-left: 50px;
      margin-right: 50px;
    }
    h3 {
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
    .btn-outline-warning {
      color: #ffb347;
      border-color: #ffb347;
    }
    .btn-outline-warning:hover {
      background-color: #ffb347;
      color: #fff;
    }
    .btn-outline-danger {
      color: #ff6666;
      border-color: #ff6666;
    }
    .btn-outline-danger:hover {
      background-color: #ff6666;
      color: #fff;
    }
    .modal-content {
      background: #1b1b1b;
      color: #f5f5f5;
      border: 1px solid #ff8d8d;
      border-radius: 10px;
      box-shadow: 0 0 25px rgb(255 193 193 / 25%);
    }
    .modal-header {
      background-color: #411b1bba;
      border-bottom: 1px solid #ff8d8d;
      color: #ffcccc;
    }
    .modal-footer {
      border-top: 1px solid #ff8d8d;
      background-color: #411b1bba;
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
</head>

<body>
<nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll" href="#page-top">Мастерская искусств</a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarDefault"
        aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span></span><span></span><span></span>
      </button>
      <div class="navbar-collapse collapse justify-content-end" id="navbarDefault">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link js-scroll active" href="admin.php">Назад</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="intro route bg-image" style="margin-top: 150px;">
    <div class="card p-3">
      <h3 class="text-center">Текущие мастер-классы</h3>
      <div class="text-end mb-3">
        <a href="registration/workshop.php" class="btn btn-danger btn-sm">+ Новый МК</a>
      </div>

<?php
$sql = "
  SELECT w.workshop_id, w.name AS workshop_name, w.cost, w.difficulty_level, w.description,
         t.name AS technique_name, m.first_name, m.last_name,
         s.session_date, s.start_time, s.end_time
  FROM workshop w
  JOIN technique t ON w.technique_id=t.technique_id
  JOIN master m ON w.master_id=m.master_id
  JOIN session s ON w.workshop_id=s.workshop_id
  ORDER BY s.session_date, s.start_time";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<div class="scrollable-workshops">';
    echo '<table class="table table-hover">';
    echo '<thead><tr>
            <th>Название</th>
            <th>Техника</th>
            <th>Уроовень сложности</th>
            <th>Дата и время</th>
            <th>Стоимость</th>
            <th>Действия</th>
          </tr></thead><tbody>';
    while ($r = mysqli_fetch_assoc($result)) {
        $id = $r['workshop_id'];
        $time = date("d.m.Y", strtotime($r['session_date'])) . " (" . substr($r['start_time'], 0, 5) . "–" . substr($r['end_time'], 0, 5) . ")";
        echo "<tr>
                <td><b>" . htmlspecialchars($r['workshop_name']) . "</b></td>
                <td>" . htmlspecialchars($r['technique_name']) . "</td>
                <td>" . htmlspecialchars($r['difficulty_level']) . "</td>
                <td>$time</td>
                <td>" . number_format($r['cost'], 2) . "</td>
                <td>
                  <button class='btn btn-sm btn-outline-info' data-bs-toggle='modal' data-bs-target='#details$id'>Детали</button>
                  <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#edit$id'>Редактировать</button>
                  <button class='btn btn-sm btn-outline-danger delete-workshop' data-id='$id'>Удалить</button>
                </td>
              </tr>";

        echo "<div class='modal fade' id='details$id' tabindex='-1'>
                <div class='modal-dialog modal-dialog-centered'>
                  <div class='modal-content'>
                    <div class='modal-header border-danger'>
                      <h5 class='modal-title'>" . htmlspecialchars($r['workshop_name']) . "</h5>
                      <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                      <p><strong>Мастер:</strong> " . htmlspecialchars($r['first_name'].' '.$r['last_name']) . "</p>
                      <p><strong>Техника:</strong> " . htmlspecialchars($r['technique_name']) . "</p>
                      <p><strong>Уровень сложности:</strong> " . htmlspecialchars($r['difficulty_level']) . "</p>
                      <p><strong>Стоимсоть:</strong> " . number_format($r['cost'],2) . "</p>
                      <p><strong>Дата и время:</strong> $time</p>
                      <hr>
                      <p><strong>Описание:</strong></p>
                      <p>" . nl2br(htmlspecialchars($r['description'])) . "</p>
                    </div>
                    <div class='modal-footer'>
                      <button class='btn btn-secondary' data-bs-dismiss='modal'>Закрыть</button>
                    </div>
                  </div>
                </div>
              </div>";

        echo "<div class='modal fade' id='edit$id' tabindex='-1'>
                <div class='modal-dialog modal-dialog-centered'>
                  <div class='modal-content'>
                    <div class='modal-header border-warning'>
                      <h5 class='modal-title'>Edit Workshop</h5>
                      <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                      <input type='hidden' id='id$id' value='$id'>
                      <div class='mb-2'>
                        <label>Title</label>
                        <input type='text' class='form-control bg-secondary text-white' id='name$id' value='" . htmlspecialchars($r['workshop_name']) . "'>
                      </div>
                      <div class='mb-2'>
                        <label>Description</label>
                        <textarea class='form-control bg-secondary text-white' id='desc$id'>" . htmlspecialchars($r['description']) . "</textarea>
                      </div>
                      <div class='mb-2'>
                        <label>Cost</label>
                        <input type='number' step='0.01' class='form-control bg-secondary text-white' id='cost$id' value='" . htmlspecialchars($r['cost']) . "'>
                      </div>
                      <div class='mb-2'>
                        <label>Difficulty Level</label>
                        <input type='text' class='form-control bg-secondary text-white' id='diff$id' value='" . htmlspecialchars($r['difficulty_level']) . "'>
                      </div>
                    </div>
                    <div class='modal-footer'>
                      <button class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                      <button class='btn btn-warning' onclick='saveWorkshop($id)'>Save</button>
                    </div>
                  </div>
                </div>
              </div>";
    }
    echo '</tbody></table></div>';
} else {
    echo "<p class='text-center text-muted'>No available workshops.</p>";
}
mysqli_close($con);
?>
  </div>
</div>

<script>
function saveWorkshop(id){
  const data = new URLSearchParams({
    action:'edit_workshop',
    workshop_id:id,
    name:document.getElementById('name'+id).value,
    description:document.getElementById('desc'+id).value,
    cost:document.getElementById('cost'+id).value,
    difficulty_level:document.getElementById('diff'+id).value
  });
  fetch('',{method:'POST',body:data})
  .then(r=>r.json())
  .then(res=>{
    if(res.success){alert('Updated!');location.reload();}
    else alert('Error updating');
  });
}

document.querySelectorAll('.delete-workshop').forEach(btn=>{
  btn.onclick=()=>{
    if(!confirm('Delete this workshop?'))return;
    const id=btn.dataset.id;
    const data=new URLSearchParams({action:'delete_workshop',workshop_id:id});
    fetch('',{method:'POST',body:data})
    .then(r=>r.json())
    .then(res=>{
      if(res.success){alert('Deleted');btn.closest('tr').remove();}
      else alert('Error deleting');
    });
  };
});
</script>
</body>
</html>
