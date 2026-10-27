<?php
// B20250035 Alya Sofiya binti Haji Rosaimi
// B20250113 Nur Aini Jamilah Mohamad Johnny
// B20250176 Ahmad Ridhuan bin Mohamad Johnny
require_once 'dbconnect.php';
include 'header.php';


$error = "";


//Redirect if session is already active
if (isset($_SESSION['userID'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'index.php'));
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();


            if ($user && password_verify($password, $user['password_hash'])) {
                //Initialize session
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];


                header("Location: " . ($user['role'] === 'admin' ? 'admin_dashboard.php' : 'index.php'));
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
    
    
    <h2>&nbsp;Login/Register</h2>
    
    
    <main class="form-interface">
        <div class="auth-container">
            <div class="auth-tabs-navigation">
                <button class="auth-tab-btn active" onclick="switchAuthTab('login-panel')">
                    <span class="btn-icon">➔]</span> Login
                </button>
                <button class="auth-tab-btn" onclick="switchAuthTab('register-panel')">
                    <span class="btn-icon">📝</span> Register
                </button>
                <button class="auth-tab-btn" onclick="switchAuthTab('forgot-panel')">
                    <span class="btn-icon">🔐</span> Forgot Password
                </button>
            </div>
    
    
            <div class="auth-panels-wrapper">
               
                <div id="login-panel" class="auth-tab-panel active">
                    <form action="login.php" method = "POST">
                        <div class="form-group">
                            <label for="login-email" class="field-label">Email</label>
                            <input type="email" id="login-email" name="email" placeholder="you@example.com" required class="text-input" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="login-password" class="field-label">Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="login-password" name="password" placeholder="********" required class="text-input">
                                <button type="button" class="password-toggle-icon" onclick="togglePasswordVisibility('login-password')">👁</button>
                            </div>
                        </div>
                        <div class="auth-action-row">
                            <button type="submit" class="submit-btn">Login</button>
                        </div>
                    </form>
                </div>
    
    
                <div id="register-panel" class="auth-tab-panel">
                    <form id="registerForm" onsubmit="event.preventDefault();">
                        <div class="form-group">
                            <label for="reg-email" class="field-label">Email</label>
                            <input type="email" id="reg-email" placeholder="you@example.com" required class="text-input">
                        </div>
                        <div class="form-group">
                            <label for="reg-username" class="field-label">Username</label>
                            <input type="text" id="reg-username" placeholder="Username" required class="text-input">
                        </div>
                        <div class="form-group">
                            <label for="reg-password" class="field-label">Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="reg-password" placeholder="********" required class="text-input">
                                <button type="button" class="password-toggle-icon" onclick="togglePasswordVisibility('reg-password')">👁</button>
                            </div>
                        </div>
                        <div class="form-action">
                            <button type="submit" class="submit-btn">Register</button>
                        </div>
                    </form>
                </div>
    
    
                <div id="forgot-panel" class="auth-tab-panel">
                    <form id="forgotForm" onsubmit="event.preventDefault();">
                        <div class="form-group">
                            <label for="forgot-email" class="field-label">Email</label>
                            <input type="email" id="forgot-email" placeholder="you@example.com" required class="text-input">
                        </div>
                        <div class="form-action">
                            <button type="submit" class="submit-btn">Reset Password</button>
                        </div>
                    </form>
                </div>
    
    
            </div>
        </div>
    </main>