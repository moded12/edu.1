<?php
// FILE: api/get_classes.php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_GET['application_id'])) {
  echo json_encode([]);
  exit;
}

$app_id = intval($_GET['application_id']);
$stmt = $conn->prepare("SELECT id, name FROM classes WHERE application_id = ?");
$stmt->bind_param("i", $app_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
?>