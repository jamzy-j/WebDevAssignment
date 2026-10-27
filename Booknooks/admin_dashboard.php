<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny

require_once 'dbconnect.php';
include 'header.php';

if (!isset($_SESSION['userID']) ||
strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php?error=unauthorized");
    exit();
} 

//Fetch catalog for CRUD
$books = $pdo->query("SELECT * FROM books ORDER BY bookID DESC")->fetchAll();

$report_stmt = $pdo->query(" SELECT b.genre, 
COUNT(DISTINCT b.bookID) AS total_books,
COUNT(r.reviewID) AS total_reviews,
IFNULL(ROUND(AVG(r.rating), 2), 'N/A') AS avg_genre_rating
FROM books b
LEFT JOIN reviews r ON b.bookID = r.bookID
GROUP BY b.genre
ORDER BY total_books DESC
");
$reports = $report_stmt->fetchAll();
?>

<h2>&nbsp;Admin Panel</h2>


<main class="mainlistinterface">
    <div class="action-header-bar">
        <button type="button" class="add-book-btn"
        onclick="window.location.href='add_book.php'">
        + Add New Book Listing</button>
    </div>

    <h3 style="color:#feceab; margin-bottom: 12px;">1. Book Catalog Management</h3>
    <table class="data-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Genre</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($books as $b): ?>
                <tr>
                    <td><?= $b['bookID'] ?></td>
                    <td><strong><?= htmlspecialchars($b['title']) ?></strong></td>
                    <td><?= htmlspecialchars($b['author']) ?></td>
                    <td><?= htmlspecialchars($b['isbn'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($b['genre'] ?? 'N/A') ?></td>
                    <td><?= $b['published_year'] ?></td>

                    <td>
                        <a href="edit_book.php?id=<?= $b['bookID'] ?>" class="btn-edit">Edit</a>
                        <a href="delete_book.php?id=<?= $b['bookID'] ?>" class="btn-delete"
                        onclick="return confirm('Are you sure you want to delete this book listings?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <h3 style="color:#feceab; margin-top: 30px; margin-bottom: 12px;">2. Aggregated Library Genre Report</h3>
    <table class="data-table">

        <thead>
            <tr>
                <th>Genre</th>
                <th>Total Books Listed</th>
                <th>Total Reviews Submitted</th>
                <th>Average Rating</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($reports as $rep): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($rep['genre'] ?? 'Uncategorized') ?></strong></td>
                    <td><?= $rep['total_books'] ?></td>
                    <td><?= $rep['total_reviews'] ?></td>
                    <td>★ <?= $rep['avg_genre_rating'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'footer.php'; ?>