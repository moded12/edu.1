<?php
// FILE: admin/views/add_semester.php
require_once '../core/db.php';

$apps = $conn->query("SELECT * FROM applications ORDER BY id DESC");

// إضافة فصل دراسي
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $app_id = intval($_POST['app_id']);
  $class_id = intval($_POST['class_id']);
  $material_id = intval($_POST['material_id']);
  $name = trim($_POST['name']);

  if ($app_id && $class_id && $material_id && $name !== '') {
    $check = $conn->query("SELECT * FROM semesters WHERE application_id=$app_id AND class_id=$class_id AND material_id=$material_id AND name='$name'");
    if ($check->num_rows == 0) {
      $conn->query("INSERT INTO semesters (application_id, class_id, material_id, name) VALUES ($app_id, $class_id, $material_id, '$name')");
      $success = '✅ تم إضافة الفصل بنجاح';
    } else {
      $warning = '⚠️ هذا الفصل مضاف مسبقًا';
    }
  }
}

$selected_app = $_GET['app_id'] ?? '';
$selected_class = $_GET['class_id'] ?? '';
$selected_material = $_GET['material_id'] ?? '';

$classes = $selected_app ? $conn->query("SELECT * FROM classes WHERE application_id = $selected_app") : [];
$materials = $selected_class ? $conn->query("SELECT * FROM materials WHERE class_id = $selected_class") : [];
$semesters = $selected_material ? $conn->query("SELECT * FROM semesters WHERE material_id = $selected_material") : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>➕ إضافة فصل دراسي</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  <h4 class="mb-4 text-primary">➕ إضافة فصل دراسي جديد</h4>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif (!empty($warning)): ?>
    <div class="alert alert-warning"><?= $warning ?></div>
  <?php endif; ?>

  <div class="form-box">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">📱 التطبيق</label>
          <select name="app_id" class="form-select" required onchange="location.href='?app_id=' + this.value">
            <option value="">-- اختر --</option>
            <?php if ($apps) while($a = $apps->fetch_assoc()): ?>
              <option value="<?= $a['id'] ?>" <?= ($selected_app == $a['id']) ? 'selected' : '' ?>><?= $a['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">🏫 الصف</label>
          <select name="class_id" class="form-select" required onchange="location.href='?app_id=<?= $selected_app ?>&class_id=' + this.value">
            <option value="">-- اختر الصف --</option>
            <?php if ($classes) while($c = $classes->fetch_assoc()): ?>
              <option value="<?= $c['id'] ?>" <?= ($selected_class == $c['id']) ? 'selected' : '' ?>><?= $c['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">📘 المادة</label>
          <select name="material_id" class="form-select" required onchange="location.href='?app_id=<?= $selected_app ?>&class_id=<?= $selected_class ?>&material_id=' + this.value">
            <option value="">-- اختر المادة --</option>
            <?php if ($materials) while($m = $materials->fetch_assoc()): ?>
              <option value="<?= $m['id'] ?>" <?= ($selected_material == $m['id']) ? 'selected' : '' ?>><?= $m['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">📅 اسم الفصل</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="col-md-6 d-grid">
          <label class="form-label">&nbsp;</label>
          <button class="btn btn-primary">💾 حفظ</button>
        </div>
      </div>
    </form>
  </div>

  <?php if ($semesters && $semesters->num_rows > 0): ?>
    <table class="table table-bordered mt-4">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>📅 اسم الفصل</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while($row = $semesters->fetch_assoc()): ?>
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