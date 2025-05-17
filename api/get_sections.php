<?php
// FILE: api/get_sections.php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_GET['semester_id'])) {
  echo json_encode([]);
  exit;
}

$semester_id = intval($_GET['semester_id']);
$stmt = $conn->prepare("SELECT id, name FROM sections WHERE semester_id = ?");
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
?>