<?php
function checkCredentials($username, $password) {
    // Define the credentials for the users
    $users = [
        'lab_staff' => ['username' => 'lab_staff', 'password' => '1234'],
        'secretary' => ['username' => 'secretary', 'password' => '1234'],
        'patient' => ['username' => 'patient', 'password' => '1234'],
        'sysadmin' => ['username' => 'sysadmin', 'password' => '1234']
    ];

    foreach ($users as $role => $credentials) {
        if ($username == $credentials['username'] && $password == $credentials['password']) {
            return $role;
        }
    }

    return false;
}

// Start the session
session_start();

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];

// Check the credentials
$role = checkCredentials($username, $password);

if ($role) {
    // Set the role in the session
    $_SESSION['role'] = $role;

    // Redirect to the next page
    // Redirect to the appropriate page based on the role
    switch ($role) {
        case 'lab_staff':
            header('Location: results.php');
            exit; // Important to prevent further execution of script
        case 'secretary':
            header('Location: appointments.php');
            exit;
        case 'patient':
            header('Location: orders.php');
            exit;
        case 'sysadmin':
            header('Location: monitorLog.php');
            exit;
    }
} else {
    // Invalid credentials
    header('Location: index.php?error=Invalid credentials');
}