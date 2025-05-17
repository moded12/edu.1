<?php
// FILE: api/get_groups.php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_GET['section_id'])) {
  echo json_encode([]);
  exit;
}

$section_id = intval($_GET['section_id']);
$stmt = $conn->prepare("SELECT id, name FROM edu_groups WHERE section_id = ?");
$stmt->bind_param("i", $section_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
?>