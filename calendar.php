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
        header("Location: view-rooms.php");
        exit();
    }
    $target_room_id = $_GET['id'];

    // 1. Fetch Room Details
    $endpoint = "tblroom?id=eq." . urlencode($target_room_id) . "&select=*";
    $response = supabase_request("GET", $endpoint);
    $roomData = $response['data'];

    if (empty($roomData)) {
        header("Location: view-rooms.php?error=room_not_found");
        exit();
    }
    $room = $roomData[0];

    // 2. QUERY LOGIC ADDED: Fetch all current reservations for this room
    $resEndpoint = "tblreservation?room_id=eq." . urlencode($target_room_id) . "&select=date_start,date_end";
    $resResponse = supabase_request("GET", $resEndpoint);
    $existingBookings = [];
    if (isset($resResponse['status']) && $resResponse['status'] === 200) {
        $existingBookings = $resResponse['data'];
    }

    // 3. LEDGER FORMATTER ADDED: Combine date & timeslots to match your js/calendar.js matrix
    $takenSlots = [];
    foreach ($existingBookings as $booking) {
        $dateKey = date("Y-m-d", strtotime($booking['date_start']));
        $slotText = date("h:i A", strtotime($booking['date_start'])) . " - " . date("h:i A", strtotime($booking['date_end']));
        $takenSlots[] = $dateKey . "||" . $slotText;
    }

    $error = "";

    // 4. Handle Post Request Form Submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reservation_date = $_POST['reservation_date'];
        $selected_slot = $_POST['timeslot']; 
        $user_id = $_SESSION['user_id'];

        if (empty($reservation_date) || empty($selected_slot)) {
            $error = "Please fill in a reservation date and choose a valid time slot.";
        } else {
            list($start_str, $end_str) = explode(" - ", $selected_slot);
            
            $date_start = date("Y-m-d H:i:s", strtotime("$reservation_date $start_str"));
            $date_end   = date("Y-m-d H:i:s", strtotime("$reservation_date $end_str"));

            // Defensive Check: Stop double booking on form submission
            $conflictKey = $reservation_date . "||" . $selected_slot;
            if (in_array($conflictKey, $takenSlots)) {
                $error = "This room timeslot has just been booked by another student. Please select a different slot.";
            } else {
                $reservationData = [
                    'user_id'    => $user_id,
                    'room_id'    => intval($target_room_id),
                    'book_id'    => null, 
                    'date_start' => $date_start, 
                    'date_end'   => $date_end,   
                    'is_room'    => true,        
                    'isApproved' => false         
                ];

                $res = supabase_request("POST", "tblreservation", $reservationData);

                if ($res['status'] >= 200 && $res['status'] < 300) {
                    header("Location: confirm.php?type=room");
                    exit();
                } else {
                    $error = "Failed to secure room booking reservation. Please try a different slot.";
                }
            }
        }
    }

    $page_type = 'calendar';
    include 'includes/header.php';
?>

    <main class="auth-container">
        <div class="calendar-card" style="max-width: 1000px;">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 2rem; font-weight: 950; color: var(--primary-red);">ROOM RESERVATION</h2>
            
            <div style="text-align: center; margin-bottom: 3rem;">
                <h3 style="font-size: 2rem; color: var(--primary-red); font-weight: 900;">
                    <?php echo htmlspecialchars($room['name'] ?? 'Study Space'); ?>
                </h3>
                <div style="color: var(--accent-yellow); font-weight: 700;">
                    CAPACITY: <?php echo htmlspecialchars($room['capacity'] ?? '4'); ?> Pax | CIT-U Library
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <p style="color: var(--primary-red); font-weight: bold; text-align: center; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="calendar.php?id=<?php echo urlencode($target_room_id); ?>">
                <div class="form-group" style="margin-bottom: 4rem;">
                    <label for="reservation_date" style="font-size: 1.5rem; text-align: center; display: block; margin-bottom: 1rem; color: var(--primary-red); font-weight: 900;">SELECT RESERVATION DATE</label>
                    <input type="date" id="reservation_date" name="reservation_date" required min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 1.2rem; border-radius: 20px; border: 3px solid var(--primary-red); font-size: 1.2rem; font-weight: 800; color: var(--primary-red); text-align: center; background-color: #fff;">
                </div>

                <div class="timeslots-section">
                    <h3>SELECT TIMESLOT</h3>
                    
                    <div class="timeslots-grid">
                        <?php
                        $slots = [
                            "08:00 AM - 09:00 AM",
                            "09:30 AM - 10:30 AM",
                            "11:00 AM - 12:00 PM",
                            "01:00 PM - 02:00 PM",
                            "02:30 PM - 03:30 PM",
                            "04:00 PM - 05:00 PM"
                        ];
                        foreach ($slots as $index => $slot):
                        ?>
                        <label class="timeslot-label" data-slot-value="<?php echo $slot; ?>" style="display: block; cursor: pointer;">
                            <input type="radio" name="timeslot" value="<?php echo $slot; ?>" required style="display: none;" onclick="highlightSlot(this)">
                            <div class="timeslot-tile" style="background-color: #fff; padding: 1.2rem; border-radius: 20px; text-align: center; font-weight: 800; color: var(--text-dark); border: 3px solid transparent; transition: all 0.2s;">
                                <?php echo $slot; ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <p style="text-align: center; color: var(--accent-yellow); font-weight: 800; margin-top: 3rem; font-size: 1.1rem;">By Reserving, I agree to the <a href="#" style="color: var(--accent-yellow);">Terms of Service</a> of the College Library.</p>
                    
                    <button type="submit" class="btn-calendar-reserve" style="border: none; display: block; margin: 4rem auto 0; text-align: center; cursor: pointer; width: 50%;">
                        RESERVE
                    </button>
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

    <script>
        window.takenSlotsLedger = <?php echo json_encode($takenSlots); ?>;
    </script>
    <script src="js/calendar.js"></script>

<?php include 'includes/footer.php'; ?>