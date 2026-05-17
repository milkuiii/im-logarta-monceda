<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Gate: Ensure only logged-in Admins see this view
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] !== true) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

// Fetch all reservations with relational table data joins
$endpoint = "tblreservation?select=*,tbluser(fname,lname),tblbook(title),tblroom(name)&order=isApproved.asc,date_start.desc";
$response = supabase_request("GET", $endpoint);
$allReservations = isset($response['data']) ? $response['data'] : [];

$page_type = 'system';
include 'includes/header.php';
?>

<main>
    <section class="browse-section">
        
        <!-- <div class="browse-by">
            <h3>ADMIN CONTROL</h3>
            <div class="filter-pills">
                <span class="filter-pill active">PENDING OVERVIEW</span>
            </div>
        </div> -->

        <h4 class="results-title">Reservation Requests</h4>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'success'): ?>
            <p style="color: green; font-weight: bold; padding: 0 0 20px 0; font-family: var(--font-main);">
                ✓ Reservation status ledger updated successfully!
            </p>
        <?php endif; ?>

        <?php if (!empty($allReservations)): ?>
            <?php foreach ($allReservations as $res): ?>
                
                <div class="item-card">
                    
                    <div class="item-info">
                        
                        <h4>
                            <?php if ($res['is_room'] && !empty($res['tblroom'])): ?>
                                🏫 Room: <?php echo htmlspecialchars($res['tblroom']['name']); ?>
                            <?php elseif (!empty($res['tblbook'])): ?>
                                📖 Book: <?php echo htmlspecialchars($res['tblbook']['title']); ?>
                            <?php else: ?>
                                🔍 Unknown Resource Space
                            <?php endif; ?>
                        </h4>
                        
                        <div class="item-meta">
                            By: <?php echo htmlspecialchars($res['tbluser']['fname'] . " " . $res['tbluser']['lname']); ?> | 
                            Status: <?php echo ($res['isApproved'] === true) ? 'Approved' : 'Pending Verification'; ?>
                        </div>
                        
                        <p class="item-desc">
                            📅 Schedule Timeline: <?php echo date("M d, Y", strtotime($res['date_start'])); ?>
                            <?php if($res['is_room']): ?>
                                | 🕒 <?php echo date("h:i A", strtotime($res['date_start'])) . " - " . date("h:i A", strtotime($res['date_end'])); ?>
                            <?php else: ?>
                                to <?php echo date("M d, Y", strtotime($res['date_end'])); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px; min-width: 180px; justify-content: center;">
                        <?php if (isset($res['isApproved']) && $res['isApproved'] === true): ?>
                            <span style="background-color: #E8F5E9; color: #2E7D32; padding: 12px 24px; border-radius: 100px; font-weight: 800; font-size: 0.9rem; text-align: center; border: 2px solid #2E7D32; text-transform: uppercase; letter-spacing: 1px;">
                                APPROVED
                            </span>
                            <a href="process-reservation.php?id=<?php echo $res['id']; ?>&status=deny" style="color: var(--primary-red); font-size: 0.85rem; font-weight: 800; text-align: center; text-decoration: underline;">
                                Revoke Access
                            </a>
                        <?php else: ?>
                            <a href="process-reservation.php?id=<?php echo $res['id']; ?>&status=approve" class="btn-item-action" style="text-decoration: none; display: block; text-align: center; margin: 0; width: 100%;">
                                APPROVE
                            </a>
                            <a href="process-reservation.php?id=<?php echo $res['id']; ?>&status=deny" class="btn-item-action" style="text-decoration: none; display: block; text-align: center; margin: 0; width: 100%; background-color: transparent; border: 3px solid var(--primary-red); color: var(--primary-red);">
                                DENY
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="padding: 20px; color: #666; font-family: var(--font-main);">No pending reservation logs found in the system ledger.</p>
        <?php endif; ?>

    </section>
</main>

<?php include 'includes/footer.php'; ?>