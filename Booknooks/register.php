<?php
require_once 'dbconnect.php';
include 'header.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    //Validate Input
    if (empty($username)) { $errors[] = "Username is required.";}
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "A valid email address is required.";}
    if (strlen($password) < 8) { $errors[] = "Password must be at least 8 characters long.";}

    if(empty($errors)) {
        try {
            //Check existing account
            $stmt = $pdo->prepare("SELECT userId FROM users WHERE email = :email OR username = :username");
            $stmt->execute([':email' => $email, ':username' => $username]);

            if ($stmt->fetch()) {
                $errors[] = "Username or Email adress is already registered.";
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)");
                $stmt->execute([':username' => $username, ':email' => $email, ':hash' => $hash, ':role' => $role]);
                $success = "Account created successfully! <a href='login.php'>Login here</a>.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }    
        
    }

}
?>

<div class="form-card">
    <h2>Create Account</h2>
    <?php if ($errors): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
         </div>
    <?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <form action="register.php" method="POST" novalidate>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '')?>">
    </div>

    <div class="form-group">
        <label for="email">Email Address:</label>
        <input type ="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['emai'] ?? '')?>">
    </div>

    <div class="form-group">
        <label for="password">Password (Min 8 characters):</label>
        <input type="password" id="password" name="password" required minlength="8">
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>

    <div class="form-group">
        <label for="role">Register As:</label>
        <select id="role" name="role">
            <option value="user">Regular Reader (User)</option>
            <option value="admin">Library Manager (Admin)</option>
         </select>
    </div>

    <button type="submit" class="btn">Register</button>
  </form>
  <p class="form-footer">Already registered? <a href="login.php">Login here</a></p>
</div>

<?php include 'footer.php'; ?>


