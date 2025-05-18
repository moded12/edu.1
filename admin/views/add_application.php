<?php
// FILE: admin/views/add_application.php
require_once '../core/db.php';
include('../includes/sidebar.php');

// إضافة التطبيق في قاعدة البيانات
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['app_name'])) {
  $name = $conn->real_escape_string(trim($_POST['app_name']));
  $conn->query("INSERT INTO applications (name) VALUES ('$name')");
  $success = '✅ تم حفظ التطبيق بنجاح.';
}

// جلب التطبيقات الحالية
$apps = $conn->query("SELECT * FROM applications ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>➕ إضافة تطبيق</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f9f9f9; margin: 0; }
    .main-content { padding: 30px 20px; margin-right: 260px; }
    @media (max-width: 768px) {
      .main-content { margin-right: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<div class="main-content">
  <h4 class="mb-4 text-primary">➕ إضافة تطبيق جديد</h4>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-3 mb-4">
    <div class="col-md-6">
      <label class="form-label">📱 اسم التطبيق</label>
      <input type="text" name="app_name" class="form-control" required>
    </div>
    <div class="col-md-6 d-flex align-items-end">
      <button type="submit" class="btn btn-success">💾 حفظ</button>
    </div>
  </form>

  <hr>
  <h5 class="text-secondary mb-3">📋 التطبيقات المُضافة</h5>
  <ul class="list-group">
    <?php while($row = $apps->fetch_assoc()): ?>
      <li class="list-group-item">🔹 <?= htmlspecialchars($row['name']) ?></li>
    <?php endwhile; ?>
  </ul>
</div>

</body>
</html>