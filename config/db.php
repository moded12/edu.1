<?php
// FILE: config/db.php

$host = 'localhost';
$user = 'edu.1';
$pass = 'Tvvcrtv1610@';
$db   = 'edu.1';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
  die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>
