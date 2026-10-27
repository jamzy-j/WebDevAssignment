<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
require_once 'dbconnect.php';
include 'header.php';

$book_id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT b.*, ROUND(AVG(r.rating), 1) AS avg_rating
    FROM books b
    LEFT JOIN reviews r ON b.bookID = r.bookID
    WHERE b.bookID = :id
    GROUP BY b.bookID");

$stmt->execute([':id' => $book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo "<p class='alert alert-error'>Selected book record was not found.</p>";
    include 'footer.php';
    exit();
}

$stmt_rev = $pdo->prepare(
    "SELECT r.*, u.username
    FROM reviews r
    JOIN users u ON r.userID = u.userID
    WHERE r.bookID = :id
    ORDER BY r.created_at DESC");

$stmt_rev->execute([':id' => $book_id]);
$reviews = $stmt_rev->fetchAll();
?>

<div class="detail-container">
    <img src="<?= htmlspecialchars($book['cover_page'] ?? 'default_cover.jpg') ?>" class="detail-cover" alt="Book Cover">

    <div class="detail-info">
        <h2><?= htmlspecialchars($book['title']) ?></h2>

        <p class="book-meta">
            Author: <strong><?= htmlspecialchars($book['author']) ?></strong> |
            Genre: <strong><?= htmlspecialchars($book['genre']) ?></strong> |
            Published: <strong><?= $book['published_year'] ?></strong> 
        </p>

        <div class="rating-badge">Average Rating: ★ <?= $book['avg_rating'] ? $book['avg_rating'] : 'No ratings yet' ?></div>
        <p class="book-description"><?= isset($book['description']) ? nl2br(htmlspecialchars($book['description'])) : 'No description available.' ?></p>
    </div>
</div>

<?php if (isset($_SESSION['userID'])) :
    $user_lists_stmt = $pdo->prepare("SELECT listID, status FROM reading_lists WHERE userID = :uid AND bookID = :bid");
    $user_lists_stmt->execute([':uid' => $_SESSION['userID'], ':bid' => $book_id]);
    $current_list_status = $user_lists_stmt->fetch(); ?>

    <div class="form-card form-card-wide">
        <h3>Save Book to Your Reading List</h3>

        <form action="manage_list.php" method="POST" class="inline-form">
            <input type="hidden" name="bookID" value="<?= $book['bookID'] ?>">

            <div class="form-group flex-grow">
                <label for="status">Reading Status</label>
                <select id="status" name="status" required>
                    <option value="Want to Read" <?= isset($current_list_status['status']) && $current_list_status['status'] == 'Want to Read' ? 'selected' : '' ?>>Want to Read</option>
                    <option value="Currently Reading" <?= isset($current_list_status['status']) && $current_list_status['status'] == 'Currently Reading' ? 'selected' : '' ?>>Currently Reading</option>
                    <option value="Read" <?= isset($current_list_status['status']) && $current_list_status['status'] == 'Read' ? 'selected' : '' ?>>Read</option>
                </select>
            </div>

            <button type="submit" class="btn btn-inline-submit">Update Status</button>
        </form>
    </div>
<?php endif; ?>

<!--Reviewing-->
<?php if (isset($_SESSION['userID'])): ?> 
    <div class="form-card form-card-wide">
        <h3>Leave a Rating & Review</h3>

        <form action="add_review.php" method="POST">
            <input type="hidden" name="bookID" value="<?= $book['bookID'] ?>">

            <!--Anti spam Honeypot Trap-->
            <div style="display:none;">
                <input type="text" name="user_website" id="user_website" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="rating">Rating (1-5)</label>
                <select id="rating" name="rating" required>
                    <option value="5">5★★★★★ - Excellent</option>
                    <option value="4">4★★★★ - Good</option>
                    <option value="3">3★★★ - Average</option>
                    <option value="2">2★★ - Poor</option>
                    <option value="1">1★ - Terrible</option>
                </select>
            </div>

            <div class="form-group">
                <label for="review_text">Written Review:</label>
                <textarea id="review_text" name="review_text" rows="5" required placeholder="Write your thoughts"></textarea>
            </div>

            <button type="submit" class="btn">Submit Review</button>
        </form>
    </div>
<?php else: ?>
    <div class="alert alert-error">
        Please <a href="login.php">login</a> to leave a review or add book to your list.
    </div>
<?php endif; ?>

<div class="review-list">
    <h3>Community Reviews (<?= count($reviews) ?>)</h3>

    <?php if ($reviews): ?>
        <?php foreach ($reviews as $r): ?>
            <div class="review-item">
                <strong><?= htmlspecialchars($r['username']) ?></strong>
                <span class="review-rating-stars">★ <?= $r['rating'] ?>/5</span>

                <p class="review-date"><?= $r['created_at'] ?></p> 
                <p class="review-text"><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-reviews-msg">No reviews posted yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
