<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// FILE: admin/views/add_semester.php
require_once('../../config/db.php');


$app_id = $_GET['application_id'] ?? 0;
$selected_class = $_GET['class_id'] ?? 0;

$classes = [];
$materials = [];

if ($app_id) {
  $classes = $conn->query("SELECT * FROM classes WHERE application_id = $app_id")->fetch_all(MYSQLI_ASSOC);
  $materials = $conn->query("SELECT * FROM materials WHERE application_id = $app_id")->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📘 إضافة فصل دراسي</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f8f9fa; padding: 20px; }
    .container { max-width: 800px; margin: auto; }
    .form-control, .btn { border-radius: 0.75rem; }
  </style>
</head>
<body>
  <div class="container">
    <h3 class="text-primary mb-4">🕓 إضافة فصل دراسي</h3>

    <form action="save_semester.php" method="POST">
      <input type="hidden" name="application_id" value="<?= $app_id ?>">

      <div class="mb-3">
        <label>الصف:</label>
        <select name="class_id" class="form-control" required>
          <option value="">اختر الصف</option>
          <?php foreach($classes as $class): ?>
            <option value="<?= $class['id'] ?>" <?= $class['id'] == $selected_class ? 'selected' : '' ?>>
              <?= htmlspecialchars($class['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>المادة:</label>
        <select name="material_id" class="form-control" required>
          <option value="">اختر المادة</option>
          <?php foreach($materials as $mat): ?>
            <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>اسم الفصل:</label>
        <input type="text" name="semester_name" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success">➕ إضافة</button>
    </form>
  </div>
</body>
</html>