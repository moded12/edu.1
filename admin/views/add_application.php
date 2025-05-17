<?php
// FILE: admin/views/add_application.php
require_once '../../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  if ($name !== '') {
    $stmt = $conn->prepare("INSERT INTO applications (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    echo "<script>location.href=window.location.href;</script>";
    exit;
  }
}
$apps = $conn->query("SELECT * FROM applications ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📱 إدارة التطبيقات</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; display: flex; margin: 0; }
    .sidebar {
      width: 250px;
      background-color: #1d1d1d;
      color: white;
      padding: 20px;
      height: 100vh;
    }
    .sidebar a {
      display: block;
      color: #ccc;
      text-decoration: none;
      padding: 8px 12px;
      margin-bottom: 6px;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #28a745;
      color: white;
    }
    .content {
      flex-grow: 1;
      padding: 30px;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>

<?php include('../sidebar.php'); ?>

<div class="content">
  <h3 class="mb-4 text-primary">📱 إضافة تطبيق جديد</h3>

  <form method="POST" class="mb-4">
    <div class="row g-3">
      <div class="col-md-6">
        <input type="text" name="name" class="form-control" placeholder="اسم التطبيق" required>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-success w-100">➕ إضافة</button>
      </div>
    </div>
  </form>

  <h5 class="mb-3">📋 التطبيقات المضافة:</h5>
  <table class="table table-bordered bg-white">
    <thead class="table-light">
      <tr><th>#</th><th>الاسم</th></tr>
    </thead>
    <tbody>
      <?php while($row = $apps->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
