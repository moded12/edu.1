<?php
// FILE: api/get_materials.php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_GET['class_id'])) {
  echo json_encode([]);
  exit;
}

$class_id = intval($_GET['class_id']);
$stmt = $conn->prepare("SELECT id, name FROM materials WHERE class_id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
?>