<?php
    include 'includes/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        if (empty($email) || empty($fname) || empty($lname) || empty($_POST['password'])) {
            echo "<p>All fields are required. Please fill out the form completely.</p>";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p>Invalid email format.</p>";
            return;
        }

        $userData = [
            'email' => $email,
            'fname' => $fname,
            'lname' => $lname,
            'isStudent' => true,
            'isFaculty' => false,
            'isAdmin' => false,
            'password' => $hashed_password
        ];
        
        $result = supabase_request("POST", "tbluser", $userData);
        
        if ($result['status'] >= 200 && $result['status'] < 300) {
            header("Location: login.php?signup=success");
            exit();
        } else {
            echo "Error creating account. Code: " . $result['status'];
        }
    }
?>

<?php 
    $page_type = 'landing';
    include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <main class="auth-container">
        <div class="auth-card">
            <h2>SIGN UP</h2>
            <form id="signupForm" method="POST" action="signup.php">
                <div class="form-group">
                    <label for="email">INSTITUTIONAL EMAIL</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="username">FIRST NAME</label>
                    <input type="text" id="firstname" name="fname" required>
                </div>
                <div class="form-group">
                    <label for="username">LAST NAME</label>
                    <input type="text" id="lastname" name="lname" required>
                </div>
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">CONFIRM PASSWORD</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <!-- <a href="login.php" class="btn-auth-submit" style="text-decoration: none; display: block; text-align: center;">SIGN UP</a> -->
                <button type="submit" class="btn-auth-submit" style="width: 100%; border: none; cursor: pointer; display: block; text-align: center;">
                    SIGN UP
                </button>
            </form>
            <div class="auth-footer-link">
                Have an account? <a href="login.php">Log In</a>
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
