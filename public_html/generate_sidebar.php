<?php
function generateSidebar() {
    // Define the sidebar items for each role
    $roleItems = [
        'lab_staff' => ['Staff', 'Orders', 'Results', 'TestsCatalog'],
        'secretary' => ['Patients', 'Billing', 'Results', 'Appointments', 'Staff', 'Order'],
        'patient' => ['Orders', 'Billing', 'Patients', 'Results', 'TestsCatalog'],
        'sysadmin' => ['Monitor Log', 'Appointments', 'Billing', 'Orders', 'Patients', 'Results', 'Staff', 'TestsCatalog']
    ];

    // Get the role from the session
    $role = $_SESSION['role'];

    $sidebarItems = [
        ['name' => 'Monitor Log', 'link' => 'monitorLog.php', 'icon' => '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16px" height="16px"><path d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"/></svg>'],
        ['name' => 'Appointments', 'link' => 'appointments.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m4-2v6m4 2v2a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 013-3h7a5 5 0 015 5v2z" /></svg>'],
        ['name' => 'Billing', 'link' => 'billing.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M4 16h16M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" /></svg>'],
        ['name' => 'Orders', 'link' => 'orders.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l1.4 2.8M7 13h10l4-8H5.4M7 13L5.4 15.7a2 2 0 01-1.8 1.1H1m6-2v2a2 2 0 002 2h10a2 2 0 002-2v-2m-6 2a2 2 0 11-4 0" /></svg>'],
        ['name' => 'Patients', 'link' => 'patients.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v2H2v-2h5m5-14a3 3 0 11-6 0 3 3 0 016 0zm6 0a3 3 0 11-6 0 3 3 0 016 0zM9 22v-4a4 4 0 00-4-4H5a4 4 0 00-4 4v4m14 0v-4a4 4 0 014-4h1a4 4 0 014 4v4" /></svg>'],
        ['name' => 'Results', 'link' => 'results.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2v8H3V13zm6 0h2v8H9V13zm6 0h2v8h-2V13zm6 0h2v8h-2V13z" /></svg>'],
        ['name' => 'Staff', 'link' => 'staff.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm8 9a3 3 0 11-6 0 3 3 0 016 0zm6-9a3 3 0 11-6 0 3 3 0 016 0zM9 22v-4a4 4 0 00-4-4H5a4 4 0 00-4 4v4m14 0v-4a4 4 0 014-4h1a4 4 0 014 4v4" /></svg>'],
        ['name' => 'TestsCatalog', 'link' => 'testsCatalog.php', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-6 0a3 3 0 00-3 3v6a3 3 0 006 0v-6a3 3 0 00-3-3zm6 0a3 3 0 00-3 3v6a3 3 0 006 0v-6a3 3 0 00-3-3z" /></svg>']
    ];

    // If the role is 'sysadmin', show all sidebar items
    if ($role == 'sysadmin') {
        $roleSidebarItems = array_column($sidebarItems, 'name');
    } else {
        // Get the sidebar items for the role
        $roleSidebarItems = isset($roleItems[$role]) ? $roleItems[$role] : [];
    }
    // Filter the sidebar items based on the role
    $sidebarItems = array_filter($sidebarItems, function ($item) use ($roleSidebarItems) {
        return in_array($item['name'], $roleSidebarItems);
    });
    // Get the current page name
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Remove parameters from the current page name
    $currentPage = strtok($currentPage, '?');

    ob_start();

    // Start the first ul element
    echo '<ul class="mt-6">';

    // Loop through the sidebar items
    foreach ($sidebarItems as $index => $item) {
        // Determine if this item should be highlighted
        $isActive = ($currentPage == $item['link']) ? true : false;

        // If this is the second item, close the first ul and start a new one
        if ($index == 1) {
            echo '</ul><ul>';
        }

        echo '<li class="relative px-6 py-3">';
        if ($isActive) {
            echo '<span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>';
        }

        // Add padding-right to the SVG icon
        $iconSVG = str_replace('<svg', '<svg style="padding-right: 5px;" width="16px" height="16px"', $item['icon']);

        echo '<a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 '
            . ($isActive ? 'dark:text-gray-100' : '') . '" href="' . $item['link'] . '">';
        echo $iconSVG . ' ' . $item['name'] . '</a></li>';
    }

    // Close the second ul
    echo '</ul>';
    $output = ob_get_clean();
    return $output;
}
