<?php
require_once 'fetch_data.php';

function generateTable($page = 1) {
    $tableName = 'Staff';
    $data = fetchData($tableName, $page);

    ob_start();

    echo '<table class="w-full whitespace-no-wrap">';
    echo '<thead>';
    echo '<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">';
    echo '<th class="px-4 py-3">Staff ID</th>';
    echo '<th class="px-4 py-3">Name</th>';
    echo '<th class="px-4 py-3">Role</th>';
    echo '<th class="px-4 py-3">Contact Info</th>';
    echo '<th class="px-4 py-3">Username</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">';

    foreach ($data as $row) {
        $roleClass = '';
        if ($row["role"] === 'Doctor') {
            $roleClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-300 text-green-900 dark:bg-green-700 dark:text-green-100';
        } else if ($row["role"] === 'Pharmacist') {
            $roleClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-300 text-blue-900 dark:bg-blue-500 dark:text-blue-100';
        } else if ($row["role"] === 'Nurse') {
            $roleClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-300 text-orange-900 dark:bg-orange-600 dark:text-orange-200';
        } else if ($row["role"] === 'Technician') {
            $roleClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-300 text-teal-900 dark:bg-teal-500 dark:text-teal-100';
        } else if ($row["role"] === 'Admin') {
            $roleClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-300 text-red-900 dark:bg-red-600 dark:text-red-200';
        }

        echo '<tr class="text-gray-700 dark:text-gray-400">';
        echo '<td class="px-4 py-3">'.$row["staff_id"].'</td>';
        echo '<td class="px-4 py-3">'.$row["name"].'</td>';
        echo '<td class="px-4 py-3"><span class="'.$roleClass.'">'.$row["role"].'</span></td>';
        echo '<td class="px-4 py-3">'.$row["contact_info"].'</td>';
        echo '<td class="px-4 py-3">'.$row["username"].'</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    $output = ob_get_clean();

    return $output;
}
function getCountTotal() {
    $tableName = 'Staff';
    return fetchCount($tableName);
}
