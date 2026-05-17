<?php
    $page_type = 'app';
    include 'includes/db.php';
    include 'includes/header.php';

    // 1. Fetch all catalog books from Supabase
    $bookResponse = supabase_request('GET', 'tblbook?select=*');
    $books = [];
    if (isset($bookResponse['status']) && $bookResponse['status'] === 200) {
        $books = $bookResponse['data'];
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $limit = 5;
    $total_books = count($books);
    $total_pages = ceil($total_books / $limit);
    $books_on_page = array_slice($books, ($page - 1) * $limit, $limit);

    // 2. Fetch just the book_id column from tblreservation
    // This tells us exactly which books have an ongoing reservation entry
    $reservationResponse = supabase_request('GET', 'tblreservation?book_id=not.is.null&select=book_id');
    $reservedBookIds = [];
    
    if (isset($reservationResponse['status']) && $reservationResponse['status'] === 200) {
        // Extract just the raw IDs into a flat, clean array: [1, 4, 7, etc.]
        $reservedBookIds = array_column($reservationResponse['data'], 'book_id');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Browse Books</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <main>
        <section class="browse-header">
            <h2>SEARCH FOR <span style="color: var(--accent-yellow);">BOOKS</span></h2>
            <div class="search-bar">
                <input type="text" placeholder="Search by author, title ...">
                <button class="search-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>
        </section>

        <section class="browse-section">
            <div class="browse-by">
                <h3>BROWSE</h3>
                <!-- <div class="filter-pills">
                    <span class="filter-pill active">CATEGORY</span>
                    <span class="filter-pill">DEPARTMENT</span>
                    <span class="filter-pill">POPULAR</span>
                </div> -->
            </div>

            <h4 class="results-title">All Books</h4>

            <?php if (!empty($books_on_page)): ?>
                <?php foreach ($books_on_page as $book): ?>
                    <div class="item-card">
                        <div class="item-info">
                            <h4><?php echo htmlspecialchars($book['title'] ?? 'Unknown Title'); ?></h4>
                            <div class="item-meta">
                                <?php echo htmlspecialchars($book['author'] ?? 'Unknown Author'); ?> | 
                                <?php echo htmlspecialchars($book['genre'] ?? 'Unknown Genre'); ?>
                            </div>
                            <p class="item-desc"><?php echo htmlspecialchars($book['description'] ?? 'Description not found...'); ?></p>
                        </div>
                        
                        <?php 
                        // Check if the current book's ID exists anywhere in the reservation list
                        if (in_array($book['id'], $reservedBookIds)): 
                        ?>
                            <button class="btn-item-action" style="background-color: #ccc; cursor: not-allowed; border: none; text-decoration: none; display: inline-block; text-align: center;" disabled>
                                BORROWED
                            </button>
                        <?php else: ?>
                            <a href="reserve-book.php?id=<?php echo $book['id']; ?>" class="btn-item-action" style="text-decoration: none; display: inline-block; text-align: center;">
                                RESERVE
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding: 20px; color: #666; font-family: var(--font-primary);">No books found in the database.</p>
            <?php endif; ?>

            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="view-books.php?page=<?php echo $page - 1; ?>" style="text-decoration: none; color: inherit;">&lt;</a>
                <?php else: ?>
                    <span style="color: #ccc;">&lt;</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i === $page): ?>
                        <span style="border-bottom: 3px solid var(--primary-red); padding-bottom: 4px;"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="view-books.php?page=<?php echo $i; ?>" style="text-decoration: none; color: inherit;"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="view-books.php?page=<?php echo $page + 1; ?>" style="text-decoration: none; color: inherit;">&gt;</a>
                <?php else: ?>
                    <span style="color: #ccc;">&gt;</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
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

