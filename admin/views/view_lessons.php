<?php
// FILE: admin/views/view_lessons.php
require_once '../core/db.php';
require_once '../includes/sidebar.php';

$apps = $conn->query("SELECT * FROM applications ORDER BY name");

$where = [];
if (!empty($_GET['app_id'])) $where[] = "lessons.app_id = " . intval($_GET['app_id']);
if (!empty($_GET['class_id'])) $where[] = "lessons.class_id = " . intval($_GET['class_id']);
if (!empty($_GET['material_id'])) $where[] = "lessons.material_id = " . intval($_GET['material_id']);
if (!empty($_GET['semester_id'])) $where[] = "lessons.semester_id = " . intval($_GET['semester_id']);
if (!empty($_GET['section_id'])) $where[] = "lessons.section_id = " . intval($_GET['section_id']);
if (!empty($_GET['group_id'])) $where[] = "lessons.group_id = " . intval($_GET['group_id']);
$where_sql = count($where) ? "WHERE " . implode(" AND ", $where) : "";

$query = "
SELECT lessons.*, 
  applications.name AS app_name,
  classes.name AS class_name,
  materials.name AS material_name,
  semesters.name AS semester_name,
  sections.name AS section_name,
  edu_groups.name AS group_name
FROM lessons
LEFT JOIN applications ON lessons.app_id = applications.id
LEFT JOIN classes ON lessons.class_id = classes.id
LEFT JOIN materials ON lessons.material_id = materials.id
LEFT JOIN semesters ON lessons.semester_id = semesters.id
LEFT JOIN sections ON lessons.section_id = sections.id
LEFT JOIN edu_groups ON lessons.group_id = edu_groups.id
$where_sql
ORDER BY lessons.id DESC
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📚 عرض الدروس</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { font-family: 'Cairo', sans-serif !important; background: #f4f4f4; }
    .main { margin-right: 220px; padding: 30px; }
    .form-select { font-size: 14px; }
  </style>
</head>
<body>

<div class="main">
  <h4 class="mb-4 text-success">📚 قائمة الدروس</h4>

  <form method="GET" id="filterForm" class="row g-2 mb-4">
    <div class="col-md-2">
      <select name="app_id" id="app_id" class="form-select">
        <option value="">📱 التطبيق</option>
        <?php while($a = $apps->fetch_assoc()): ?>
          <option value="<?= $a['id'] ?>" <?= ($_GET['app_id'] ?? '') == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-2"><select name="class_id" id="class_id" class="form-select"><option value="">🏫 الصف</option></select></div>
    <div class="col-md-2"><select name="material_id" id="material_id" class="form-select"><option value="">📘 المادة</option></select></div>
    <div class="col-md-2"><select name="semester_id" id="semester_id" class="form-select"><option value="">📅 الفصل</option></select></div>
    <div class="col-md-2"><select name="section_id" id="section_id" class="form-select"><option value="">📂 القسم</option></select></div>
    <div class="col-md-2"><select name="group_id" id="group_id" class="form-select"><option value="">👥 المجموعة</option></select></div>
    <div class="col-md-12 text-end mt-2">
      <button type="submit" class="btn btn-primary">🔍 عرض النتائج</button>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>📱 التطبيق</th>
        <th>🏫 الصف</th>
        <th>📘 المادة</th>
        <th>📅 الفصل</th>
        <th>📂 القسم</th>
        <th>👥 المجموعة</th>
        <th>📄 اسم الدرس</th>
        <th>📎 مرفقات</th>
        <th>⚙️ إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): $i = 1; ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['app_name']) ?></td>
            <td><?= htmlspecialchars($row['class_name']) ?></td>
            <td><?= htmlspecialchars($row['material_name']) ?></td>
            <td><?= htmlspecialchars($row['semester_name']) ?></td>
            <td><?= htmlspecialchars($row['section_name']) ?></td>
            <td><?= htmlspecialchars($row['group_name']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><a href="view_attachments.php?lesson_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">عرض</a></td>
            <td>
              <a href="edit_lesson.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
              <a href="delete_lesson.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف الدرس؟')">🗑️</a>
              <a href="move_lesson.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">🔀</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="10" class="text-center">🚫 لا توجد دروس مطابقة للفلاتر.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
function loadDropdown(id, target, action, selectedValue = '') {
  $.post('load_dropdown.php', { id: id, action: action }, function(data) {
    $('#' + target).html('<option value="">-- اختر --</option>' + data);
    if (selectedValue) $('#' + target).val(selectedValue);
  });
}

$(document).ready(function () {
  const app_id = "<?= $_GET['app_id'] ?? '' ?>";
  const class_id = "<?= $_GET['class_id'] ?? '' ?>";
  const material_id = "<?= $_GET['material_id'] ?? '' ?>";
  const semester_id = "<?= $_GET['semester_id'] ?? '' ?>";
  const section_id = "<?= $_GET['section_id'] ?? '' ?>";
  const group_id = "<?= $_GET['group_id'] ?? '' ?>";

  if (app_id) loadDropdown(app_id, 'class_id', 'classes', class_id);
  if (class_id) loadDropdown(class_id, 'material_id', 'materials', material_id);
  if (material_id) loadDropdown(material_id, 'semester_id', 'semesters', semester_id);
  if (semester_id) loadDropdown(semester_id, 'section_id', 'sections', section_id);
  if (section_id) loadDropdown(section_id, 'group_id', 'groups', group_id);

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