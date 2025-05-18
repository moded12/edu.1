<?php
// FILE: admin/views/edit_lesson.php
require_once '../core/db.php';
$id = intval($_GET['id'] ?? 0);
$lesson = $conn->query("SELECT * FROM lessons WHERE id = $id")->fetch_assoc();
$apps = $conn->query("SELECT * FROM applications ORDER BY name");

if (!$lesson) {
  echo "<h3 class='text-danger'>🚫 الدرس غير موجود</h3>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $app_id = intval($_POST['app_id']);
  $class_id = intval($_POST['class_id']);
  $material_id = intval($_POST['material_id']);
  $semester_id = intval($_POST['semester_id']);
  $section_id = intval($_POST['section_id']);
  $group_id = intval($_POST['group_id']);
  $name = $conn->real_escape_string($_POST['name']);
  $external_link = $conn->real_escape_string($_POST['external_link']);

  $conn->query("UPDATE lessons SET 
    app_id=$app_id, class_id=$class_id, material_id=$material_id, 
    semester_id=$semester_id, section_id=$section_id, group_id=$group_id, 
    name='$name' WHERE id=$id");

  if (!empty($external_link)) {
    $conn->query("DELETE FROM lesson_files WHERE lesson_id=$id");
    $conn->query("INSERT INTO lesson_files (lesson_id, file_path, type) 
                  VALUES ($id, '$external_link', 'link')");
  }

  if (!empty($_FILES['attachment']['name'])) {
    $filename = time() . '_' . basename($_FILES['attachment']['name']);
    $folder = date('Y-m');
    $path = "../../admin/views/uploads/lessons/$folder";
    if (!is_dir($path)) mkdir($path, 0777, true);
    move_uploaded_file($_FILES['attachment']['tmp_name'], "$path/$filename");

    $conn->query("DELETE FROM lesson_files WHERE lesson_id=$id");
    $conn->query("INSERT INTO lesson_files (lesson_id, file_path, type) 
                  VALUES ($id, '/admin/views/uploads/lessons/$folder/$filename', 'file')");
  }

  echo "<script>alert('✅ تم تعديل الدرس بنجاح'); window.location='view_lessons.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>✏️ تعديل الدرس</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f8f9fa; padding: 30px; }
    label { font-weight: bold; }
  </style>
</head>
<body>
<div class="container">
  <h4 class="text-success mb-4">✏️ تعديل درس</h4>

  <form method="POST" enctype="multipart/form-data" class="row g-3" id="editForm">
    <div class="col-md-2">
      <label>📱 التطبيق</label>
      <select name="app_id" id="app_id" class="form-select" required>
        <option value="">-- اختر --</option>
        <?php while($a = $apps->fetch_assoc()): ?>
          <option value="<?= $a['id'] ?>" <?= $a['id'] == $lesson['app_id'] ? 'selected' : '' ?>>
            <?= $a['name'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-2"><label>🏫 الصف</label>
      <select name="class_id" id="class_id" class="form-select" required></select>
    </div>
    <div class="col-md-2"><label>📘 المادة</label>
      <select name="material_id" id="material_id" class="form-select" required></select>
    </div>
    <div class="col-md-2"><label>📅 الفصل</label>
      <select name="semester_id" id="semester_id" class="form-select" required></select>
    </div>
    <div class="col-md-2"><label>📂 القسم</label>
      <select name="section_id" id="section_id" class="form-select" required></select>
    </div>
    <div class="col-md-2"><label>👥 المجموعة</label>
      <select name="group_id" id="group_id" class="form-select" required></select>
    </div>

    <div class="col-md-6"><label>📄 اسم الدرس</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($lesson['name']) ?>" required>
    </div>
    <div class="col-md-6"><label>🌐 رابط خارجي (اختياري)</label>
      <input type="url" name="external_link" class="form-control">
    </div>

    <div class="col-md-12"><label>📎 مرفق جديد (اختياري)</label>
      <input type="file" name="attachment" class="form-control">
    </div>

    <div class="col-md-12 text-end">
      <button class="btn btn-primary px-5">💾 حفظ التعديل</button>
    </div>
  </form>
</div>

<script>
function loadDropdown(id, target, action, selected = '') {
  $.post('load_dropdown.php', { id: id, action: action }, function(data) {
    $('#' + target).html('<option value="">-- اختر --</option>' + data);
    if (selected) $('#' + target).val(selected);
  });
}

$(document).ready(function () {
  const app = <?= $lesson['app_id'] ?>;
  const classId = <?= $lesson['class_id'] ?>;
  const mat = <?= $lesson['material_id'] ?>;
  const sem = <?= $lesson['semester_id'] ?>;
  const sec = <?= $lesson['section_id'] ?>;
  const group = <?= $lesson['group_id'] ?>;

  if (app) loadDropdown(app, 'class_id', 'classes', classId);
  if (classId) loadDropdown(classId, 'material_id', 'materials', mat);
  if (mat) loadDropdown(mat, 'semester_id', 'semesters', sem);
  if (sem) loadDropdown(sem, 'section_id', 'sections', sec);
  if (sec) loadDropdown(sec, 'group_id', 'groups', group);

  $('#app_id').on('change', function() {
    loadDropdown(this.value, 'class_id', 'classes');
    $('#material_id, #semester_id, #section_id, #group_id').html('<option value="">-- اختر --</option>');
  });
  $('#class_id').on('change', function() {
    loadDropdown(this.value, 'material_id', 'materials');
    $('#semester_id, #section_id, #group_id').html('<option value="">-- اختر --</option>');
  });
  $('#material_id').on('change', function() {
    loadDropdown(this.value, 'semester_id', 'semesters');
    $('#section_id, #group_id').html('<option value="">-- اختر --</option>');
  });
  $('#semester_id').on('change', function() {
    loadDropdown(this.value, 'section_id', 'sections');
    $('#group_id').html('<option value="">-- اختر --</option>');
  });
  $('#section_id').on('change', function() {
    loadDropdown(this.value, 'group_id', 'groups');
  });
});
</script>
</body>
</html>