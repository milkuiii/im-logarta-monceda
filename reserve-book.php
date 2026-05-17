<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    include 'includes/db.php';

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: view-books.php");
        exit();
    }
    $target_book_id = $_GET['id'];

    $endpoint = "tblbook?id=eq." . urlencode($target_book_id) . "&select=*";
    $response = supabase_request("GET", $endpoint);
    $bookData = $response['data'];

    if (empty($bookData)) {
        header("Location: view-books.php?error=book_not_found");
        exit();
    }
    $book = $bookData[0]; 

    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $user_id = $_SESSION['user_id'];

        // Chronological safety validation
        if (strtotime($start_date) > strtotime($end_date)) {
            $error = "The end date cannot be earlier than the start date.";
        } else {
            $reservationData = [
                'user_id'    => $user_id,
                'book_id'    => intval($target_book_id),
                'room_id'    => null, 
                'date_start' => $start_date,  // Matches your timestamp column
                'date_end'   => $end_date,    // Matches your timestamp column
                'is_room'    => false,        // Explicitly false since this is a book reservation
                'isApproved' => false         // Defaults to unapproved/pending status
            ];

            $res = supabase_request("POST", "tblreservation", $reservationData);

            if ($res['status'] >= 200 && $res['status'] < 300) {
                header("Location: calendar.php?reservation=success");
                exit();
            } else {
                $error = "Failed to secure reservation. Please try again or check library rules.";
            }
        }
    }

    $page_type = 'reserve-book';
    include 'includes/header.php';
?>

    <main class="auth-container">
        <div class="auth-card" style="max-width: 900px; padding: 4rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 2rem;">RESERVE BOOK</h2>
            
            <?php if (!empty($error)): ?>
                <p style="color: var(--primary-red); text-align: center; font-weight: 700; margin-bottom: 2rem;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <div style="text-align: center; margin-bottom: 4rem;">
                <h3 style="font-size: 3.2rem; color: var(--primary-red); font-weight: 950; line-height: 1.1; margin-bottom: 0.5rem;">
                    <?php echo htmlspecialchars($book['title']); ?>
                </h3>
                <div style="color: var(--accent-yellow); font-weight: 800; font-size: 1.3rem;">
                    <?php echo htmlspecialchars($book['author']); ?> | <?php echo htmlspecialchars($book['genre']); ?>
                </div>
                <p style="margin-top: 2rem; color: #333; font-size: 1rem; line-height: 1.6; max-width: 800px; margin-left: auto; margin-right: auto;">
                    <?php echo htmlspecialchars($book['description'] ?? 'No catalog description provided for this library title.'); ?>
                </p>
            </div>

            <form id="reserveBookForm" method="POST" action="reserve-book.php?id=<?php echo urlencode($target_book_id); ?>">
                <div class="form-group">
                    <label for="start_date">DATE OF START</label>
                    <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">DATE OF END</label>
                    <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <p style="text-align: center; color: var(--accent-yellow); font-weight: 800; margin-top: 3rem; font-size: 1.1rem;">
                    By Reserving, I agree to the <a href="#" style="color: var(--accent-yellow);">Terms of Service</a> of the College Library.
                </p>
                
                <button type="submit" class="btn-auth-submit" style="width: 50%; border: none; cursor: pointer; display: block; margin: 5 auto; text-align: center;">
                    RESERVE
                </button>
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

    <!-- Book Reservation Confirmation Modal -->
    <div id="bookModal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <h3 class="custom-modal-title">Confirm Book Reservation</h3>
            <div class="custom-modal-body" id="bookModalBody">
            </div>
            <div class="custom-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeBookModal()">Edit</button>
                <button type="button" class="btn-modal-confirm" onclick="confirmBookReservation()">Reserve</button>
            </div>
        </div>
    </div>

    <script>
        const reserveBookForm = document.getElementById('reserveBookForm');
        const bookModal = document.getElementById('bookModal');
        const bookModalBody = document.getElementById('bookModalBody');

        reserveBookForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Check that end date is later than start date
            if (new Date(endDate) <= new Date(startDate)) {
                alert("The end date must be later than the start date.");
                return;
            }

            bookModalBody.innerHTML = `
                <p>Start Date: <span>${startDate}</span></p>
                <p>End Date: <span>${endDate}</span></p>
            `;
            
            bookModal.classList.add('active');
        });

        function closeBookModal() {
            bookModal.classList.remove('active');
        }

        function confirmBookReservation() {
            reserveBookForm.submit();
        }
    </script>

<?php include 'includes/footer.php'; ?>