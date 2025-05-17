<?php
require_once '../../config/db.php';
$semester_id = (int)($_GET['semester_id'] ?? 0);
$q = $conn->query("SELECT id, name FROM sections WHERE semester_id = $semester_id ORDER BY id DESC");
echo '<option value="">اختر القسم</option>';
while ($row = $q->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>