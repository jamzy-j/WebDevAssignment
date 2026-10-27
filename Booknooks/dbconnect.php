<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny

$host = 'localhost';
$db   = 'libby_db';
$user = 'root';
$pass = '';


$dsn = "mysql:host=$host"; 

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);$pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`;");
    $pdo->exec("USE `$db`;");

    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        userID INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(45) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'user'
    );

    CREATE TABLE IF NOT EXISTS books (
        bookID INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        author VARCHAR(100) NOT NULL,
        isbn VARCHAR(13) UNIQUE,
        genre VARCHAR(50),
        published_year INT,
        cover_page VARCHAR(255) DEFAULT NULL,
        description TEXT DEFAULT NULL
    );

    CREATE TABLE IF NOT EXISTS reviews (
        reviewID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT,
        bookID INT,
        rating INT CHECK (rating >= 1 AND rating <= 5),
        review_text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
        FOREIGN KEY (bookID) REFERENCES books(bookID) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS reading_lists (
        listID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT,
        bookID INT,
        status ENUM('Want to Read', 'Currently Reading', 'Read') NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user_book (userID, bookID),
        FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
        FOREIGN KEY (bookID) REFERENCES books(bookID) ON DELETE CASCADE
    );
    ";

    $pdo->exec($sql);

} catch (PDOException $e) {
    echo "Connection/SQL Error: " . $e->getMessage();
    exit();
}
?>
