<?php 
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny

require_once 'dbconnect.php';
include 'header.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

//Fetch user books from database
$stmt = $pdo->prepare("SELECT b.bookID, b.title, b.author, b.genre, b.published_year, rl.status, rl.updated_at, IFNULL(ROUND(AVG(r.rating), 1), 'N/A') AS avg_rating
FROM reading_lists rl
JOIN books b ON rl.bookID = b.bookID 
LEFT JOIN reviews r ON b.bookID = r.bookID
WHERE rl.userID = :userID
GROUP BY b.bookID, rl.status, rl.updated_at
ORDER BY rl.updated_at DESC
");

$stmt->execute([':userID' => $userID]);
$my_books = $stmt->fetchAll();

$reading_books = array_filter($my_books, fn($b) => $b['status'] === 'Currently Reading');
$plan_books = array_filter($my_books, fn($b) => $b['status'] === 'Want to Read');
$completed_books = array_filter($my_books, fn($b) => $b['status'] === 'Read');
?>

<h2>&nbsp;My List</h2>

<main class="mainlistinterface">

<div class="action-header-bar">
    <button type="button" class= "add-book-btn" 
    onclick="window.location.href='index.php'">
    + Add New Book
</button>
</div>

<div class="tabs-navigation">
    <button class="tab-btn active"
    onclick="switchTab('reading')">Currently Reading</button>

    <button class="tab-btn"
    onclick="switchTab('plan2read')">Want To Read</button>

    <button class="tab-btn"
    onclick="switchTab('completed')">Read</button>
</div>


<div class="list-summary">
    <span id="title-count"><?= count($reading_books) ?> Titles</span>
</div>


<div class="tabs-content-wrapper">

    <!--Current Reading Tab-->
    <div id="reading" class="tab-panel active">
        <?php if ($reading_books): ?> 
            <div class="book-card-grid" style="gap: 16px; display: flex; flex-direction: column;">

                <?php foreach ($reading_books as $b): ?>
                    <div class="book-card">
                        <div class="book-details">
                            <div class="book-header">
                                <h3 class="book-title"><?= htmlspecialchars($b['title']) ?></h3>
                                
                                <div class="book-meta">
                                    <span class="rating">★ <?= $b['avg_rating'] ?></span>
                                </div>
                            </div>

                            <p class="book-description">
                                By <strong><?= htmlspecialchars($b['author']) ?></strong> |
                                Genre: <?= htmlspecialchars($b['genre'] ?? 'N/A') ?> |
                                Year: <?= $b['published_year'] ?>
                            </p> 

                            <div style="margin-top: 10px;">
                                <a href="book_details.php?id=<?= $b['bookID'] ?>" class="btn-edit">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p class="empty-tab-msg">No book currently being read.</p>
        <?php endif; ?>
    </div>


    <!--Want to Read tab-->
    <div id="plan2read" class="tab-panel">
        <?php if ($plan_books): ?>
            <div class="book-card-grid" style="gap: 16px; display: flex; flex-direction: column;">
                <?php foreach ($plan_books as $b): ?>
                    <div class= "book-card">
                        <div class="book-details">
                            <div class="book-header"> 
                                <h3 class="book-title"><?= htmlspecialchars($b['title']) ?></h3>

                                <div class="book-meta">
                                <span class="rating">★ <?= $b['avg_rating'] ?></span>
                                <span class="status-badge ongoing">Want to Read</span>
                                </div>
                            </div>

                            <p class="book-description">
                                By <strong><?= htmlspecialchars($b['author']) ?></strong> |
                                Genre: <?= htmlspecialchars($b['genre'] ?? 'N/A') ?> |
                                Year: <?= $b['published_year'] ?>
                            </p>

                            <div style="margin-top: 10px;">
                                <a href="book_details.php?id=<?= $b['bookID'] ?>" class="btn-edit">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p class="empty-tab-msg">No book in Want to Read.</p>
        <?php endif; ?>
    </div>

    <!--Read-->
    <div id="completed" class="tab-panel">
        <?php if ($completed_books): ?>
            <div class="book-card-grid" style="gap: 16px; display: flex; flex-direction: column;">

                <?php foreach ($completed_books as $b): ?>
                    <div class="book-card">
                        <div class="book-details">
                            <div class="book-header">
                                <h3 class="book-title"><?= htmlspecialchars($b['title']) ?></h3>

                                <div class="book-meta">
                                    <span class="rating">★ <?= $b['avg_rating'] ?></span>
                                    <span class="status-badge ongoing" style="color:#2ecc71; border-color: rgba(46, 204, 113, 0.2);">Completed</span>
                                </div>
                            </div>

                            <p class="book-description">
                                By <strong><?= htmlspecialchars($b['author']) ?></strong> |
                                Genre: <?= htmlspecialchars($b['genre'] ?? 'N/A') ?> |
                                Year: <?= $b['published_year'] ?>
                            </p>

                            <div style="margin-top: 10px;">
                                <a href="book_details.php?id=<?= $b['bookID'] ?>" class="btn-edit">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p class="empty-tab-msg">No completed books yet.</p>
        <?php endif; ?>
    </div>
</div>
</main>

<?php include 'footer.php'; ?>