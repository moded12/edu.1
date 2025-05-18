<?php
// FILE: admin/views/view_attachments.php
require_once '../core/db.php';

$lesson_id = intval($_GET['lesson_id'] ?? 0);
if (!$lesson_id) die("❌ معرف الدرس غير صالح.");

$result = $conn->query("SELECT * FROM lesson_files WHERE lesson_id = $lesson_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📎 مرفقات الدرس</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f9f9f9; padding: 30px; }
    .attachment-card { border: 1px solid #ccc; padding: 15px; border-radius: 10px; background: white; margin-bottom: 15px; }
    .attachment-card a { font-weight: bold; text-decoration: none; }
  </style>
</head>
<body>
<div class="container">
  <h4 class="mb-4 text-primary">📎 قائمة مرفقات الدرس</h4>

  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="attachment-card">
        <p>النوع: <strong><?= $row['type'] ?></strong></p>
        <p>
          <a href="<?= $row['file_path'] ?>" target="_blank">🔗 فتح المرفق</a>
        </p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-warning">🚫 لا توجد مرفقات لهذا الدرس.</div>
  <?php endif; ?>
</div>
</body>
</html>