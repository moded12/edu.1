<?php
require_once '../../config/db.php';
$class_id = (int)($_GET['class_id'] ?? 0);
$q = $conn->query("SELECT id, name FROM materials WHERE class_id = $class_id ORDER BY id DESC");
echo '<option value="">اختر المادة</option>';
while ($row = $q->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>