<?php
$page_title = "Edit Post";
$active_page = "posts";
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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$error = "";

// Fetch post to check existence and ownership
$stmt = mysqli_prepare($conn, "SELECT * FROM posts WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$post) {
    // Post not found
    header("Location: viewposts.php");
    exit();
}

// Authorization check: Make sure this post belongs to the logged-in user
if ($post['user_id'] != $user_id) {
    die("Access Denied: You do not have permission to edit this post.");
}

if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Please fill in all fields.";
    } else {
        // Secure update with prepared statement
        $update_stmt = mysqli_prepare($conn, "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($update_stmt, "ssii", $title, $content, $id, $user_id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            mysqli_stmt_close($update_stmt);
            header("Location: viewposts.php");
            exit();
        } else {
            $error = "Error updating post: " . mysqli_error($conn);
        }
        mysqli_stmt_close($update_stmt);
    }
}

include "../config/header.php";
?>

<div class="form-container">
    <div class="form-card" style="max-width: 600px; margin: 0 auto;">
        <h2>Edit Post</h2>
        <p class="form-subtitle">Make changes to your published article</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="edit.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label for="title">Post Title</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-control" 
                    value="<?php echo isset($title) ? htmlspecialchars($title) : htmlspecialchars($post['title']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea 
                    id="content" 
                    name="content" 
                    class="form-control" 
                    required
                ><?php echo isset($content) ? htmlspecialchars($content) : htmlspecialchars($post['content']); ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" name="update" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <i class="fa-solid fa-circle-check"></i> Save Changes
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