<?php
// FILE: admin/views/save_lesson.php
require_once '../../config/db.php';

function sanitize($value) {
  return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$lesson_name = sanitize($_POST['lesson_name'] ?? '');
$slug = sanitize($_POST['slug'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$application_id = intval($_POST['application_id'] ?? 0);
$class_id = intval($_POST['class_id'] ?? 0);
$material_id = intval($_POST['material_id'] ?? 0);
$semester_id = intval($_POST['semester_id'] ?? 0);
$section_id = intval($_POST['section_id'] ?? 0);
$group_id = intval($_POST['group_id'] ?? 0);
$type = $_POST['attachment_type'] ?? '';
$external_url = sanitize($_POST['external_url'] ?? '');

if ($lesson_name && $group_id) {
  $stmt = $conn->prepare("INSERT INTO lessons (group_id, name, slug, description) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $group_id, $lesson_name, $slug, $description);
  $stmt->execute();
  $lesson_id = $stmt->insert_id;
  $stmt->close();

  // Handle attachment
  if ($type === 'url' && !empty($external_url)) {
    $conn->query("INSERT INTO attachments (lesson_id, type, url, title) VALUES ($lesson_id, '$type', '$external_url', '$lesson_name')");
  } elseif (!empty($_FILES['file']['name'])) {
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $folder = strtolower($type);
    $date = date('Y-m');
    $uploadDir = "../../views/uploads/lessons/$date/$folder/";
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $newName = uniqid() . '_' . basename($_FILES['file']['name']);
    $targetFile = $uploadDir . $newName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
      $relativePath = "/admin/views/uploads/lessons/$date/$folder/$newName";
      $conn->query("INSERT INTO attachments (lesson_id, type, url, title) VALUES ($lesson_id, '$type', '$relativePath', '$lesson_name')");
    }
  }
}

header("Location: add_lessons.php?saved=1");
exit;
?>
