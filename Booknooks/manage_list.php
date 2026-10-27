<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'dbconnect.php';
include 'header.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bookID = (int)($_POST['bookID'] ?? 0);
    $status = $_POST['status'] ?? 'Want to Read';

    $allowed_statuses = ['Want to Read', 'Currently Reading', 'Read'];
    if ($bookID > 0 && in_array($status, $allowed_statuses)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO reading_lists (userID, bookID, status)
            VALUES (:userID, :bookID, :status)
            ON DUPLICATE KEY UPDATE status = :status_dup");

            $stmt->execute([
                ':userID' => $userID,
                ':bookID' => $bookID,
                ':status' => $status,
                ':status_dup' => $status
            ]);
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
    header("Location: book_details.php?id=" . $bookID);
    exit();
}
?>
