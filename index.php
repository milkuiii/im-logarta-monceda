<?php
    $page_type = 'landing';
    include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Library Reservation System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <section class="hero">
            <h1>RESERVE <br>with <span class="ease-text">Ease</span></h1>
            <p>Plan smarter, study better. This all-in-one library reservation system gives students and faculty guaranteed access to books and spaces through a simple, organized, and reliable booking experience</p>
        </section>

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
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

