<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($page_type)) { $page_type = 'system'; }

    // Helper variable for the display name
    $displayName = "GUEST";
    if (isset($_SESSION['fname']) && isset($_SESSION['lname'])) {
        $displayName = strtoupper($_SESSION['fname'] . "." . $_SESSION['lname']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Library Reservation System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="<?php echo $page_type === 'landing' ? 'landing-page' : 'system-page'; ?>">

    <header class="<?php echo $page_type === 'landing' ? 'header-landing' : 'header-internal'; ?>">
        <div class="logo-container">
            <img src="assets/img/citu-logo.png" alt="CIT-U Logo">
            <span class="logo-text">ReServe</span>
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php">HOME</a></li>
                
                <?php if($page_type === 'landing' && !isset($_SESSION['user_id'])): ?>
                    <li><a href="#">ABOUT</a></li>
                    <li><a href="view-rooms.php">VIEW ROOMS</a></li>
                    <li><a href="view-books.php">VIEW BOOKS</a></li>
                    <li><a href="login.php">LOG IN</a></li>
                    <li><a href="signup.php" class="btn-signup">SIGN UP</a></li>
                <?php else: ?>
                    <li><a href="view-rooms.php">ROOMS</a></li>
                    <li><a href="view-books.php">BOOKS</a></li>
                    <li><a href="calendar.php">CALENDAR</a></li>
                    <li>
                        <a href="profile.php" style="display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg> 
                            <?php echo $displayName; ?>
                        </a>
                    </li>
                    <li><a href="logout.php" style="color: var(--primary-red);">LOGOUT</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>