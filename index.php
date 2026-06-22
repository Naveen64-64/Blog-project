<<<<<<< HEAD
<?php
$page_title = "Home";
$active_page = "home";
$path_prefix = "";

// Include database to fetch stats
include "config/database.php";

// Fetch simple dashboard metrics
$posts_count = 0;
$users_count = 0;

$res_posts = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM posts");
if ($res_posts) {
    $row = mysqli_fetch_assoc($res_posts);
    $posts_count = $row['cnt'];
}

$res_users = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users");
if ($res_users) {
    $row = mysqli_fetch_assoc($res_users);
    $users_count = $row['cnt'];
}

include "config/header.php";
?>

<div class="hero-card">
    <h1>Welcome to <span>MyBlog</span></h1>
    <p class="hero-subtitle">A premium, secure platform to read, write, and share your thoughts. Join our growing community of writers and start publishing today!</p>
    
    <div class="cta-group">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="posts/create.php" class="btn btn-primary">
                <i class="fa-solid fa-pen-nib"></i> Write a New Post
            </a>
            <a href="posts/viewposts.php" class="btn btn-secondary">
                <i class="fa-solid fa-list-ul"></i> Browse Feed
            </a>
        <?php else: ?>
            <a href="auth/register.php" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Join the Community
            </a>
            <a href="posts/viewposts.php" class="btn btn-secondary">
                <i class="fa-solid fa-book-open"></i> Read Articles
            </a>
        <?php endif; ?>
    </div>

    <!-- Stats Grid -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo number_format($posts_count); ?></div>
            <div class="stat-label">Published Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo number_format($users_count); ?></div>
            <div class="stat-label">Active Authors</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Secure &amp; Fast</div>
        </div>
    </div>
</div>

<?php
include "config/footer.php";
?>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexPlanet Internship</title>

    <!-- Link CSS File -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <?php
        echo "<h1>Hello ApexPlanet Internship!</h1>";
        ?>
    </div>

</body>
</html>
>>>>>>> 12cd39118c0ab05d07cd34f80ec2dd24e2d1af62
