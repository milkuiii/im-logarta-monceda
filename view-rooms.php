<?php
    $page_type = 'app';
    include 'includes/db.php';
    include 'includes/header.php';

    // Fetch books from Supabase
    $response = supabase_request('GET', 'tblroom?select=*');
    $rooms = [];
    if (isset($response['status']) && $response['status'] === 200) {
        $rooms = $response['data'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Browse Rooms</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <section class="browse-header">
            <h2>BROWSE ROOMS BY <span style="color: var(--accent-yellow);">DATE</span></h2>
            <div class="search-bar">
                <input type="text" placeholder="Search by date and time">
                <button class="search-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>
        </section>

        <section class="browse-section">
            <div class="browse-by">
                <h3>BROWSE AVAILABLE ROOMS</h3>
            </div>

            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $room): ?>
                <div class="item-card">
                    <div class="item-info">
                        <h4><?php echo htmlspecialchars($room['name'] ?? 'Unknown Name'); ?></h4>
                        <div class="item-meta">
                            CAPACITY: <?php echo htmlspecialchars($room['capacity'] ?? 'Unknown Capacity'); ?>

                        </div>
                    </div>
                    <a href="calendar.php" class="btn-item-action" style="text-decoration: none; display: inline-block; text-align: center;">RESERVE</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding: 20px; color: #666; font-family: var(--font-primary);">No rooms found in the database.</p>
            <?php endif; ?>

<!-- 
            <div class="item-card">
                <div class="item-info">
                    <h4>Room Name</h4>
                    <div class="item-meta">Location, CIT-U Library</div>
                    <p class="item-desc">Capacity: 4 people maximum</p>
                </div>
                <a href="calendar.php" class="btn-item-action" style="border-radius: 8px; font-size: 0.9rem; text-decoration: none; display: inline-block; text-align: center;">VIEW TIMESLOTS</a>
            </div>

            <div class="item-card">
                <div class="item-info">
                    <h4>Room Name</h4>
                    <div class="item-meta">Location, CIT-U Library</div>
                    <p class="item-desc">Capacity: 4 people maximum</p>
                </div>
                <a href="calendar.php" class="btn-item-action" style="border-radius: 8px; font-size: 0.9rem; text-decoration: none; display: inline-block; text-align: center;">VIEW TIMESLOTS</a>
            </div>

            <div class="item-card">
                <div class="item-info">
                    <h4>Room Name</h4>
                    <div class="item-meta">Location, CIT-U Library</div>
                    <p class="item-desc">Capacity: 4 people maximum</p>
                </div>
                <a href="calendar.php" class="btn-item-action" style="border-radius: 8px; font-size: 0.9rem; text-decoration: none; display: inline-block; text-align: center;">VIEW TIMESLOTS</a>
            </div> -->

            <!-- <div class="pagination">
                <span>&lt;</span>
                <span style="border-bottom: 3px solid var(--primary-red); padding-bottom: 4px;">1</span>
                <span>2</span>
                <span>...</span>
                <span>16</span>
                <span>&gt;</span>
            </div> -->
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

