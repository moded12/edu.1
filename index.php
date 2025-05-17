<?php
// FILE: index.php
// ROOT: /edu.1/
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>مرحبًا بك في المنصة التعليمية</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f0f4f8;
      text-align: center;
      padding: 50px;
    }
    h1 {
      margin-bottom: 40px;
      color: #333;
    }
    .card-option {
      transition: transform 0.3s ease;
      cursor: pointer;
    }
    .card-option:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .icon {
      font-size: 50px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <h1>👋 مرحبًا بك في المنصة التعليمية</h1>

  <div class="container">
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <a href="admin/views/add_application.php" class="text-decoration-none text-dark">
          <div class="card card-option p-4">
            <div class="icon">🛠️</div>
            <h5>لوحة الإدارة</h5>
            <p class="text-muted">الدخول إلى لوحة تحكم المشرف</p>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="frontend/index.php" class="text-decoration-none text-dark">
          <div class="card card-option p-4">
            <div class="icon">🎓</div>
            <h5>الواجهة التعليمية</h5>
            <p class="text-muted">عرض المواد والدروس للطلاب</p>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="#" class="text-decoration-none text-dark">
          <div class="card card-option p-4">
            <div class="icon">📊</div>
            <h5>تقارير وإحصائيات</h5>
            <p class="text-muted">قسم قابل للتوسعة لاحقًا</p>
          </div>
        </a>
      </div>
    </div>
  </div>

</body>
</html>
