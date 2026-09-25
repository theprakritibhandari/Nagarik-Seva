<?php

$host = getenv("DB_HOST");
$port = getenv("DB_PORT");
$username = getenv("DB_USER");
$password = getenv("DB_PASSWORD");
$database = getenv("DB_NAME");

$conn = mysqli_init();

mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect(
    $conn,
    $host,
    $username,
    $password,
    $database,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
)) {
    die("Database connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");

?>