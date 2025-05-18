<?php
// FILE: admin/views/save_lesson.php
require_once '../core/db.php';

// استقبال البيانات
$class_id     = $_POST['class_id'] ?? null;
$material_id  = $_POST['material_id'] ?? null;
$semester_id  = $_POST['semester_id'] ?? null;
$section_id   = $_POST['section_id'] ?? null;
$group_id     = $_POST['group_id'] ?? null;
$name         = $_POST['lesson_name'] ?? '';
$link         = $_POST['external_link'] ?? '';

if (!$class_id || !$material_id || !$semester_id || !$section_id || !$group_id || !$name) {
    die('❌ بيانات ناقصة');
}

// إدخال الدرس
$stmt = $conn->prepare("INSERT INTO lessons (class_id, material_id, semester_id, section_id, group_id, name, external_link) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiiiiss", $class_id, $material_id, $semester_id, $section_id, $group_id, $name, $link);
$stmt->execute();
$lesson_id = $stmt->insert_id;

// تجهيز مجلد التخزين
$date_folder = date("Y-m");
$upload_base = "uploads/lessons/{$date_folder}";
$image_dir = "$upload_base/image";
$pdf_dir = "$upload_base/pdf";
@mkdir($image_dir, 0777, true);
@mkdir($pdf_dir, 0777, true);

// معالجة الملفات
foreach ($_FILES['attachment']['tmp_name'] as $key => $tmp_name) {
    if ($_FILES['attachment']['error'][$key] === 0) {
        $filename = uniqid() . '_' . basename($_FILES['attachment']['name'][$key]);
        $filetype = mime_content_type($tmp_name);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $target = (str_contains($filetype, 'pdf') ? $pdf_dir : $image_dir) . '/' . $filename;
        if (move_uploaded_file($tmp_name, $target)) {
            $type = str_contains($filetype, 'pdf') ? 'pdf' : 'image';
            $stmt2 = $conn->prepare("INSERT INTO lesson_files (lesson_id, file_path, type) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $lesson_id, $target, $type);
            $stmt2->execute();
        }
    }
}

header("Location: add_lessons.php?success=1");
exit;
?>