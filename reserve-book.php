<?php
    $page_type = 'reserve-book';
    include 'includes/header.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Reserve Book</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="auth-container">
        <div class="auth-card" style="max-width: 900px; padding: 4rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 2rem;">RESERVE BOOK</h2>
            
            <div style="text-align: center; margin-bottom: 4rem;">
                <h3 style="font-size: 3.2rem; color: var(--primary-red); font-weight: 950; line-height: 1.1; margin-bottom: 0.5rem;">Lorem Ipsum Title</h3>
                <div style="color: var(--accent-yellow); font-weight: 800; font-size: 1.3rem;">Author Name | 3rd Edition: 128 pages</div>
                <p style="margin-top: 2rem; color: #333; font-size: 1rem; line-height: 1.6; max-width: 800px; margin-left: auto; margin-right: auto;">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
            </div>

            <form>
                <div class="form-group">
                    <label for="start_date">DATE OF START</label>
                    <input type="date" id="start_date" name="start_date">
                </div>
                <div class="form-group">
                    <label for="end_date">DATE OF END</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
                
                <p style="text-align: center; color: var(--accent-yellow); font-weight: 800; margin-top: 3rem; font-size: 1.1rem;">By Reserving, I agree to the <a href="#" style="color: var(--accent-yellow);">Terms of Service</a> of the College Library.</p>
                
                <a href="confirm.php" class="btn-auth-submit" style="width: 50%; text-decoration: none; display: block; text-align: center; margin: 0 auto;">RESERVE</a>
            </form>
        </div>
    </main>

    <section class="cta-footer">
        <div class="cta-text">
            <h2>RESERVE WITH EASE TODAY</h2>
            <p>Plan smarter, study better.</p>
            <a href="signup.php" class="btn-get-started">Get started</a>
        </div>
        <div class="footer-menu">
            <div class="footer-menu-column">
                <h3>Menu</h3>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="view-rooms.php">View Rooms</a></li>
                    <li><a href="view-books.php">View Books</a></li>
                </ul>
            </div>
            <div class="footer-menu-column">
                <h3>Follow us</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Linkedin</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Telegram</a></li>
                </ul>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

