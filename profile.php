<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Security Check: Block unauthenticated users
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    include 'includes/db.php';
    $user_id = $_SESSION['user_id'];

    // 2. Complex Relational Request to Supabase: Pull reservations along with related book/room names
    // Using PostgREST syntax to join tblbook(title) and tblroom(name) seamlessly
    $endpoint = "tblreservation?user_id=eq." . $user_id . "&select=*,tblbook(title),tblroom(name)&order=date_start.desc";
    $response = supabase_request("GET", $endpoint);
    $myReservations = isset($response['data']) ? $response['data'] : [];

    $page_type = 'system'; 
    include 'includes/header.php';
?>

    <main class="auth-container" style="flex-direction: column; gap: 40px; padding: 4rem 2rem;">
        
        <div class="auth-card" style="max-width: 900px; padding: 4rem; width: 100%;">
            <h2>USER PROFILE</h2>
            
            <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
                <p style="color: green; text-align: center; font-weight: bold; margin-bottom: 2rem;">Profile updated successfully!</p>
            <?php endif; ?>

            <form id="profileForm" action="update_profile.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="first_name">FIRST NAME</label>
                        <input type="text" id="first_name" name="fname" value="<?php echo htmlspecialchars($_SESSION['fname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">LAST NAME</label>
                        <input type="text" id="last_name" name="lname" value="<?php echo htmlspecialchars($_SESSION['lname']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">INSTITUTIONAL EMAIL</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" readonly style="background-color: #f4f4f4; cursor: not-allowed;">
                </div>
                
                <div style="display: flex; gap: 20px; justify-content: center; margin-top: 2rem;">
                    <button type="submit" class="btn-auth-submit" style="margin: 0; width: 45%; border: none; cursor: pointer;">SAVE CHANGES</button>
                    <a href="view-books.php" class="btn-auth-submit" style="text-decoration: none; display: flex; align-items: center; justify-content: center; margin: 0; width: 45%; background-color: transparent; border: 3px solid var(--primary-red); color: var(--primary-red);">CANCEL</a>
                </div>
            </form>
        </div>

        <div class="auth-card" style="max-width: 900px; padding: 4rem; width: 100%;">
            <h2 style="margin-bottom: 2rem;">MY RESERVATIONS</h2>
            
            <?php if (empty($myReservations)): ?>
                <div style="text-align: center; padding: 2rem; color: #666; font-weight: 600;">
                    <p>You haven't made any book or room reservations yet.</p>
                    <a href="view-books.php" style="color: var(--primary-red); text-decoration: underline; display: inline-block; margin-top: 1rem;">Browse Books</a> or 
                    <a href="view-rooms.php" style="color: var(--primary-red); text-decoration: underline;">Browse Rooms</a>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php foreach ($myReservations as $res): ?>
                        <div style="background-color: #fff; border: 2px solid #E0E0E0; border-radius: 15px; padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                            <div>
                                <?php if ($res['is_room'] && !empty($res['tblroom'])): ?>
                                    <h4 style="font-size: 1.3rem; color: var(--primary-red); font-weight: 800;">🏫 Room: <?php echo htmlspecialchars($res['tblroom']['name']); ?></h4>
                                <?php elseif (!empty($res['tblbook'])): ?>
                                    <h4 style="font-size: 1.3rem; color: var(--primary-red); font-weight: 800;">📖 Book: <?php echo htmlspecialchars($res['tblbook']['title']); ?></h4>
                                <?php else: ?>
                                    <h4 style="font-size: 1.3rem; color: var(--primary-red); font-weight: 800;">🔍 Unknown Resource Layout</h4>
                                <?php endif; ?>
                                
                                <p style="font-size: 0.95rem; margin-top: 0.5rem; color: #555; font-weight: 600;">
                                    📅 <?php echo date("M d, Y", strtotime($res['date_start'])); ?> 
                                    <?php if($res['is_room']): ?>
                                        | 🕒 <?php echo date("h:i A", strtotime($res['date_start'])) . " - " . date("h:i A", strtotime($res['date_end'])); ?>
                                    <?php else: ?>
                                        to <?php echo date("M d, Y", strtotime($res['date_end'])); ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div>
                                <?php if (isset($res['isApproved']) && $res['isApproved'] === true): ?>
                                    <span style="background-color: #E8F5E9; color: #2E7D32; padding: 0.6rem 1.5rem; border-radius: 50px; font-weight: 800; font-size: 0.9rem; border: 2px solid #2E7D32;">
                                        APPROVED
                                    </span>
                                <?php else: ?>
                                    <span style="background-color: #FFF3E0; color: #E65100; padding: 0.6rem 1.5rem; border-radius: 50px; font-weight: 800; font-size: 0.9rem; border: 2px solid #E65100;">
                                        PENDING
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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

    <!-- Profile Confirmation Modal -->
    <div id="profileModal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <h3 class="custom-modal-title">Confirm Profile Update</h3>
            <div class="custom-modal-body" id="profileModalBody">
            </div>
            <div class="custom-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeProfileModal()">Edit</button>
                <button type="button" class="btn-modal-confirm" onclick="confirmProfile()">Save</button>
            </div>
        </div>
    </div>

    <script>
        const profileForm = document.getElementById('profileForm');
        const profileModal = document.getElementById('profileModal');
        const profileModalBody = document.getElementById('profileModalBody');

        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fname = document.getElementById('first_name').value;
            const lname = document.getElementById('last_name').value;
            const email = document.getElementById('email').value;

            profileModalBody.innerHTML = `
                <p>Email: <span>${email}</span></p>
                <p>First Name: <span>${fname}</span></p>
                <p>Last Name: <span>${lname}</span></p>
            `;
            
            profileModal.classList.add('active');
        });

        function closeProfileModal() {
            profileModal.classList.remove('active');
        }

        function confirmProfile() {
            profileForm.submit();
        }
    </script>

<?php include 'includes/footer.php'; ?>