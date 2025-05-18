<?php
// FILE: admin/views/add_group.php
require_once '../core/db.php';

$apps = $conn->query("SELECT * FROM applications ORDER BY id DESC");

// إضافة مجموعة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $app_id = intval($_POST['app_id']);
  $class_id = intval($_POST['class_id']);
  $material_id = intval($_POST['material_id']);
  $semester_id = intval($_POST['semester_id']);
  $section_id = intval($_POST['section_id']);
  $name = trim($_POST['name']);

  if ($app_id && $class_id && $material_id && $semester_id && $section_id && $name !== '') {
    $check = $conn->query("SELECT * FROM edu_groups WHERE application_id=$app_id AND class_id=$class_id AND material_id=$material_id AND semester_id=$semester_id AND section_id=$section_id AND name='$name'");
    if ($check->num_rows == 0) {
      $conn->query("INSERT INTO edu_groups (application_id, class_id, material_id, semester_id, section_id, name) VALUES ($app_id, $class_id, $material_id, $semester_id, $section_id, '$name')");
      $success = '✅ تم إضافة المجموعة بنجاح';
    } else {
      $warning = '⚠️ هذه المجموعة مضافة مسبقًا';
    }
  }
}

$selected_app = $_GET['app_id'] ?? '';
$selected_class = $_GET['class_id'] ?? '';
$selected_material = $_GET['material_id'] ?? '';
$selected_semester = $_GET['semester_id'] ?? '';
$selected_section = $_GET['section_id'] ?? '';

$classes = $selected_app ? $conn->query("SELECT * FROM classes WHERE application_id = $selected_app") : [];
$materials = $selected_class ? $conn->query("SELECT * FROM materials WHERE class_id = $selected_class") : [];
$semesters = $selected_material ? $conn->query("SELECT * FROM semesters WHERE material_id = $selected_material") : [];
$sections = $selected_semester ? $conn->query("SELECT * FROM sections WHERE semester_id = $selected_semester") : [];
$groups = $selected_section ? $conn->query("SELECT * FROM edu_groups WHERE section_id = $selected_section") : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>➕ إضافة مجموعة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; margin: 0; background: #f2f2f2; }
    .main-content { padding: 30px 20px; margin-right: 260px; }
    @media (max-width: 768px) {
      .main-content { margin-right: 0; padding: 20px; }
    }
    .form-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px #ccc; }
    .table { background: white; margin-top: 30px; }
  </style>
</head>
<body>

<?php include('../includes/sidebar.php'); ?>

<div class="main-content">
  <h4 class="mb-4 text-primary">➕ إضافة مجموعة دراسية</h4>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif (!empty($warning)): ?>
    <div class="alert alert-warning"><?= $warning ?></div>
  <?php endif; ?>

  <div class="form-box">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">📱 التطبيق</label>
          <select name="app_id" class="form-select" onchange="location.href='?app_id=' + this.value">
            <option value="">-- اختر --</option>
            <?php if ($apps) while($a = $apps->fetch_assoc()): ?>
              <option value="<?= $a['id'] ?>" <?= ($selected_app == $a['id']) ? 'selected' : '' ?>><?= $a['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">🏫 الصف</label>
          <select name="class_id" class="form-select" onchange="location.href='?app_id=<?= $selected_app ?>&class_id=' + this.value">
            <option value="">-- الصف --</option>
            <?php if ($classes) while($c = $classes->fetch_assoc()): ?>
              <option value="<?= $c['id'] ?>" <?= ($selected_class == $c['id']) ? 'selected' : '' ?>><?= $c['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">📘 المادة</label>
          <select name="material_id" class="form-select" onchange="location.href='?app_id=<?= $selected_app ?>&class_id=<?= $selected_class ?>&material_id=' + this.value">
            <option value="">-- المادة --</option>
            <?php if ($materials) while($m = $materials->fetch_assoc()): ?>
              <option value="<?= $m['id'] ?>" <?= ($selected_material == $m['id']) ? 'selected' : '' ?>><?= $m['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">📅 الفصل</label>
          <select name="semester_id" class="form-select" onchange="location.href='?app_id=<?= $selected_app ?>&class_id=<?= $selected_class ?>&material_id=<?= $selected_material ?>&semester_id=' + this.value">
            <option value="">-- الفصل --</option>
            <?php if ($semesters) while($s = $semesters->fetch_assoc()): ?>
              <option value="<?= $s['id'] ?>" <?= ($selected_semester == $s['id']) ? 'selected' : '' ?>><?= $s['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">📂 القسم</label>
          <select name="section_id" class="form-select" onchange="location.href='?app_id=<?= $selected_app ?>&class_id=<?= $selected_class ?>&material_id=<?= $selected_material ?>&semester_id=<?= $selected_semester ?>&section_id=' + this.value">
            <option value="">-- القسم --</option>
            <?php if ($sections) while($sc = $sections->fetch_assoc()): ?>
              <option value="<?= $sc['id'] ?>" <?= ($selected_section == $sc['id']) ? 'selected' : '' ?>><?= $sc['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">👥 اسم المجموعة</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="col-md-6 d-grid">
          <label class="form-label">&nbsp;</label>
          <button class="btn btn-success">💾 حفظ</button>
        </div>
      </div>
    </form>
  </div>

  <?php if ($groups && $groups->num_rows > 0): ?>
    <table class="table table-bordered mt-4">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>👥 اسم المجموعة</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while($row = $groups->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
</html>