<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include 'includes/db.php';

    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role = $_POST['role'];
            
            $isStudent = ($role === 'student');
            $isFaculty = ($role === 'faculty');

            // Step 1: Base Account insertion payload
            $userData = [
                'email' => $email,
                'fname' => $fname,
                'lname' => $lname,
                'password' => $hashed_password,
                'isStudent' => $isStudent,
                'isFaculty' => $isFaculty,
                'isAdmin' => false 
            ];

            $userResponse = supabase_request("POST", "tbluser", $userData);

            if ($userResponse['status'] >= 200 && $userResponse['status'] < 300) {
                $newUser = $userResponse['data'];
                $newUserId = isset($newUser[0]['id']) ? $newUser[0]['id'] : null;

                if (!$newUserId) {
                    $fetchResponse = supabase_request("GET", "tbluser?email=eq." . urlencode($email) . "&select=id");
                    $newUserId = $fetchResponse['data'][0]['id'];
                }

                if ($isStudent) {
                    $studentData = [
                        'uid'     => $newUserId, // Changed key from 'id' to 'uid'
                        'program' => $_POST['program'],
                        'year'    => intval($_POST['year']) 
                    ];
                    $metaResponse = supabase_request("POST", "tblstudent", $studentData);
                } else if ($isFaculty) {
                    $facultyData = [
                        'uid'        => $newUserId, // Changed key from 'id' to 'uid'
                        'department' => $_POST['department']
                    ];
                    $metaResponse = supabase_request("POST", "tblfaculty", $facultyData);
                }

                header("Location: login.php?signup=success");
                exit();
            } else {
                $error = "Error writing account profile credentials.";
            }
        }
    }

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
                    <label for="firstname">FIRST NAME</label>
                    <input type="text" id="firstname" name="fname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">LAST NAME</label>
                    <input type="text" id="lastname" name="lname" required>
                </div>

                <div class="form-group">
                    <label>I AM A:</label>
                    <div style="display: flex; gap: 30px; padding: 0.5rem 1rem;">
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--text-dark); font-size: 1.1rem;">
                            <input type="radio" name="role" value="student" checked onclick="toggleRoleFields('student')" style="width: auto; margin: 0;"> Student
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--text-dark); font-size: 1.1rem;">
                            <input type="radio" name="role" value="faculty" onclick="toggleRoleFields('faculty')" style="width: auto; margin: 0;"> Faculty
                        </label>
                    </div>
                </div>

                <div id="student-fields" style="display: block; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="program">PROGRAM / COURSE</label>
                        <input type="text" id="program" name="program" placeholder="e.g., BSCS">
                    </div>
                    <div class="form-group">
                        <label for="year">YEAR LEVEL</label>
                        <div style="position: relative; width: 100%;">
                            <select id="year" name="year" class="custom-dropdown">
                                <option value="" disabled selected hidden>Select your year level</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                                <option value="5">5th Year</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="faculty-fields" style="display: none; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="department">DEPARTMENT</label>
                        <input type="text" id="department" name="department" placeholder="e.g., College of Computer Studies">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">CONFIRM PASSWORD</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn-auth-submit">
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
    <script src="js/signup.js"></script>

    <!-- Signup Confirmation Modal -->
    <div id="signupModal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <h3 class="custom-modal-title">Confirm Your Info</h3>
            <div class="custom-modal-body" id="signupModalBody">
            </div>
            <div class="custom-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeSignupModal()">Edit</button>
                <button type="button" class="btn-modal-confirm" onclick="confirmSignup()">Submit</button>
            </div>
        </div>
    </div>

    <script>
        const signupForm = document.getElementById('signupForm');
        const signupModal = document.getElementById('signupModal');
        const signupModalBody = document.getElementById('signupModalBody');

        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const fname = document.getElementById('firstname').value;
            const lname = document.getElementById('lastname').value;
            const role = document.querySelector('input[name="role"]:checked').value;
            
            let extraInfo = '';
            if (role === 'student') {
                const program = document.getElementById('program').value;
                const year = document.getElementById('year').options[document.getElementById('year').selectedIndex]?.text || '';
                extraInfo = `<p>Program: <span>${program}</span></p>
                             <p>Year Level: <span>${year}</span></p>`;
            } else if (role === 'faculty') {
                const department = document.getElementById('department').value;
                extraInfo = `<p>Department: <span>${department}</span></p>`;
            }

            signupModalBody.innerHTML = `
                <p>Email: <span>${email}</span></p>
                <p>Name: <span>${fname} ${lname}</span></p>
                <p>Role: <span style="text-transform: capitalize;">${role}</span></p>
                ${extraInfo}
            `;
            
            signupModal.classList.add('active');
        });

        function closeSignupModal() {
            signupModal.classList.remove('active');
        }

        function confirmSignup() {
            signupForm.submit();
        }
    </script>
</body>
</html>
