<?php
    $page_type = 'profile';
    include 'includes/header.php';
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - User Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <main class="auth-container">
        <div class="auth-card" style="max-width: 900px; padding: 4rem;">
            <h2>USER PROFILE</h2>
            
            <form>
                <div style="display: grid; grid-template-columns: 2fr 2fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="first_name">FIRST NAME</label>
                        <input type="text" id="first_name" name="first_name" value="John">
                    </div>
                    <div class="form-group">
                        <label for="last_name">LAST NAME</label>
                        <input type="text" id="last_name" name="last_name" value="Doe">
                    </div>
                    <div class="form-group">
                        <label for="middle_initial">M.I.</label>
                        <input type="text" id="middle_initial" name="middle_initial" value="D" maxlength="1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">INSTITUTIONAL EMAIL</label>
                    <input type="email" id="email" name="email" value="john.doe@university.edu">
                </div>
                
                <div style="display: flex; gap: 20px; justify-content: center; margin-top: 2rem;">
                    <a href="index.php" class="btn-auth-submit" style="text-decoration: none; display: block; text-align: center; margin: 0; width: 45%;">SAVE CHANGES</a>
                    <a href="index.php" class="btn-auth-submit" style="text-decoration: none; display: block; text-align: center; margin: 0; width: 45%; background-color: transparent; border: 3px solid var(--primary-red); color: var(--primary-red);">CANCEL</a>
                </div>
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

    <footer class="copyright-bar">
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Use</a>
            <a href="#">University Hotline</a>
            <a href="#">Legal</a>
            <a href="#">Site Map</a>
        </div>
        <div>© 2021 All Rights Reserved</div>
    </footer>
</body>
</html>
