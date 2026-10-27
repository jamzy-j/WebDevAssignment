<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$current_page = basename($_SERVER['PHP_SELF']);
$current_role = strtolower($_SESSION['role'] ?? $_SESSION['role'] ?? '');
?>


<!DOCTYPE html>
<html lang="en">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <title>Reading Tracker</title>
        <link rel="stylesheet" href="design.css">
        <script src="scripts.js" defer></script>
    </head>


    <body>
        <header class="navbar">
            <div class="nav-container">
                <a href="index.php" class="logo">Booknooks</a>


                <nav>
                    <ul class="nav-links">
                        <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Catalog</a></li>


                        <?php if (isset($_SESSION['userID'])): ?>

                            <?php if ($current_role === 'admin'): ?>
                                <li><a href='admin_dashboard.php' class="<?= $current_page == 'admin_dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                                <li><a href='admin_dashboard.php' class="<?= $current_page == 'admin_dashboard.php' ? 'active' : '' ?>">Admin Panel</a></li>
                                <li><a href="add_book.php" class="<?= $current_page == 'add_book.php' ? 'active' : '' ?>">+ Add Book</a></li>

                            <?php  else: ?>
                                <li><a href="user_dashboard.php" class="<?= $current_page == 'user_dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                            <?php endif; ?>


                            <li class="user_info">Logged in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                            (<?= htmlspecialchars($_SESSION['role']) ?>)</li>


                            <li><a href="logout.php" class="btn-nav">Logout</a></li>


                        <?php else: ?>
                            <li><a href="login.php" class="<?= $current_page == 'login.php' ? 'active' : '' ?>">Login</a></li>


                            <li><a href="register.php" class="<?= $current_page == 'register.php' ? 'active' : '' ?>">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </header>
    <main class="main-content">
