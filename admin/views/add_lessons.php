<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// FILE: admin/views/add_lessons.php
require_once '../../config/db.php';
require_once '../sidebar.php';
ini_set('display_errors', 1); error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📘 إضافة درس مع مرفق</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f9f9f9; }
    .main { margin-right: 270px; padding: 30px; }
    label { font-weight: bold; }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="main">
  <h3 class="text-success mb-4">📘 إضافة درس جديد مع مرفق</h3>

  <form method="POST" enctype="multipart/form-data" class="p-4 bg-white shadow rounded">
    <div class="row g-3">
      <div class="col-md-6">
        <label>اختر التطبيق</label>
        <select id="application_id" class="form-select" required>
          <option value="">-- اختر --</option>
          <?php
          $apps = $conn->query("SELECT * FROM applications");
          while ($app = $apps->fetch_assoc()) {
              echo "<option value='{$app['id']}'>{$app['name']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-6">
        <label>اختر الصف</label>
        <select id="class_id" class="form-select" required></select>
      </div>

      <div class="col-md-6">
        <label>اختر المادة</label>
        <select id="material_id" class="form-select" required></select>
      </div>

      <div class="col-md-6">
        <label>اختر الفصل الدراسي</label>
        <select id="semester_id" class="form-select" required></select>
      </div>

      <div class="col-md-6">
        <label>اختر القسم</label>
        <select id="section_id" class="form-select" required></select>
      </div>

      <div class="col-md-6">
        <label>اختر المجموعة</label>
        <select id="group_id" name="group_id" class="form-select" required></select>
      </div>

      <div class="col-md-6">
        <label>اسم الدرس</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label>الرابط النصي (slug)</label>
        <input type="text" name="slug" class="form-control">
      </div>

      <div class="col-md-12">
        <label>وصف مختصر</label>
        <textarea name="description" class="form-control" rows="2"></textarea>
      </div>

      <div class="col-md-6">
        <label>نوع المرفق</label>
        <select name="attachment_type" class="form-select">
          <option value="pdf">PDF</option>
          <option value="image">صورة</option>
          <option value="video">فيديو</option>
          <option value="doc">مستند</option>
        </select>
      </div>

      <div class="col-md-6">
        <label>رابط خارجي (إن وجد)</label>
        <input type="text" name="attachment_link" class="form-control">
      </div>

      <div class="col-md-12">
        <label>أو اختر ملف</label>
        <input type="file" name="attachment_file" class="form-control">
      </div>

      <div class="col-md-12 text-end">
        <button type="submit" class="btn btn-success">💾 حفظ الدرس والمرفق</button>
      </div>
    </div>
  </form>
</div>

<script>
$(document).ready(function() {
  $('#application_id').change(function() {
    let appId = $(this).val();
    $('#class_id').html('<option>جارٍ التحميل...</option>');
    $.get('../../public/api/get_classes.php', { app_id: appId }, function(data) {
      $('#class_id').html(data);
    });
  });

  $('#class_id').change(function() {
    let classId = $(this).val();
    $('#material_id').html('<option>جارٍ التحميل...</option>');
    $.get('../../public/api/get_materials.php', { class_id: classId }, function(data) {
      $('#material_id').html(data);
    });
  });

  $('#material_id').change(function() {
    let matId = $(this).val();
    $('#semester_id').html('<option>جارٍ التحميل...</option>');
    $.get('../../public/api/get_semesters.php', { material_id: matId }, function(data) {
      $('#semester_id').html(data);
    });
  });

  $('#semester_id').change(function() {
    let semId = $(this).val();
    $('#section_id').html('<option>جارٍ التحميل...</option>');
    $.get('../../public/api/get_sections.php', { semester_id: semId }, function(data) {
      $('#section_id').html(data);
    });
  });

  $('#section_id').change(function() {
    let secId = $(this).val();
    $('#group_id').html('<option>جارٍ التحميل...</option>');
    $.get('../../public/api/get_groups.php', { section_id: secId }, function(data) {
      $('#group_id').html(data);
    });
  });
});
</script>

</body>
</html>