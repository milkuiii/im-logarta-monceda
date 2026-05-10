<?php
    $page_type = 'calendar';
    include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Select Date</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="auth-container">
        <div class="calendar-card" style="max-width: 1000px;">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 2rem; font-weight: 950; color: var(--primary-red);">ROOM RESERVATION</h2>
            <div style="text-align: center; margin-bottom: 3rem;">
                <h3 style="font-size: 2rem; color: var(--primary-red); font-weight: 900;">Conference Room A</h3>
                <div style="color: var(--accent-yellow); font-weight: 700;">Location: 2nd Floor, CIT-U Library</div>
            </div>

            <div class="form-group" style="margin-bottom: 4rem;">
                <label for="reservation_date" style="font-size: 1.5rem; text-align: center; display: block; margin-bottom: 1rem; color: var(--primary-red); font-weight: 900;">SELECT RESERVATION DATE</label>
                <input type="date" id="reservation_date" name="reservation_date" value="2021-11-14" style="width: 100%; padding: 1.2rem; border-radius: 20px; border: 3px solid var(--primary-red); font-size: 1.2rem; font-weight: 800; color: var(--primary-red); text-align: center;">
            </div>

            <div class="timeslots-section">
                <h3>SELECT TIMESLOT</h3>
                <div class="timeslots-grid">
                    <div class="timeslot">08:00 AM - 09:00 AM</div>
                    <div class="timeslot selected">09:30 AM - 10:30 AM</div>
                    <div class="timeslot">11:00 AM - 12:00 PM</div>
                    <div class="timeslot">01:00 PM - 02:00 PM</div>
                    <div class="timeslot">02:30 PM - 03:30 PM</div>
                    <div class="timeslot">04:00 PM - 05:00 PM</div>
                </div>
                
                <p style="text-align: center; color: var(--accent-yellow); font-weight: 800; margin-top: 3rem; font-size: 1.1rem;">By Reserving, I agree to the <a href="#" style="color: var(--accent-yellow);">Terms of Service</a> of the College Library.</p>
                
                <a href="confirm.php" class="btn-calendar-reserve" style="text-decoration: none; display: block; text-align: center;">RESERVE</a>
            </div>
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
