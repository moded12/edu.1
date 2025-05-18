<?php
// FILE: /httpdocs/edu.1/public/materials.php
require_once '../admin/core/db.php';

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

if (!$app_id || !$class_id) die('❌ بيانات ناقصة');

$app = $conn->query("SELECT name FROM applications WHERE id = $app_id")->fetch_assoc();
$class = $conn->query("SELECT name FROM classes WHERE id = $class_id")->fetch_assoc();

$materials = $conn->query("SELECT * FROM materials WHERE application_id = $app_id AND class_id = $class_id ORDER BY id ASC");

function getSemesters($conn, $material_id) {
  $semesters = $conn->query("SELECT * FROM semesters WHERE material_id = $material_id");
  $data = [];
  while ($row = $semesters->fetch_assoc()) {
    $semester_id = $row['id'];
    $sections = $conn->query("SELECT * FROM sections WHERE semester_id = $semester_id");
    $section_data = [];
    while ($s = $sections->fetch_assoc()) {
      $section_data[] = $s;
    }
    $row['sections'] = $section_data;
    $data[] = $row;
  }
  return $data;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>اختر المادة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f4f4f4; padding: 30px; }
    .material-btn { border: 2px solid #ccc; border-radius: 8px; padding: 15px; cursor: pointer; background: #fff; font-weight: bold; }
    .material-btn:hover { background-color: #e6f2ff; }
    .semester-block { background: #e7f5ff; padding: 10px 15px; border-radius: 8px; display: inline-block; margin: 10px 5px; font-weight: bold; }
    .section-list { margin: 10px 20px; }
    .section-item { background: white; padding: 8px 15px; margin: 4px 0; border-radius: 5px; border: 1px solid #ddd; }
    .hidden { display: none; }
  </style>
</head>
<body>
  <div class="container">
    <a href="classes.php?app_id=<?= $app_id ?>" class="text-muted d-block mb-2">← العودة للصفوف</a>
    <h4 class="text-success mb-4">📘 اختر المادة - <?= htmlspecialchars($class['name'] ?? '') ?> / <?= htmlspecialchars($app['name'] ?? '') ?></h4>

    <?php if ($materials && $materials->num_rows > 0): ?>
      <div class="row g-3 mb-4">
        <?php while($material = $materials->fetch_assoc()): ?>
          <div class="col-md-3 col-6">
            <div class="material-btn text-center" onclick="toggleMaterial(<?= $material['id'] ?>)">
              <?= htmlspecialchars($material['name']) ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <?php mysqli_data_seek($materials, 0); ?>
      <?php while($material = $materials->fetch_assoc()): 
        $semesters = getSemesters($conn, $material['id']);
      ?>
        <div id="material_<?= $material['id'] ?>" class="material-details hidden">
          <?php foreach($semesters as $semester): ?>
            <div class="semester-block"><?= htmlspecialchars($semester['name']) ?></div>
            <div class="section-list">
              <?php foreach($semester['sections'] as $sec): ?>
                <div class="section-item">
                  <a href="groups.php?app_id=<?= $app_id ?>&class_id=<?= $class_id ?>&material_id=<?= $material['id'] ?>&semester_id=<?= $semester['id'] ?>&section_id=<?= $sec['id'] ?>">
                    <?= htmlspecialchars($sec['name']) ?>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endwhile; ?>

    <?php else: ?>
      <div class="alert alert-warning text-center">🚫 لا توجد مواد متاحة لهذا الصف.</div>
    <?php endif; ?>
  </div>

  <script>
    function toggleMaterial(id) {
      document.querySelectorAll('.material-details').forEach(div => div.classList.add('hidden'));
      const target = document.getElementById('material_' + id);
      if (target) target.classList.remove('hidden');
    }
  </script>
</body>
</html>