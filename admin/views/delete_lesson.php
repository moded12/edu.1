<?php
require_once '../core/db.php';

$id = intval($_GET['id'] ?? 0);

if (!isset($_GET['confirm'])) {
  echo "<script>
    if (confirm('⚠️ هل أنت متأكد أنك تريد حذف هذا الدرس؟')) {
      window.location = 'delete_lesson.php?id=$id&confirm=1';
    } else {
      window.location = 'view_lessons.php';
    }
  </script>";
  exit;
}

$conn->query("DELETE FROM lesson_files WHERE lesson_id = $id");
$conn->query("DELETE FROM lessons WHERE id = $id");

echo "<script>alert('✅ تم حذف الدرس بنجاح'); window.location='view_lessons.php';</script>";
?>
