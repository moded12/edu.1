<?php
// FILE: admin/views/add_class.php
require_once '../../config/db.php';

// إضافة صف
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['application_id'])) {
  $name = trim($_POST['name']);
  $app_id = intval($_POST['application_id']);
  if ($name !== '' && $app_id > 0) {
    $stmt = $conn->prepare("INSERT INTO classes (application_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $app_id, $name);
    $stmt->execute();
    header("Location: add_class.php?application_id=$app_id");
    exit;
  }
}

// حذف صف
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM classes WHERE id = $id");
  header("Location: add_class.php");
  exit;
}

// تعديل صف
if (isset($_POST['edit_id'])) {
  $edit_id = intval($_POST['edit_id']);
  $edit_name = trim($_POST['edit_name']);
  if ($edit_name !== '') {
    $stmt = $conn->prepare("UPDATE classes SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $edit_name, $edit_id);
    $stmt->execute();
    header("Location: add_class.php?application_id=" . intval($_POST['application_id']));
    exit;
  }
}

$apps = $conn->query("SELECT * FROM applications");

// فلترة حسب التطبيق
$current_app = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;
if ($current_app > 0) {
  $classes = $conn->query("SELECT * FROM classes WHERE application_id = $current_app ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📚 إدارة الصفوف</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; display: flex; margin: 0; }
    .sidebar {
      width: 250px;
      background-color: #1d1d1d;
      color: white;
      padding: 20px;
      height: 100vh;
    }
    .sidebar a {
      display: block;
      color: #ccc;
      text-decoration: none;
      padding: 8px 12px;
      margin-bottom: 6px;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #28a745;
      color: white;
    }
    .content {
      flex-grow: 1;
      padding: 30px;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>

<?php include('../sidebar.php'); ?>

<div class="content">
  <h3 class="mb-4 text-primary">📚 إضافة صف جديد</h3>

  <!-- اختيار التطبيق لعرض الصفوف -->
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
      <select name="application_id" class="form-select" onchange="this.form.submit()">
        <option value="">اختر التطبيق</option>
        <?php while ($a = $apps->fetch_assoc()): ?>
          <option value="<?= $a['id'] ?>" <?= ($current_app == $a['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($a['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
  </form>

  <?php if ($current_app): ?>
  <!-- نموذج الإضافة -->
  <form method="POST" class="mb-4 row g-3">
    <input type="hidden" name="application_id" value="<?= $current_app ?>">
    <div class="col-md-4">
      <input type="text" name="name" class="form-control" placeholder="اسم الصف" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success w-100">➕ إضافة</button>
    </div>
  </form>

  <!-- عرض الصفوف الخاصة بالتطبيق -->
  <h5 class="mb-3">📋 الصفوف التابعة للتطبيق:</h5>
  <table class="table table-bordered bg-white">
    <thead class="table-light">
      <tr><th>#</th><th>الصف</th><th>تحكم</th></tr>
    </thead>
    <tbody>
      <?php while($row = $classes->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td>
            <form method="POST" class="d-flex">
              <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="application_id" value="<?= $current_app ?>">
              <input type="text" name="edit_name" class="form-control me-2" value="<?= htmlspecialchars($row['name']) ?>">
              <button type="submit" class="btn btn-sm btn-primary">✏️</button>
            </form>
          </td>
          <td>
            <a href="?delete=<?= $row['id'] ?>&application_id=<?= $current_app ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل تريد الحذف؟')">🗑️ حذف</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

</body>
</html>