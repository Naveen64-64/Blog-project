<?php
$page_title = "Create Post";
$active_page = "create";
$path_prefix = "../";

include "../config/database.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Restrict to logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$error = "";

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        $error = "Please fill in all fields.";
    } else {
        // Insert post using prepared statement
        $stmt = mysqli_prepare($conn, "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $content);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: viewposts.php");
            exit();
        } else {
            $error = "Error adding post: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

include "../config/header.php";
?>

<div class="form-container">
    <div class="form-card" style="max-width: 600px; margin: 0 auto;">
        <h2>Create Post</h2>
        <p class="form-subtitle">Share your insights and stories with the world</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="create.php">
            <div class="form-group">
                <label for="title">Post Title</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-control" 
                    placeholder="Enter a catchy title" 
                    value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea 
                    id="content" 
                    name="content" 
                    class="form-control" 
                    placeholder="Write your article here..." 
                    required
                ><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" name="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <i class="fa-solid fa-circle-check"></i> Add Post
                </button>
                <a href="viewposts.php" class="btn btn-secondary" style="flex: 1; justify-content: center; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
include "../config/footer.php";
?>