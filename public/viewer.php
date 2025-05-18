<?php
// FILE: /edu.1/public/viewer.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../admin/core/db.php';

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

if ($lesson_id <= 0) {
    die('معرف الدرس غير صالح.');
}

$sql = "SELECT file_path FROM lesson_files WHERE lesson_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row['file_path'];
}

$stmt->close();
$conn->close();

function fullUrl($filePath) {
    if (filter_var($filePath, FILTER_VALIDATE_URL)) {
        return $filePath;
    } else {
        return "https://shneler.com/edu.1/admin/views/" . ltrim($filePath, '/');
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>📄 محتوى الدرس</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        img, iframe, video {
            width: 100%;
            max-height: 90vh;
            object-fit: contain;
            margin-bottom: 20px;
        }
        .btn-back {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .container {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-success mb-4">📄 محتوى الدرس</h3>

    <?php if (empty($files)) : ?>
        <div class='alert alert-info'>لا يوجد مرفقات لهذا الدرس.</div>
    <?php endif; ?>

    <?php foreach ($files as $file): ?>
        <?php
        $url = fullUrl($file);
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            echo "<img src='$url' alt='Lesson Image'>";
        } elseif ($ext === 'pdf') {
            echo "<iframe src='$url' height='600px' style='border:1px solid #ccc;'></iframe>";
        } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) {
            echo "<video controls><source src='$url' type='video/$ext'>المتصفح لا يدعم تشغيل الفيديو.</video>";
        } elseif (in_array($ext, ['doc', 'docx'])) {
            echo "<div class='mb-4'><a href='https://docs.google.com/viewer?url=" . urlencode($url) . "' target='_blank' class='btn btn-outline-primary w-100'>📄 عرض ملف Word</a></div>";
        } elseif (filter_var($file, FILTER_VALIDATE_URL)) {
            echo "<div class='mb-4'><a href='$url' target='_blank' class='btn btn-success w-100'>🌐 فتح الرابط الخارجي</a></div>";
        } else {
            echo "<div class='alert alert-warning'>نوع الملف غير مدعوم: $file</div>";
        }
        ?>
    <?php endforeach; ?>
</div>

<a href="index.php" class="btn btn-dark btn-back">🔙 العودة</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
