<?php
// FILE: admin/views/add_lessons.php
require_once '../core/db.php';
$apps = $conn->query("SELECT * FROM applications ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>➕ إضافة درس</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { font-family: 'Cairo', sans-serif; margin: 0; background: #f2f2f2; }
    .main-content { padding: 30px 20px; margin-right: 260px; }
    @media (max-width: 768px) {
      .main-content { margin-right: 0; padding: 20px; }
    }
    .form-box {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 5px #ccc;
    }
    .form-label { font-weight: bold; }
  </style>
</head>
<body>

<?php include('../includes/sidebar.php'); ?>

<div class="main-content">
  <h4 class="text-success mb-4">➕ إضافة درس جديد</h4>

  <div class="form-box">
    <form method="POST" enctype="multipart/form-data" action="save_lesson.php">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">📱 التطبيق</label>
          <select name="app_id" id="app_id" class="form-select" required>
            <option value="">-- اختر --</option>
            <?php while($a = $apps->fetch_assoc()): ?>
              <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">🏫 الصف</label>
          <select name="class_id" id="class_id" class="form-select" required></select>
        </div>

        <div class="col-md-2">
          <label class="form-label">📘 المادة</label>
          <select name="material_id" id="material_id" class="form-select" required></select>
        </div>

        <div class="col-md-2">
          <label class="form-label">📅 الفصل</label>
          <select name="semester_id" id="semester_id" class="form-select" required></select>
        </div>

        <div class="col-md-2">
          <label class="form-label">📂 القسم</label>
          <select name="section_id" id="section_id" class="form-select" required></select>
        </div>

        <div class="col-md-2">
          <label class="form-label">👥 المجموعة</label>
          <select name="group_id" id="group_id" class="form-select" required></select>
        </div>

        <div class="col-md-6">
          <label class="form-label">📄 اسم الدرس</label>
          <input type="text" name="lesson_name" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">🌐 رابط خارجي (اختياري)</label>
          <input type="url" name="external_link" class="form-control">
        </div>

        <div class="col-md-12">
          <label class="form-label">📎 رفع مرفق</label>
<input type="file" name="attachment[]" class="form-control" multiple>
        </div>

        <div class="col-md-12 text-end">
          <button class="btn btn-success px-5">💾 حفظ الدرس</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function loadDropdown(id, target, action) {
  $.post('load_dropdown.php', { id: id, action: action }, function(data) {
    $('#' + target).html(data);
  });
}

$('#app_id').on('change', function() {
  const appId = this.value;
  $('#material_id, #semester_id, #section_id, #group_id').html('');
  loadDropdown(appId, 'class_id', 'classes');

  // انتظر قليلاً بعد تحميل الصفوف، ثم اختر الصف الأول تلقائيًا (اختياري)
  setTimeout(function () {
    const firstClass = $('#class_id option:eq(1)').val(); // أول صف بعد "-- اختر --"
    if (firstClass) {
      $('#class_id').val(firstClass).trigger('change');
    }
  }, 300); // تأخير بسيط للسماح بتحميل الخيارات
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
</script>

</body>
</html>