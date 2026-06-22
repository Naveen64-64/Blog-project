<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure path prefix is defined
if (!isset($path_prefix)) {
    $path_prefix = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " - MyBlog" : "MyBlog - Modern Publishing Platform"; ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Premium CSS stylesheet -->
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>css/style.css">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <a href="<?php echo $path_prefix; ?>index.php">
                    <span class="logo-icon"><i class="fa-solid fa-feather-pointed"></i></span>
                    <span class="logo-text">MyBlog</span>
                </a>
            </div>
            
            <ul class="nav-links">
                <li>
                    <a href="<?php echo $path_prefix; ?>index.php" class="<?php echo isset($active_page) && $active_page == 'home' ? 'active' : ''; ?>">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                </li>
                <li>
                    <a href="<?php echo $path_prefix; ?>posts/viewposts.php" class="<?php echo isset($active_page) && $active_page == 'posts' ? 'active' : ''; ?>">
                        <i class="fa-solid fa-newspaper"></i> Posts
                    </a>
                </li>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <li>
                        <a href="<?php echo $path_prefix; ?>posts/create.php" class="<?php echo isset($active_page) && $active_page == 'create' ? 'active' : ''; ?>">
                            <i class="fa-solid fa-circle-plus"></i> Create Post
                        </a>
                    </li>
                    <li class="user-profile">
                        <span class="user-badge">
                            <i class="fa-solid fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                    </li>
                    <li>
                        <a href="<?php echo $path_prefix; ?>auth/logout.php" class="nav-btn-logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo $path_prefix; ?>auth/login.php" class="<?php echo isset($active_page) && $active_page == 'login' ? 'active' : ''; ?>">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $path_prefix; ?>auth/register.php" class="nav-btn-register <?php echo isset($active_page) && $active_page == 'register' ? 'active' : ''; ?>">
                            <i class="fa-solid fa-user-plus"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <!-- Main content wrapper -->
    <main class="main-wrapper">
