<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
session_start();
require_once 'dbconnect.php';
include 'header.php';

if (!isset($_SESSION['userID']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$bookID = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE bookID = :id");
$stmt->execute([':id' => $bookID]);
$book = $stmt->fetch();

if (!$book) {
    die("Selected record was not found");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $sql = "UPDATE books SET title = :title, author = :author, isbn = :isbn, genre = :genre, published_year = :year
                WHERE bookID = :id";

        $stmt_up = $pdo->prepare($sql);
        $stmt_up->execute([
            ':title' => $_POST['title'],
            ':author' => $_POST['author'],
            ':isbn' => $_POST['isbn'],
            ':genre' => $_POST['genre'],
            ':year' => (int)$_POST['published_year'],
            ':id' => $bookID
        ]);

        header("Location: admin_dashboard.php");
        exit();

    } catch (PDOException $e) {
        $error = "Update Failed: " . $e->getMessage();
    }
}
?>

<div class="form-card form-card-medium">
    <h2>Edit Book Entry #<?= $book['bookID'] ?></h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="edit_book.php?id=<?= $book['bookID'] ?>" method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
        </div>

        <div class="form-group">
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($book['genre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="published_year">Published Year:</label>
            <input type="number" id="published_year" name="published_year" value="<?= $book['published_year'] ?>" required>
        </div>

        <button type="submit" class="btn">Update Book Listing</button>
    </form>
</div>

<?php include 'footer.php'; ?>
