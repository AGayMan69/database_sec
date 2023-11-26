<?php
require_once 'fetch_data.php';
function generateTable($page = 1)
{
    require_once 'fetch_data.php';

    $tableName = 'Billing';
    $data = fetchData($tableName, $page);

    ob_start(); // Start output buffering

    echo '<table class="w-full whitespace-no-wrap">';
    echo '<thead>';
    echo '<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">';
    echo '<th class="px-4 py-3">ID</th>';
    echo '<th class="px-4 py-3">Order ID</th>';
    echo '<th class="px-4 py-3">Billed Amount</th>';
    echo '<th class="px-4 py-3">Payment Status</th>';
    echo '<th class="px-4 py-3">Insurance Claim Status</th>';
    // Add a date field if exists
    // echo '<th class="px-4 py-3">Date</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">';

    foreach ($data as $row) {
        echo '<tr class="text-gray-700 dark:text-gray-400">';
        echo '<td class="px-4 py-3">'.$row["id"].'</td>';
        echo '<td class="px-4 py-3">'.$row["order_id"].'</td>';
        echo '<td class="px-4 py-3 text-sm">$'.number_format($row["billed_amount"], 2).'</td>';

        $paymentStatusClass = $row["payment_status"] === 'Paid' ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100' : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100';
        echo '<td class="px-4 py-3 text-xs"><span class="'.$paymentStatusClass.'">'.$row["payment_status"].'</span></td>';

        $insuranceClaimStatusClass = $row["insurance_claim_status"] === 'Claimed' ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100' : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100';
        echo '<td class="px-4 py-3 text-xs"><span class="'.$insuranceClaimStatusClass.'">'.$row["insurance_claim_status"].'</span></td>';

        // Format and echo the date field if exists
        // $date = date("m/d/Y", strtotime($row["date_field"]));
        // echo '<td class="px-4 py-3 text-sm">'.$date.'</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    $output = ob_get_clean(); // Collect output and clean the buffer

    return $output; // Return the HTML table as a string
}

function getCountTotal() {
    $tableName = 'Billing';
    return fetchCount($tableName);
}
