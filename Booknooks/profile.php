<!--
 B20250035 Alya Sofiya binti Haji Rosaimi
 B20250113 Nur Aini Jamilah Moahamad Johnny
 B20250176 Ahmad Ridhuan bin Mohamad Johnny -->

 <?php
 require_once 'dbconnect.php';
 include 'header.php';

 if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
 }

 //Fetch user info
 $stmt = $pdo->prepare("SELECT * FROM users WHERE userID = :id");
 $stmt->execute([':id' => $_SESSION['userID']]);
 $user = $stmt->fetch();
 ?>

    <h2>&nbsp;Your Profile</h2>
    
    
    <main class="main-interface">
        <div class="profile-card">
           
            <div class="info-row">
                <span class="info-label">User ID:</span>
                <span class="info-value">#BAWLS123</span>
            </div>
           
           
            <div class="info-row">
                <span class="info-label">Display Name:</span>
                <span class="info-value"><?= htmlspecialchars($user['username']) ?></span>
            </div>
           
            <div class="info-row">
                <span class="info-label">Email Address:</span>
                <span class="info-value secure-email-wrapper">
                    <span class="email-text"><?= htmlspecialchars($user['email']) ?></span>
                    <div class="secure-mask">Reveal Email</div>
                </span>
            </div>
    
    
        </div>
    </main>

    <?php include 'footer.php'; ?>
    