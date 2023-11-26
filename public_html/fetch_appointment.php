<?php
require_once 'fetch_data.php';

function generateTable($page = 1) {
    $tableName = 'Appointments';
    $data = fetchData($tableName, $page);

    ob_start();

    echo '<table class="w-full whitespace-no-wrap">';
    echo '<thead>';
    echo '<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">';
    echo '<th class="px-4 py-3">ID</th>';
    echo '<th class="px-4 py-3">Patient ID</th>';
    echo '<th class="px-4 py-3">Appointment Date</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">';

    foreach ($data as $row) {
        echo '<tr class="text-gray-700 dark:text-gray-400">';
        echo '<td class="px-4 py-3">'.$row["id"].'</td>';
        echo '<td class="px-4 py-3">'.$row["patient_id"].'</td>';
        echo '<td class="px-4 py-3">'.$row["appointment_date"].'</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    $output = ob_get_clean();

    return $output;
}

function getCountTotal() {
    $tableName = 'Appointments';
    return fetchCount($tableName);
}
