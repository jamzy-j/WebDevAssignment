<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['userID'])) {
    die("unauthorized request. Please log in first.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['user_website'])) {
        die("Spam bot is detected.");
    }

    $bookID = (int)($_POST['bookID'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $review_text = trim($_POST['review_text'] ?? '');
    $userID = $_SESSION['userID'];

    if ($bookID <= 0 || $rating < 1 || $rating > 5 || strlen($review_text) < 5) {
        die("Invalid submission data");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (userID, bookID, rating, review_text) 
        VALUES (:userID, :bookID, :rating, :review_text)");

        $stmt->execute([
            ':userID' => $userID,
            ':bookID' => $bookID,
            ':rating' => $rating,
            ':review_text' => $review_text
        ]);

        header("Location: book_details.php?id=" . $bookID);
        exit();

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}
?>