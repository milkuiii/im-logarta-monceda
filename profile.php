<?php
    // 1. Session check MUST be at the very top, before ANY HTML or spaces
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 2. Security check
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    include 'includes/db.php';
    $page_type = 'system'; 
    include 'includes/header.php'; 
?>

<main class="auth-container">
    <div class="auth-card" style="max-width: 900px; padding: 4rem;">
        <h2>USER PROFILE</h2>
        
        <form action="update_profile.php" method="POST">
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
</main>

<?php include 'includes/footer.php'; ?>