<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// FILE: admin/views/add_lessons.php
require_once('../core/db.php');
require_once('../includes/header.php');
require_once('../includes/sidebar.php');

// جلب التطبيقات
$apps = $conn->query("SELECT * FROM applications");
?>
<div class="container mt-4">
  <h3 class="text-success mb-4">📘 إضافة درس جديد مع مرفق</h3>
  <?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">✅ تم حفظ الدرس والمرفق بنجاح!</div>
  <?php endif; ?>
  <form action="../actions/save_lesson.php" method="post" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-md-6">
        <label>اختر التطبيق:</label>
        <select name="application_id" id="application" class="form-select" required>
          <option value="">-- اختر --</option>
          <?php while($a = $apps->fetch_assoc()): ?>
            <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label>اختر الصف:</label>
        <select name="class_id" id="class" class="form-select" required></select>
      </div>
      <div class="col-md-6">
        <label>اختر المادة:</label>
        <select name="material_id" id="material" class="form-select" required></select>
      </div>
      <div class="col-md-6">
        <label>الفصل الدراسي:</label>
        <select name="semester_id" id="semester" class="form-select" required></select>
      </div>
      <div class="col-md-6">
        <label>القسم:</label>
        <select name="section_id" id="section" class="form-select" required></select>
      </div>
      <div class="col-md-6">
        <label>المجموعة:</label>
        <select name="group_id" id="group" class="form-select" required></select>
      </div>
      <div class="col-md-6">
        <label>اسم الدرس:</label>
        <input type="text" name="name" class="form-control" required />
      </div>
      <div class="col-md-6">
        <label>الرابط النصي (slug):</label>
        <input type="text" name="slug" class="form-control" required />
      </div>
      <div class="col-12">
        <label>وصف مختصر:</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <div class="col-md-6">
        <label>نوع المرفق:</label>
        <select name="file_type" class="form-select">
          <option value="pdf">PDF</option>
          <option value="image">صورة</option>
          <option value="video">فيديو</option>
          <option value="link">رابط خارجي</option>
        </select>
      </div>
      <div class="col-md-6">
        <label>رابط خارجي (إن وجد):</label>
        <input type="url" name="external_link" class="form-control" />
      </div>
      <div class="col-md-12">
        <label>أو اختر ملف:</label>
        <input type="file" name="file" class="form-control" />
      </div>
    </div>
    <button class="btn btn-success mt-3 w-100">💾 حفظ الدرس والمرفق</button>
  </form>
</div>
<script src="../../assets/jquery.min.js"></script>
<script>
  $('#application').change(function() {
    var app_id = $(this).val();
    $('#class').load("../../public/api/get_classes.php?app_id=" + app_id);
  });
  $('#class').change(function() {
    var cid = $(this).val();
    $('#material').load("../../public/api/get_materials.php?class_id=" + cid);
  });
  $('#material').change(function() {
    var mid = $(this).val();
    var cid = $('#class').val();
    $('#semester').load("../../public/api/get_semesters.php?class_id=" + cid + "&material_id=" + mid);
  });
  $('#semester').change(function() {
    var sid = $(this).val();
    var cid = $('#class').val();
    $('#section').load("../../public/api/get_sections.php?semester_id=" + sid + "&class_id=" + cid);
  });
  $('#section').change(function() {
    var sid = $(this).val();
    $('#group').load("../../public/api/get_groups.php?section_id=" + sid);
  });
</script>