<?php
$page_title = "Login";
$active_page = "login";
$path_prefix = "../";

include "../config/database.php";

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Secure query with prepared statements
        $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id'];
                
                header("Location: ../posts/viewposts.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
        mysqli_stmt_close($stmt);
    }
}

include "../config/header.php";
?>

<div class="form-container">
    <div class="form-card">
        <h2>Login</h2>
        <p class="form-subtitle">Welcome back! Log in to manage your posts</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-control" 
                    placeholder="Enter your username" 
                    value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Enter your password" 
                    required
                >
            </div>
            
            <button type="submit" name="login" class="btn btn-primary form-submit-btn">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </button>
        </form>
        
        <div class="form-footer">
            Don't have an account? <a href="register.php">Register now</a>
        </div>
    </div>
</div>

<?php
include "../config/footer.php";
?>