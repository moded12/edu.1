
<?php
// FILE: public/lessons.php
require_once '../admin/core/db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : 0;
$semester_id = isset($_GET['semester_id']) ? intval($_GET['semester_id']) : 0;
$section_id = isset($_GET['section_id']) ? intval($_GET['section_id']) : 0;
$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

if (!$group_id) die("❌ بيانات ناقصة");

$group = $conn->query("SELECT name FROM edu_groups WHERE id = $group_id")->fetch_assoc();
$lessons = $conn->query("SELECT id, name FROM lessons WHERE group_id = $group_id ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📄 الدروس</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f4f4f4; padding: 30px; }
    .lesson-item { background: white; padding: 15px; border-radius: 10px; margin-bottom: 10px; box-shadow: 0 0 5px #ccc; cursor: pointer; transition: 0.2s; }
    .lesson-item:hover { background-color: #f0fdf4; }
    a { text-decoration: none; color: inherit; }
  </style>
</head>
<body>
<div class="container">
  <h4 class="text-success mb-4">📄 الدروس - <?= htmlspecialchars($group['name']) ?></h4>

  <?php if ($lessons && $lessons->num_rows > 0): ?>
    <?php while($row = $lessons->fetch_assoc()): ?>
      <div class="lesson-item">
        <a href="viewer.php?lesson_id=<?= $row['id'] ?>">
          <?= htmlspecialchars($row['name']) ?>
        </a>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center">🚫 لا توجد دروس متاحة لهذه المجموعة.</p>
  <?php endif; ?>
</div>
</body>
</html>
