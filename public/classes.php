<?php
// FILE: /httpdocs/edu.1/public/classes.php
require_once '../admin/core/db.php';

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
if (!$app_id) die('❌ تطبيق غير محدد');

$app = $conn->query("SELECT name FROM applications WHERE id = $app_id")->fetch_assoc();
$classes = $conn->query("SELECT id, name FROM classes WHERE application_id = $app_id ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📚 اختر الصف</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f5f5f5; padding: 30px; }
    .card-class { transition: 0.3s; cursor: pointer; }
    .card-class:hover { transform: scale(1.03); background: #eaf4ff; }
    .card-title { font-size: 1.1rem; }
    .back-link { text-decoration: none; color: #888; font-size: 14px; display: inline-block; margin-bottom: 10px; }
  </style>
</head>
<body>
<div class="container">
  <a href="index.php" class="back-link">← العودة للتطبيقات</a>
  <h4 class="text-success mb-4">📚 اختر الصف - <?= htmlspecialchars($app['name']) ?></h4>
  <div class="row g-4">
    <?php if ($classes && $classes->num_rows > 0): ?>
      <?php while($row = $classes->fetch_assoc()): ?>
        <div class="col-md-3 col-6">
          <div class="card card-class shadow-sm text-center" onclick="goToMaterials(<?= $row['id'] ?>)">
            <div class="card-body fw-bold">
              <?= htmlspecialchars($row['name']) ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">🚫 لا توجد صفوف متاحة لهذا التطبيق.</p>
    <?php endif; ?>
  </div>
</div>

<script>
function goToMaterials(classId) {
  window.location.href = 'materials.php?app_id=<?= $app_id ?>&class_id=' + classId;
}
</script>
</body>
</html>