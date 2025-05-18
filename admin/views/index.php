<!-- FILE: /admin/views/index.php -->
<?php include('../includes/sidebar.php'); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>📘 لوحة التحكم</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif !important;
      background: #f9f9f9;
      margin: 0;
    }
    .main-content {
      padding: 40px 20px;
      margin-right: 260px;
    }
    @media (max-width: 768px) {
      .main-content {
        margin-right: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="main-content">
    <h2 class="text-success">📘 مرحبًا بك في لوحة التحكم</h2>
    <p>اختر من القائمة الجانبية للبدء بإدارة المحتوى.</p>
  </div>
</body>
</html>