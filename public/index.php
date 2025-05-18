<?php
// FILE: /httpdocs/edu.1/public/index.php
require_once '../admin/core/db.php';
$apps = $conn->query("SELECT id, name FROM applications ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📱 اختر التطبيق</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f5f5f5; padding: 30px; }
    .card-app { transition: 0.3s; cursor: pointer; }
    .card-app:hover { transform: scale(1.03); background: #e9f5e9; }
    .card-title { font-size: 1.1rem; }
  </style>
</head>
<body>
<div class="container">
  <h4 class="text-center text-success mb-4">📱 اختر التطبيق لعرض المحتوى</h4>
  <div class="row g-4 justify-content-center">
    <?php if ($apps && $apps->num_rows > 0): ?>
      <?php while($row = $apps->fetch_assoc()): ?>
        <div class="col-md-3 col-6">
          <div class="card card-app shadow-sm" onclick="goToClass(<?= $row['id'] ?>)">
            <div class="card-body text-center">
              <div class="fw-bold card-title"><?= htmlspecialchars($row['name']) ?></div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">🚫 لا توجد تطبيقات متاحة</p>
    <?php endif; ?>
  </div>
</div>

<script>
function goToClass(appId) {
  window.location.href = 'classes.php?app_id=' + appId;
}
</script>
</body>
</html>