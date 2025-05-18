<?php
// FILE: admin/includes/filter_form.php

if (!isset($conn)) {
  require_once '../core/db.php';
}

$appOptions = $conn->query("SELECT * FROM applications ORDER BY name");
$classOptions = isset($_GET['app_id']) ? $conn->query("SELECT * FROM classes WHERE application_id = ".intval($_GET['app_id'])) : [];
$materialOptions = isset($_GET['class_id']) ? $conn->query("SELECT * FROM materials WHERE class_id = ".intval($_GET['class_id'])) : [];
$semesterOptions = isset($_GET['material_id']) ? $conn->query("SELECT * FROM semesters WHERE material_id = ".intval($_GET['material_id'])) : [];
$sectionOptions = isset($_GET['semester_id']) ? $conn->query("SELECT * FROM sections WHERE semester_id = ".intval($_GET['semester_id'])) : [];
$groupOptions = isset($_GET['section_id']) ? $conn->query("SELECT * FROM edu_groups WHERE section_id = ".intval($_GET['section_id'])) : [];
?>

<form method="GET" class="row g-2 mb-4 bg-white p-3 rounded shadow-sm border">
  <div class="col-md-2">
    <select name="app_id" class="form-select" onchange="this.form.submit()">
      <option value="">📱 التطبيق</option>
      <?php while($row = $appOptions->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($_GET['app_id'] ?? '') == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-2">
    <select name="class_id" class="form-select" onchange="this.form.submit()">
      <option value="">🏫 الصف</option>
      <?php if($classOptions) while($row = $classOptions->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($_GET['class_id'] ?? '') == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-2">
    <select name="material_id" class="form-select" onchange="this.form.submit()">
      <option value="">📘 المادة</option>
      <?php if($materialOptions) while($row = $materialOptions->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($_GET['material_id'] ?? '') == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-2">
    <select name="semester_id" class="form-select" onchange="this.form.submit()">
      <option value="">📅 الفصل</option>
      <?php if($semesterOptions) while($row = $semesterOptions->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($_GET['semester_id'] ?? '') == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-2">
    <select name="section_id" class="form-select" onchange="this.form.submit()">
      <option value="">📂 القسم</option>
      <?php if($sectionOptions) while($row = $sectionOptions->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($_GET['section_id'] ?? '') == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-2">
    <select name="group_id" class="form-select" onchange="this.form.submit()">
      <option value="">👥 المجموعة</option>
      <?php if($groupOptions) while($row = $groupOptions->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= ($_GET['group_id'] ?? '') == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
  </div>
</form>