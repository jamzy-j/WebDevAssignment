<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
require_once 'dbconnect.php';
include 'header.php';

if (!isset($_SESSION['userID']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = (int)($_POST['published_year'] ?? 0);

    if (empty($title) || empty($author) || $year <= 0) {
        $error = "Title, Author, and Published Year are required.";
    } else {
        try {
            $sql = "INSERT INTO books (title, author, isbn, genre, published_year)
                    VALUES (:title, :author, :isbn, :genre, :year)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':isbn' => !empty($isbn) ? $isbn : null,
                ':genre' => !empty($genre) ? $genre : null,
                ':year' => $year
            ]);

            header("Location: admin_dashboard.php");
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>


    <h2>&nbsp;Add New Book Listing</h2>

    <main class="form-interface">
        <div class="auth-container">

    <?php if (!empty($error)): ?>
        <div class="alert alert-error" style="color: #ff4d4d; margin-bottom: 15px; font-weight: bold;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="add_book.php" method="POST">
        <div class="form-group">
            <label for="title" class="field-label">Book Title</label>
            <input type="text" id="title" name="title" placeholder="Enter book title" required class="text-input" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="author" class="field-label">Author</label>
            <input type="text" id="author" name="author" placeholder="Enter author name" required class="text-input" value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="isbn" class="field-label">ISBN:</label>
            <input type="text" id="isbn" name="isbn" placeholder="e.g. 978-3-16-148410-0" class="text-input" value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="genre" class="field-label">Genre:</label>
            <input type="text" id="genre" name="genre" placeholder="e.g. Classic, Comedy, Sci-Fi" class="text-input" value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="published_year" class="field-label">Published Year: </label>
            <input type="number" id="published_year" name="published_year" placeholder="e.g. 2013" min="1000" max="<?= date('Y') ?>" required class="text-input" value="<?= htmlspecialchars($_POST['published_year'] ?? '')?>">
        </div>

        <div class="auth-action-row" style="margin-top: 20px;">
            <button type="submit" class="submit-btn">Save Book</button>
            <a href="admin_dashboard.php" class="btn" style="background-color: #555; text-decoration: none; padding: 10px 15px; border-radius: 4px; color: #ffffff; margin-left: 10px">Cancel</a>

        </div>
    </form>
    </div>
    </main>
<?php include 'footer.php'; ?>

