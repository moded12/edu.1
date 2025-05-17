<?php
// FILE: admin/views/add_material.php
require_once '../../config/db.php';

// إضافة مادة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['class_id'])) {
  $name = trim($_POST['name']);
  $class_id = intval($_POST['class_id']);
  if ($name !== '' && $class_id > 0) {
    $stmt = $conn->prepare("INSERT INTO materials (class_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $class_id, $name);
    $stmt->execute();
    header("Location: add_material.php?application_id=" . intval($_POST['application_id']));
    exit;
  }
}

// حذف مادة
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM materials WHERE id = $id");
  $app = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;
  header("Location: add_material.php?application_id=$app");
  exit;
}

// تعديل مادة
if (isset($_POST['edit_id'], $_POST['edit_name'])) {
  $edit_id = intval($_POST['edit_id']);
  $edit_name = trim($_POST['edit_name']);
  if ($edit_name !== '') {
    $stmt = $conn->prepare("UPDATE materials SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $edit_name, $edit_id);
    $stmt->execute();
    header("Location: add_material.php?application_id=" . intval($_POST['application_id']));
    exit;
  }
}

$apps = $conn->query("SELECT * FROM applications");
$current_app = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;
$classes = ($current_app > 0) ? $conn->query("SELECT * FROM classes WHERE application_id = $current_app ORDER BY id DESC") : null;

$materials = $conn->query("SELECT materials.id, materials.name, classes.name AS class_name
                           FROM materials
                           JOIN classes ON materials.class_id = classes.id
                           WHERE classes.application_id = $current_app
                           ORDER BY materials.id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📘 إدارة المواد</title>
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
  <h3 class="mb-4 text-primary">📘 إضافة مادة جديدة</h3>

  <!-- اختيار التطبيق -->
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
  <!-- نموذج إضافة مادة -->
  <form method="POST" class="row g-3 mb-4">
    <input type="hidden" name="application_id" value="<?= $current_app ?>">
    <div class="col-md-4">
      <?php if (isset($classes)): ?>
      <select name="class_id" class="form-select" required>
        <option value="">اختر الصف</option>
        <?php while ($c = $classes->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <?php endif; ?>
    </div>
    <div class="col-md-4">
      <input type="text" name="name" class="form-control" placeholder="اسم المادة" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success w-100">➕ إضافة</button>
    </div>
  </form>

  <!-- عرض المواد -->
  <h5 class="mb-3">📋 المواد التابعة للتطبيق:</h5>
  <table class="table table-bordered bg-white">
    <thead class="table-light">
      <tr><th>#</th><th>المادة</th><th>الصف</th><th>تحكم</th></tr>
    </thead>
    <tbody>
      <?php while($row = $materials->fetch_assoc()): ?>
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
          <td><?= htmlspecialchars($row['class_name']) ?></td>
          <td>
            <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#confirmDeleteModal' onclick="setDeleteId(<?= $row['id'] ?>)">🗑️ حذف</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Delete Modal -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="confirmDeleteModalLabel">تأكيد الحذف</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body text-center">
          ⚠️ هل أنت متأكد أنك تريد حذف هذه المادة؟
        </div>
        <div class="modal-footer">
          <form method="GET">
            <input type="hidden" name="application_id" value="<?= $current_app ?>">
            <input type="hidden" name="delete" id="delete_id_field">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
  function setDeleteId(id) {
    document.getElementById('delete_id_field').value = id;
  }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php endif; ?>
</div>

</body>
</html>
