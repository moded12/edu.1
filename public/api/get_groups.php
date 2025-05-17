<?php
require_once '../../config/db.php';
$section_id = (int)($_GET['section_id'] ?? 0);
$q = $conn->query("SELECT id, name FROM edu_groups WHERE section_id = $section_id ORDER BY id DESC");
echo '<option value="">اختر المجموعة</option>';
while ($row = $q->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>