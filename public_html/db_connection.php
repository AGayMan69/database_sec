<?php

function getConn()
{
    $servername = "mysql_server";
    $password = "1234";
    $dbname = "db_sec";

    if ($_SESSION['role'] == 'lab_staff') {
        $username = 'lab_staff';
    } elseif ($_SESSION['role'] == 'secretary') {
        $username = 'secretary';
    } elseif ($_SESSION['role'] == 'patient') {
        $username = 'patient';
    } elseif ($_SESSION['role'] == 'sysadmin') {
        $username = 'sysadmin';
    } else {
        die("Invalid role: " . $_SESSION['role']);
    }
// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

