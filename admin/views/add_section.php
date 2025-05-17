<?php
// FILE: admin/views/add_section.php
require_once '../../config/db.php';

// إضافة قسم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['semester_id'])) {
  $name = trim($_POST['name']);
  $semester_id = intval($_POST['semester_id']);
  if ($name !== '' && $semester_id > 0) {
    $stmt = $conn->prepare("INSERT INTO sections (semester_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $semester_id, $name);
    $stmt->execute();
    header("Location: add_section.php?application_id=" . intval($_POST['application_id']) . "&class_id=" . intval($_POST['class_id']));
    exit;
  }
}

// حذف
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM sections WHERE id = $id");
  $app = intval($_GET['application_id']);
  $cls = intval($_GET['class_id']);
  header("Location: add_section.php?application_id=$app&class_id=$cls");
  exit;
}

// تعديل
if (isset($_POST['edit_id'], $_POST['edit_name'])) {
  $edit_id = intval($_POST['edit_id']);
  $edit_name = trim($_POST['edit_name']);
  if ($edit_name !== '') {
    $stmt = $conn->prepare("UPDATE sections SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $edit_name, $edit_id);
    $stmt->execute();
    header("Location: add_section.php?application_id=" . intval($_POST['application_id']) . "&class_id=" . intval($_POST['class_id']));
    exit;
  }
}

$apps = $conn->query("SELECT * FROM applications");
$current_app = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;
$current_class = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

$classes = ($current_app > 0) ? $conn->query("SELECT * FROM classes WHERE application_id = $current_app") : null;
$semesters = ($current_class > 0) ? $conn->query("SELECT * FROM semesters WHERE material_id = $current_class") : null;

$sections = $conn->query("SELECT sections.id, sections.name, semesters.name AS semester_name
  FROM sections
  JOIN semesters ON sections.semester_id = semesters.id
  JOIN classes ON semesters.material_id = classes.id
  WHERE classes.application_id = $current_app AND classes.id = $current_class
  ORDER BY sections.id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>🏷️ إدارة الأقسام</title>
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
  <h3 class="mb-4 text-primary">🏷️ إضافة قسم دراسي</h3>

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
    <?php if ($current_app > 0): ?>
    <div class="col-md-4">
      <select name="class_id" class="form-select" onchange="this.form.submit()">
        <option value="">اختر الصف</option>
        <?php
        $cls_list = $conn->query("SELECT * FROM classes WHERE application_id = $current_app");
        while ($c = $cls_list->fetch_assoc()):
        ?>
          <option value="<?= $c['id'] ?>" <?= ($current_class == $c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <?php endif; ?>
  </form>

  <?php if ($current_app && $current_class): ?>
  <form method="POST" class="row g-3 mb-4">
    <input type="hidden" name="application_id" value="<?= $current_app ?>">
    <input type="hidden" name="class_id" value="<?= $current_class ?>">
    <div class="col-md-4">
      <?php if ($semesters): ?>
      <select name="semester_id" class="form-select" required>
        <option value="">اختر الفصل</option>
        <?php while ($s = $semesters->fetch_assoc()): ?>
          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <?php endif; ?>
    </div>
    <div class="col-md-4">
      <input type="text" name="name" class="form-control" placeholder="اسم القسم" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success w-100">➕ إضافة</button>
    </div>
  </form>

  <!-- عرض الأقسام -->
  <h5 class="mb-3">📋 الأقسام الدراسية:</h5>
  <table class="table table-bordered bg-white">
    <thead class="table-light">
      <tr><th>#</th><th>القسم</th><th>الفصل</th><th>تحكم</th></tr>
    </thead>
    <tbody>
      <?php while($row = $sections->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td>
            <form method="POST" class="d-flex">
              <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="application_id" value="<?= $current_app ?>">
              <input type="hidden" name="class_id" value="<?= $current_class ?>">
              <input type="text" name="edit_name" class="form-control me-2" value="<?= htmlspecialchars($row['name']) ?>">
              <button type="submit" class="btn btn-sm btn-primary">✏️</button>
            </form>
          </td>
          <td><?= htmlspecialchars($row['semester_name']) ?></td>
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
          <h5 class="modal-title">تأكيد الحذف</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body text-center">⚠️ هل تريد حذف هذا القسم؟</div>
        <div class="modal-footer">
          <form method="GET">
            <input type="hidden" name="application_id" value="<?= $current_app ?>">
            <input type="hidden" name="class_id" value="<?= $current_class ?>">
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
