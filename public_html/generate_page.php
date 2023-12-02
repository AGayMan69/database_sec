<?php
session_start();

function generatePage($fetch_file, $page_name)
{
    require_once $fetch_file;
    require_once 'generate_pagination_list.php';
    require_once 'generate_sidebar.php';

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    $html = '<!DOCTYPE html>';
    $html .= '<html :class="{ \'theme-dark\': dark }" x-data="data()" lang="en">';
    $html .= generateHead();
    $html .= generateBody($page_name, $page);
    $html .= '</html>';

    echo $html;
}

function generateHead()
{
    return <<<EOD
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Clinic Dashboard</title>
    <link href="https://fstaff.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="./assets/css/tailwind.output.css"/>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="./assets/js/init-alpine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer></script>
    <script src="./assets/js/charts-lines.js" defer></script>
    <script src="./assets/js/charts-pie.js" defer></script>
</head>
EOD;
}

function array_tail($array, $n) {
    return array_slice($array, -$n);
}
function generateLog(){
    $file = '/var/log/mysql/mysql.log';
    $lines = array_tail(file($file), 100);
    $string = "";
    foreach ($lines as $line) {
        $parts = explode("\t", $line);
        $suspiciousKeywords = ['DROP', 'UNION', '--', '/*', '*/', 'xp_cmdshell', 'EXEC'];
        $timestamp = $parts[0];
        $id = $parts[1];
        $command = $parts[2];
        $argument = $parts[3];

        foreach ($suspiciousKeywords as $keyword) {
            if (stripos($argument, $keyword) !== false) {
                $string .= "<span class='text-red-600'>Alert: Suspicious query detected at $timestamp by user $id: $argument</span><br />";
            }
        }
        $string .= $line . "<br />";
    }
    return $string;
}

function generateBody($page_name, $page)
{
    $sidebar = generateSidebar();

    if ($page_name == 'Monitor Log') {
        $log = generateLog();
        return <<<EOD
<body>
<div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow - hidden': isSideMenuOpen }">
    <aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
        <div class="py-4 text-gray-500 dark:text-gray-400">
            <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">Clinic</a>
            $sidebar
        </div>
    </aside>
   <div
            x-show="isSideMenuOpen"
            x-transition:enter="transition ease-in-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in-out duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    ></div>
    <div class="flex flex-col flex-1 w-full">
        <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
            <div
                    class="container flex items-center justify-end h-full px-6 mx-auto text-purple-600 dark:text-purple-300"
            >
                <button
                        class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                        @click="toggleSideMenu"
                        aria-label="Menu"
                >
                    <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"
                        ></path>
                    </svg>
                </button>
                <ul class="flex items-center flex-shrink-0 space-x-6 ml-auto">
                    <!-- Profile menu -->
                    <li class="relative">
                        <button
                                class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none"
                                @click="toggleProfileMenu"
                                @keydown.escape="closeProfileMenu"
                                aria-label="Account"
                                aria-haspopup="true"
                        >
                            <img
                                    class="object-cover w-8 h-8 rounded-full"
                                    src="https://media.istockphoto.com/id/1202490554/vector/person-gray-photo-placeholder-man.jpg?s=612x612&w=0&k=20&c=KyXtDhRIFdY-xFnyc_19UEK0pY3PLz2R6Bpv--VPYwo="
                                    alt=""
                                    aria-hidden="true"
                            />
                        </button>
                        <template x-if="isProfileMenuOpen">
                            <ul
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    @click.away="closeProfileMenu"
                                    @keydown.escape="closeProfileMenu"
                                    class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                                    aria-label="submenu"
                            >
                                <li class="flex">
                                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="logout.php">
                                        <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Log out</span>
                                    </a>
                                </li>
                            </ul>
                        </template>
                    </li>
                </ul>
            </div>
        </header> 
        <main class="h-full overflow-y-auto">
            <div class="container px-6 mx-auto grid">
                <h2
                        class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                    $page_name
                </h2>
                <div class="w-full overflow-hidden rounded-lg shadow-xs">
                    <div class="w-full overflow-x-auto text-white">
                    $log
                    </div>
                </div>
            </div>
        </main>    
    </div>
</div>
</body>
EOD;
    } else {
        $table = generateTable($page);
        $pagination = generationPaginationList(getCountTotal(), $page);
        return <<<EOD
<body>
<div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow - hidden': isSideMenuOpen }">
    <aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
        <div class="py-4 text-gray-500 dark:text-gray-400">
            <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">Clinic</a>
            $sidebar
        </div>
    </aside>
   <div
            x-show="isSideMenuOpen"
            x-transition:enter="transition ease-in-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in-out duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    ></div>
    <div class="flex flex-col flex-1 w-full">
        <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
            <div
                    class="container flex items-center justify-end h-full px-6 mx-auto text-purple-600 dark:text-purple-300"
            >
                <button
                        class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                        @click="toggleSideMenu"
                        aria-label="Menu"
                >
                    <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"
                        ></path>
                    </svg>
                </button>
                <ul class="flex items-center flex-shrink-0 space-x-6 ml-auto">
                    <!-- Profile menu -->
                    <li class="relative">
                        <button
                                class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none"
                                @click="toggleProfileMenu"
                                @keydown.escape="closeProfileMenu"
                                aria-label="Account"
                                aria-haspopup="true"
                        >
                            <img
                                    class="object-cover w-8 h-8 rounded-full"
                                    src="https://media.istockphoto.com/id/1202490554/vector/person-gray-photo-placeholder-man.jpg?s=612x612&w=0&k=20&c=KyXtDhRIFdY-xFnyc_19UEK0pY3PLz2R6Bpv--VPYwo="
                                    alt=""
                                    aria-hidden="true"
                            />
                        </button>
                        <template x-if="isProfileMenuOpen">
                            <ul
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    @click.away="closeProfileMenu"
                                    @keydown.escape="closeProfileMenu"
                                    class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                                    aria-label="submenu"
                            >
                                <li class="flex">
                                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="logout.php">
                                        <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Log out</span>
                                    </a>
                                </li>
                            </ul>
                        </template>
                    </li>
                </ul>
            </div>
        </header> 
        <main class="h-full overflow-y-auto">
            <div class="container px-6 mx-auto grid">
                <h2
                        class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                    $page_name
                </h2>
                <!-- New Table -->
                <div class="w-full overflow-hidden rounded-lg shadow-xs">
                    <div class="w-full overflow-x-auto">
                        $table
                    </div>
                    $pagination
                </div>
            </div>
        </main>    
    </div>
</div>
</body>
EOD;
    }
}