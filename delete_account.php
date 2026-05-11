<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Send DELETE request to Supabase
    $endpoint = "tbluser?id=eq." . $user_id;
    $response = supabase_request("DELETE", $endpoint);
    
    if ($response['status'] >= 200 && $response['status'] < 300) {
        // Destroy session and redirect to login
        session_destroy();
        header("Location: signup.php?message=Account+deleted+successfully");
        exit();
    } else {
        // Handle error
        echo "Error deleting account. Please try again later.";
    }
} else {
    header("Location: profile.php");
    exit();
}
?>
