<?php
require_once '../../config/db.php';
$app_id = (int)($_GET['app_id'] ?? 0);
$q = $conn->query("SELECT id, name FROM classes WHERE application_id = $app_id ORDER BY id DESC");
echo '<option value="">اختر الصف</option>';
while ($row = $q->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>