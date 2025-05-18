<?php
require_once '../core/db.php';
$id = intval($_GET['id'] ?? 0);
$lesson = $conn->query("SELECT * FROM lessons WHERE id = $id")->fetch_assoc();
$apps = $conn->query("SELECT * FROM applications ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_app = intval($_POST['app_id']);
  $new_class = intval($_POST['class_id']);
  $new_material = intval($_POST['material_id']);
  $new_semester = intval($_POST['semester_id']);
  $new_section = intval($_POST['section_id']);
  $new_group = intval($_POST['group_id']);

  $conn->query("UPDATE lessons SET app_id=$new_app, class_id=$new_class, material_id=$new_material, semester_id=$new_semester, section_id=$new_section, group_id=$new_group WHERE id=$id");
  echo "<script>alert('✅ تم نقل الدرس بنجاح'); window.location='view_lessons.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>🔀 نقل الدرس</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-4" style="font-family: 'Cairo', sans-serif;">
  <h3 class="text-info mb-4">🔀 نقل الدرس إلى مكان آخر</h3>
  <form method="POST" class="row g-3">
    <div class="col-md-2"><label>📱 التطبيق</label><input type="number" name="app_id" class="form-control" required></div>
    <div class="col-md-2"><label>🏫 الصف</label><input type="number" name="class_id" class="form-control" required></div>
    <div class="col-md-2"><label>📘 المادة</label><input type="number" name="material_id" class="form-control" required></div>
    <div class="col-md-2"><label>📅 الفصل</label><input type="number" name="semester_id" class="form-control" required></div>
    <div class="col-md-2"><label>📂 القسم</label><input type="number" name="section_id" class="form-control" required></div>
    <div class="col-md-2"><label>👥 المجموعة</label><input type="number" name="group_id" class="form-control" required></div>
    <div class="col-md-12 text-end"><button class="btn btn-info">🔀 تنفيذ النقل</button></div>
  </form>
</body>
</html>
