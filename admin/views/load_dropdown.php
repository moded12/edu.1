<?php
// FILE: admin/views/load_dropdown.php
require_once '../core/db.php';

$action = $_POST['action'] ?? '';
$id = intval($_POST['id']);

if (!$action || !$id) {
  exit;
}

switch ($action) {
  case 'classes':
    $rows = $conn->query("SELECT id, name FROM classes WHERE application_id = $id");
    break;
  case 'materials':
    $rows = $conn->query("SELECT id, name FROM materials WHERE class_id = $id");
    break;
  case 'semesters':
    $rows = $conn->query("SELECT id, name FROM semesters WHERE material_id = $id");
    break;
  case 'sections':
    $rows = $conn->query("SELECT id, name FROM sections WHERE semester_id = $id");
    break;
  case 'groups':
    $rows = $conn->query("SELECT id, name FROM edu_groups WHERE section_id = $id");
    break;
  default:
    $rows = [];
}

if ($rows && $rows instanceof mysqli_result) {
  while ($r = $rows->fetch_assoc()) {
    echo '<option value="' . $r['id'] . '">' . htmlspecialchars($r['name']) . '</option>';
  }
}
?>
