
<?php
// FILE: /httpdocs/edu.1/public/fetch_sections.php
require_once '../admin/core/db.php';

$semester_id = isset($_GET['semester_id']) ? intval($_GET['semester_id']) : 0;
if (!$semester_id) die();

$sections = $conn->query("SELECT id, name FROM sections WHERE semester_id = $semester_id ORDER BY id ASC");

if ($sections && $sections->num_rows > 0) {
  while($s = $sections->fetch_assoc()) {
    echo '<div class="section-pill" onclick="loadGroups(' . $s['id'] . ')">' . htmlspecialchars($s['name']) . '</div>';
    echo '<div id="groups-' . $s['id'] . '" class="group-row d-none" style="margin-right: 20px; margin-top: 10px;"></div>';
  }
} else {
  echo '<p class="text-muted">🚫 لا توجد أقسام مرتبطة بهذا الفصل.</p>';
}
?>

<script>
function loadGroups(sectionId) {
  $('.group-row').not('#groups-' + sectionId).slideUp().addClass('d-none');
  let target = $('#groups-' + sectionId);
  if (!target.hasClass('loaded')) {
    $.get('fetch_groups.php', { section_id: sectionId }, function(data) {
      target.html(data).removeClass('d-none loaded').slideDown();
    });
  } else {
    target.toggleClass('d-none').slideToggle();
  }
}
</script>
