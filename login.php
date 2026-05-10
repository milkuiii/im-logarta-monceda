<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include 'includes/db.php';

    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email_input = $_POST['email'];
        $password_input = $_POST['password'];

        // 1. Fetch user from Supabase by email
        $endpoint = "tbluser?email=eq." . urlencode($email_input) . "&select=*";
        $response = supabase_request("GET", $endpoint);
        
        // Handle the new response format (data and status)
        $userData = $response['data'];

        if (!empty($userData)) {
            $user = $userData[0]; 

            // 2. Verify the hashed password
            if (password_verify($password_input, $user['password'])) {
                // 3. Success! Save user info to the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                $_SESSION['isStudent'] = $user['isStudent'];

                header("Location: view-books.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Email not found.";
        }
    }

    $page_type = 'landing';
    include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReServe - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="auth-container">
        <div class="auth-card">
            <h2>LOGIN</h2>
            <?php if($error): ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form id="loginForm" method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">INSTITUIONAL EMAIL</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div style="text-align: right; margin-top: -1rem; margin-bottom: 2rem;">
                    <a href="#" style="color: var(--primary-red); text-decoration: none; font-weight: 700;">Forgot your password?</a>
                </div>
                <!-- <a href="index.php" class="btn-auth-submit" style="text-decoration: none; display: block; text-align: center;">LOGIN</a> -->
                <button type="submit" class="btn-auth-submit" style="width: 100%; border: none; cursor: pointer; display: block; text-align: center;">LOGIN</button>
            </form>
            <div class="auth-footer-link">
                No account yet? <a href="signup.php">Sign up here</a>
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

