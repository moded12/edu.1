<?php
// FILE: api/get_semesters.php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_GET['material_id'])) {
  echo json_encode([]);
  exit;
}

$material_id = intval($_GET['material_id']);
$stmt = $conn->prepare("SELECT id, name FROM semesters WHERE material_id = ?");
$stmt->bind_param("i", $material_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
?>