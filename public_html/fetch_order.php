<?php
require_once 'fetch_data.php';

function generateTable($page = 1) {
    $tableName = 'Orders';
    $data = fetchData($tableName, $page);

    ob_start();

    echo '<table class="w-full whitespace-no-wrap">';
    echo '<thead>';
    echo '<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">';
    echo '<th class="px-4 py-3">ID</th>';
    echo '<th class="px-4 py-3">Patient ID</th>';
    echo '<th class="px-4 py-3">Test Code</th>';
    echo '<th class="px-4 py-3">Ordering Physician</th>';
    echo '<th class="px-4 py-3">Order Date</th>';
    echo '<th class="px-4 py-3">Status</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">';

    foreach ($data as $row) {
        $statusClass = '';
        if ($row["status"] === 'Processed') {
            $statusClass = 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100 ';
        } else if ($row["status"] === 'Processing') {
            $statusClass = 'px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-orange-500 dark:text-orange-100';
        } else if ($row["status"] === 'Ordered') {
            $statusClass = 'px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-500 dark:text-blue-100 ';
        }

        echo '<tr class="text-gray-700 dark:text-gray-400">';
        echo '<td class="px-4 py-3">'.$row["id"].'</td>';
        echo '<td class="px-4 py-3">'.$row["patient_id"].'</td>';
        echo '<td class="px-4 py-3">'.$row["test_code"].'</td>';
        echo '<td class="px-4 py-3">'.$row["ordering_physician"].'</td>';
        echo '<td class="px-4 py-3">'.$row["order_date"].'</td>';
        echo '<td class="px-4 py-3"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full '.$statusClass.'">'.$row["status"].'</span></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    $output = ob_get_clean();

    return $output;
}

function getCountTotal() {
    $tableName = 'Orders';
    return fetchCount($tableName);
}
