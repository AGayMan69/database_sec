<?php
function generationPaginationList($totalRecords, $page) {
    $totalPages = ceil($totalRecords / 40);

    ob_start();
    echo '<div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">';
    echo '<span class="flex items-center col-span-3"> Showing ' . (($page-1)*40 + 1) . '-' . min($totalRecords, $page*40) . ' of ' . $totalRecords . '</span>';
    echo '<span class="col-span-2"></span>';
    echo '<span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">';
    echo '<nav aria-label="Table navigation">';
    echo '<ul class="inline-flex items-center">';

    if ($page > 1) {
        // Previous Page Link
        echo '<li><a href="?page=' . ($page - 1) . '" class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple" aria-label="Previous">...</a></li>';
    }

    for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++) {
        if ($i == $page) {
            // Current Page Link
            echo '<li><a href="?page=' . $i . '" class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple">' . $i . '</a></li>';
        } else {
            // Other Page Links
            echo '<li><a href="?page=' . $i . '" class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">' . $i . '</a></li>';
        }
    }

    if ($page < $totalPages) {
        // Next Page Link
        echo '<li><a href="?page=' . ($page + 1) . '" class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple" aria-label="Next">...</a></li>';
    }

    echo '</ul>';
    echo '</nav>';
    echo '</span>';
    echo '</div>';
    $output = ob_get_clean();
    return $output;
}
