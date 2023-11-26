<?php
require_once 'db_connection.php';

// Read the encryption key from the file

function fetchData($tableName, $page = 1, $limit = 40)
{
    global $encryption_key;  // Make sure the encryption key is accessible inside the function
//    $encryption_key = trim(file_get_contents('/key/aes_key.txt'));
    $encryption_key = 'ã2è×¢"xÁý2M\~dîú¡±õ¯HCm{_';
    $conn = getConn();

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        die("Invalid table name");
    }

    $offset = ($page - 1) * $limit;

    // Check if session role is 'patient' and modify the SQL query
    session_start();
    if ($_SESSION['role'] == 'patient') {
        $patient_id = 38; // Assuming that patient_id is stored in session
        // Modify the SQL query to decrypt the data while fetching
        if ($tableName == 'Billing') {
            $sql = "SELECT * FROM {$tableName} JOIN Orders ON {$tableName}.order_id=Orders.id WHERE Orders.patient_id = {$patient_id} LIMIT {$limit} OFFSET {$offset}";
        } elseif ($tableName == 'Results') {
            $sql = "SELECT {$tableName}.id, order_id, AES_DECRYPT(report_url, '{$encryption_key}') as report_url, AES_DECRYPT(interpretation, '{$encryption_key}') as interpretation, AES_DECRYPT(reporting_pathologist, '{$encryption_key}') as reporting_pathologist FROM {$tableName} JOIN Orders ON {$tableName}.order_id=Orders.id WHERE Orders.patient_id = {$patient_id} LIMIT {$limit} OFFSET {$offset}";
        } elseif
        ($tableName == 'Orders') {
            $sql = "SELECT *, AES_DECRYPT(ordering_physician, '{$encryption_key}') as ordering_physician FROM {$tableName} WHERE patient_id={$patient_id} LIMIT {$limit} OFFSET {$offset}";
        } elseif
        ($tableName == 'Patients') {
            $sql = "SELECT *, AES_DECRYPT(name, '{$encryption_key}') as name, AES_DECRYPT(contact_info, '{$encryption_key}') as contact_info, AES_DECRYPT(insurance_details, '{$encryption_key}') as insurance_details, AES_DECRYPT(username, '{$encryption_key}') as username, AES_DECRYPT(password, '{$encryption_key}') as password FROM {$tableName} WHERE id={$patient_id} LIMIT {$limit} OFFSET {$offset}";
        } else {
            $sql = "SELECT * FROM {$tableName} LIMIT {$limit} OFFSET {$offset}";
        }
    } else {
        // Modify the SQL query to decrypt the data while fetching
        if ($tableName == 'Results') {
            $sql = "SELECT id, order_id, AES_DECRYPT(report_url, '{$encryption_key}') as report_url, AES_DECRYPT(interpretation, '{$encryption_key}') as interpretation, AES_DECRYPT(reporting_pathologist, '{$encryption_key}') as reporting_pathologist FROM {$tableName} LIMIT {$limit} OFFSET {$offset}";
        } elseif ($tableName == 'Staff') {
            $sql = "SELECT staff_id, AES_DECRYPT(name, '{$encryption_key}') AS name, role, AES_DECRYPT(contact_info, '{$encryption_key}') AS contact_info, AES_DECRYPT(username, '{$encryption_key}') AS username FROM {$tableName} LIMIT {$limit} OFFSET {$offset}";
        } elseif ($tableName == 'Patients') {
            $sql = "SELECT id, AES_DECRYPT(name, '{$encryption_key}') as name, dob, AES_DECRYPT(contact_info, '{$encryption_key}') as contact_info, AES_DECRYPT(insurance_details, '{$encryption_key}') as insurance_details, AES_DECRYPT(username, '{$encryption_key}') as username, AES_DECRYPT(password, '{$encryption_key}') as password FROM {$tableName} LIMIT {$limit} OFFSET {$offset}";
        } elseif ($tableName == 'Orders') {
            $sql = "SELECT id, patient_id, test_code, AES_DECRYPT(ordering_physician, '{$encryption_key}') as ordering_physician, order_date, status FROM {$tableName} LIMIT {$limit} OFFSET {$offset}";
        } else {
            $sql = "SELECT * FROM {$tableName} LIMIT {$limit} OFFSET {$offset}";
        }
    }

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $data;
}

function fetchCount($tableName)
{
    $conn = getConn();

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        die("Invalid table name");
    }

    if ($_SESSION['role'] == 'patient') {
        $patient_id = 38; // Assuming that patient_id is stored in session
        if ($tableName == 'Billing' || $tableName == 'Results') {
            $sql = "SELECT COUNT(*) AS total FROM {$tableName} JOIN Orders ON {$tableName}.order_id=Orders.id WHERE Orders.patient_id = {$patient_id}";
        } elseif ($tableName == 'Orders') {
            $sql = "SELECT COUNT(*) AS total FROM {$tableName} WHERE patient_id={$patient_id}";
        } elseif ($tableName == 'Patients') {
            $sql = "SELECT COUNT(*) AS total FROM {$tableName} WHERE id={$patient_id}";
        } else {
            $sql = "SELECT COUNT(*) AS total FROM {$tableName}";
        }
    } else {
        $sql = "SELECT COUNT(*) AS total FROM {$tableName}";
    }
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error . $tableName);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return ceil($row['total']);
}