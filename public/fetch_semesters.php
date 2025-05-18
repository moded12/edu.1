
<?php
// FILE: /httpdocs/edu.1/public/fetch_semesters.php
require_once '../admin/core/db.php';

$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : 0;
if (!$material_id) die();

$semesters = $conn->query("SELECT id, name FROM semesters WHERE material_id = $material_id ORDER BY id ASC");

if ($semesters && $semesters->num_rows > 0) {
  while($s = $semesters->fetch_assoc()) {
    echo '<div class="semester-pill" onclick="loadSections(' . $s['id'] . ')">' . htmlspecialchars($s['name']) . '</div>';
    echo '<div id="sections-' . $s['id'] . '" class="section-row d-none" style="margin-right: 20px; margin-top: 10px;"></div>';
  }
} else {
  echo '<p class="text-muted">🚫 لا توجد فصول مرتبطة بهذه المادة.</p>';
}
?>

<script>
function loadSections(semesterId) {
  $('.section-row').not('#sections-' + semesterId).slideUp().addClass('d-none');
  let target = $('#sections-' + semesterId);
  if (!target.hasClass('loaded')) {
    $.get('fetch_sections.php', { semester_id: semesterId }, function(data) {
      target.html(data).removeClass('d-none loaded').slideDown();
    });
  } else {
    target.toggleClass('d-none').slideToggle();
  }
}
</script>
