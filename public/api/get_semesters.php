<?php
require_once '../../config/db.php';
$material_id = (int)($_GET['material_id'] ?? 0);
$q = $conn->query("SELECT id, name FROM semesters WHERE material_id = $material_id ORDER BY id DESC");
echo '<option value="">اختر الفصل الدراسي</option>';
while ($row = $q->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>