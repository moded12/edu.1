<?php
// FILE: admin/views/add_class.php
require_once '../core/db.php';

// جلب التطبيقات
$apps = $conn->query("SELECT * FROM applications ORDER BY id DESC");

// إضافة صف جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $app_id = intval($_POST['app_id']);
  $name = trim($_POST['name']);
  if ($app_id && $name !== '') {
    $check = $conn->query("SELECT * FROM classes WHERE application_id = $app_id AND name = '$name'");
    if ($check->num_rows == 0) {
      $conn->query("INSERT INTO classes (application_id, name) VALUES ($app_id, '$name')");
      $success = '✅ تم إضافة الصف بنجاح';
    } else {
      $warning = '⚠️ هذا الصف مضاف مسبقًا لهذا التطبيق';
    }
  }
}

// جلب الصفوف المرتبطة بالتطبيق المحدد
$classList = [];
if (isset($_GET['app_id'])) {
  $app_id_filter = intval($_GET['app_id']);
  $classList = $conn->query("SELECT * FROM classes WHERE application_id = $app_id_filter ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>➕ إضافة صف</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; margin: 0; background: #f2f2f2; }
    .main-content { padding: 30px 20px; margin-right: 260px; }
    @media (max-width: 768px) {
      .main-content { margin-right: 0; padding: 20px; }
    }
    .form-box { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 0 5px #ccc; }
    .table { background: white; margin-top: 30px; }
  </style>
</head>
<body>

<?php include('../includes/sidebar.php'); ?>

<div class="main-content">
  <h4 class="text-primary mb-4">➕ إضافة صف جديد</h4>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif (!empty($warning)): ?>
    <div class="alert alert-warning"><?= $warning ?></div>
  <?php endif; ?>

  <div class="form-box">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">📱 اختر التطبيق</label>
          <select name="app_id" class="form-select" required onchange="location.href='?app_id=' + this.value">
            <option value="">-- اختر --</option>
            <?php while($app = $apps->fetch_assoc()): ?>
              <option value="<?= $app['id'] ?>" <?= ($_GET['app_id'] ?? '') == $app['id'] ? 'selected' : '' ?>>
                <?= $app['name'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">🏫 اسم الصف</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-2 d-grid">
          <label class="form-label">&nbsp;</label>
          <button class="btn btn-primary">💾 حفظ</button>
        </div>
      </div>
    </form>
  </div>

  <?php if (isset($classList) && $classList && $classList->num_rows > 0): ?>
    <table class="table table-bordered mt-4">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>🏫 اسم الصف</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while($row = $classList->fetch_assoc()): ?>
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