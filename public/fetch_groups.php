
<?php
// FILE: /httpdocs/edu.1/public/fetch_groups.php
require_once '../admin/core/db.php';

$section_id = isset($_GET['section_id']) ? intval($_GET['section_id']) : 0;
if (!$section_id) die();

$groups = $conn->query("SELECT id, name FROM edu_groups WHERE section_id = $section_id ORDER BY id ASC");

if ($groups && $groups->num_rows > 0) {
  while($g = $groups->fetch_assoc()) {
    echo '<div class="group-pill" onclick="location.href=\'lessons.php?section_id=' . $section_id . '&group_id=' . $g['id'] . '\'">';
    echo htmlspecialchars($g['name']);
    echo '</div>';
  }
} else {
  echo '<p class="text-muted">🚫 لا توجد مجموعات مرتبطة بهذا القسم.</p>';
}
?>
