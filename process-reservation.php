<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include 'includes/db.php';

    // Strictly ensure only logged-in Admins can run this file
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
        header("Location: login.php");
        exit();
    }

    // 2. Validate that both required parameters are present in the URL
    if (isset($_GET['id']) && isset($_GET['status'])) {
        $reservation_id = $_GET['id'];
        $status_action  = $_GET['status'];

        // Construct the target endpoint pointing to the specific row ID
        $endpoint = "tblreservation?id=eq." . urlencode($reservation_id);

        if ($status_action === 'approve') {
            // Build payload to flip your schema's boolean column to true
            $payload = ['isApproved' => true];
            $response = supabase_request("PATCH", $endpoint, $payload);
            
        } else if ($status_action === 'deny') {
            $response = supabase_request("DELETE", $endpoint);
            
        }

        // 3. Inspect response status codes to determine operational success
        if (isset($response['status']) && $response['status'] >= 200 && $response['status'] < 300) {
            header("Location: admin-dashboard.php?action=success");
            exit();
        } else {
            header("Location: admin-dashboard.php?action=error");
            exit();
        }
    } else {
        // Fallback security redirect if someone tries accessing the file without parameters
        header("Location: admin-dashboard.php");
        exit();
    }
?>