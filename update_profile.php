<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $new_fname = $_POST['fname'];
    $new_lname = $_POST['lname'];
    $user_id = $_SESSION['user_id'];

    // Prepare the data for Supabase
    $updateData = [
        'fname' => $new_fname,
        'lname' => $new_lname
    ];

    // Send PATCH request to Supabase
    $endpoint = "tbluser?id=eq." . $user_id;
    $response = supabase_request("PATCH", $endpoint, $updateData);

    if ($response['status'] >= 200 && $response['status'] < 300) {

        $_SESSION['fname'] = $new_fname;
        $_SESSION['lname'] = $new_lname;

        header("Location: profile.php?update=success");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: profile.php?update=error");
        exit();
    }
} else {
    // If someone tries to access this file directly without POSTing, send them home
    header("Location: index.php");
    exit();
}