<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'dbconnect.php';
include 'header.php';

if (!isset($_SESSION['userID']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM books WHERE bookID = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: admin_dashboard.php");
exit();
?>
