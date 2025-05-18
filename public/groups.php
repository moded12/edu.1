<?php
// FILE: public/groups.php
require_once '../admin/core/db.php';

$app_id = $_GET['app_id'] ?? 0;
$class_id = $_GET['class_id'] ?? 0;
$material_id = $_GET['material_id'] ?? 0;
$semester_id = $_GET['semester_id'] ?? 0;
$section_id = $_GET['section_id'] ?? 0;

// تأكد من جميع القيم
if (!$app_id || !$class_id || !$material_id || !$semester_id || !$section_id) {
  die('❌ بيانات ناقصة');
}

// جلب المجموعات المرتبطة بالقسم المحدد
$groups = $conn->query("SELECT id, name FROM edu_groups 
  WHERE application_id = $app_id 
    AND class_id = $class_id 
    AND material_id = $material_id 
    AND semester_id = $semester_id 
    AND section_id = $section_id
  ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>👥 اختر المجموعة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f4f4f4; padding: 30px; }
    .group-card { cursor: pointer; transition: 0.3s; }
    .group-card:hover { transform: scale(1.02); background-color: #f1fdfb; }
  </style>
</head>
<body>
<div class="container">
  <h4 class="text-success mb-4">👥 اختر المجموعة</h4>

  <div class="row g-3">
    <?php if ($groups && $groups->num_rows > 0): ?>
      <?php while($group = $groups->fetch_assoc()): ?>
        <div class="col-md-3 col-6">
          <div class="card group-card shadow-sm text-center" onclick="goToLessons(<?= $group['id'] ?>)">
            <div class="card-body fw-bold"><?= htmlspecialchars($group['name']) ?></div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-danger">🚫 لا توجد مجموعات متاحة حالياً.</p>
    <?php endif; ?>
  </div>
</div>

<script>
function goToLessons(groupId) {
  const params = new URLSearchParams({
    app_id: '<?= $app_id ?>',
    class_id: '<?= $class_id ?>',
    material_id: '<?= $material_id ?>',
    semester_id: '<?= $semester_id ?>',
    section_id: '<?= $section_id ?>',
    group_id: groupId
  });
  window.location.href = 'lessons.php?' + params.toString();
}
</script>
</body>
</html>