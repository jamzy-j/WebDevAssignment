<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
require_once 'dbconnect.php';
include 'header.php';

$search = trim($_GET['search'] ?? '');
$genre = trim($_GET['genre'] ?? '');

$query = "SELECT b.*, ROUND(AVG(r.rating), 1) AS avg_rating,
          COUNT(r.reviewID) AS review_count
          FROM books b
          LEFT JOIN reviews r ON b.bookID = r.bookID WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (b.title LIKE :search OR b.author LIKE :search)";
    $params[':search'] = "%" . $search . "%"; 
}
if (!empty($genre)) {
    $query .= " AND b.genre = :genre"; 
    $params[':genre'] = $genre;        
}

$query .= " GROUP BY b.bookID ORDER BY b.bookID DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll();

$genres = $pdo->query("SELECT DISTINCT genre FROM books WHERE genre IS NOT NULL AND genre != ''")->fetchAll(PDO::FETCH_COLUMN);
?>

<h2>Browse Booknook’s Catalog</h2>

<form method="GET" class="filter-bar">
    <input type="text" name="search" class="filter-input" placeholder="Search by title or author" value="<?= htmlspecialchars($search) ?>">

    <select name="genre" class="filter-select">
        <option value="">All Genres</option>
        <?php foreach ($genres as $g): ?>
            <option value="<?= htmlspecialchars($g) ?>" <?= $genre === $g ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-filter">Filter</button>
</form>

<div class="catalog-grid">
    <?php if ($books): ?>
        <?php foreach ($books as $b): ?>
            <div class="book-card">
                <h3><?= htmlspecialchars($b['title']) ?></h3>
                <p class="book-meta">By <?= htmlspecialchars($b['author']) ?> (<?= $b['published_year'] ?>)</p>
                <p class="book-meta">Genre: <?= htmlspecialchars($b['genre'] ?? 'N/A') ?> | ISBN: <?= htmlspecialchars($b['isbn'] ?? 'N/A') ?></p>
               
                <div class="rating-badge">★ <?= $b['avg_rating'] ? $b['avg_rating'] : 'N/A' ?> (<?= $b['review_count'] ?>)</div>
                <a href="book_details.php?id=<?= $b['bookID'] ?>" class="btn" style="margin-top: auto;">View Details</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No books found matching the criteria.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

