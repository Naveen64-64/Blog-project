<?php
$page_title = "Posts";
$active_page = "posts";
$path_prefix = "../";

include "../config/database.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$logged_in = isset($_SESSION['user_id']);

// Require login for "My Posts" filter
if ($filter === 'my' && !$logged_in) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch posts based on filter using prepared statements
if ($filter === 'my') {
    $user_id = $_SESSION['user_id'];
    $stmt = mysqli_prepare($conn, 
        "SELECT posts.*, users.username 
         FROM posts 
         LEFT JOIN users ON posts.user_id = users.id 
         WHERE posts.user_id = ? 
         ORDER BY posts.created_at DESC"
    );
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, 
        "SELECT posts.*, users.username 
         FROM posts 
         LEFT JOIN users ON posts.user_id = users.id 
         ORDER BY posts.created_at DESC"
    );
}

include "../config/header.php";
?>

<div class="posts-header-section">
    <h1><?php echo $filter === 'my' ? 'My Posts' : 'All Articles'; ?></h1>
    
    <?php if ($logged_in): ?>
        <div class="filter-tabs">
            <a href="viewposts.php?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
                <i class="fa-solid fa-globe"></i> All Posts
            </a>
            <a href="viewposts.php?filter=my" class="filter-tab <?php echo $filter === 'my' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user"></i> My Posts
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if ($result && mysqli_num_rows($result) > 0): ?>
    <div class="posts-grid">
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
            <?php 
            $is_owner = $logged_in && ($_SESSION['user_id'] == $post['user_id']);
            ?>
            <article class="post-card">
                <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                <p class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                
                <div class="post-footer">
                    <div class="post-meta">
                        <span class="post-author">
                            <i class="fa-solid fa-feather"></i> 
                            <?php echo $is_owner ? 'You' : htmlspecialchars($post['username'] ?? 'Anonymous'); ?>
                        </span>
                        <span class="post-date">
                            <i class="fa-solid fa-calendar-days"></i> 
                            <?php echo date("F j, Y", strtotime($post['created_at'])); ?>
                        </span>
                    </div>
                    
                    <?php if ($is_owner): ?>
                        <div class="post-actions">
                            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn-icon btn-icon-edit" title="Edit Post">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" 
                               class="btn-icon btn-icon-delete" 
                               title="Delete Post"
                               onclick="return confirm('Are you sure you want to permanently delete this post?');"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fa-regular fa-folder-open"></i>
        </div>
        <h3>No posts found</h3>
        <p>
            <?php 
            if ($filter === 'my') {
                echo "You haven't written any posts yet. Start sharing your ideas!";
            } else {
                echo "No posts have been published yet. Be the first to share something!";
            }
            ?>
        </p>
        
        <?php if ($logged_in): ?>
            <a href="create.php" class="btn btn-primary">
                <i class="fa-solid fa-pen-nib"></i> Write First Post
            </a>
        <?php else: ?>
            <a href="../auth/login.php" class="btn btn-primary">
                <i class="fa-solid fa-right-to-bracket"></i> Login to Post
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
if ($filter === 'my' && isset($stmt)) {
    mysqli_stmt_close($stmt);
}
include "../config/footer.php";
?>
