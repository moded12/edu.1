<?php
// FILE: admin/core/db.php

$host = "localhost";
$user = "edu.1";
$pass = "Tvvcrtv1610@";
$dbname = "edu.1";

$conn = new mysqli($host, $user, $pass, $dbname);

// ______ __ _______
if ($conn->connect_error) {
    die("_ ___ _______: " . $conn->connect_error);
}

// ____ __ _______
mysqli_set_charset($conn, "utf8");
?>