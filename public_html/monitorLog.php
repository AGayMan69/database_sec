<a href="logout.php">Log out</a>
<a href="appointments.php">appointments</a>

<?php
function array_tail($array, $n) {
    return array_slice($array, -$n);
}
$file = '/var/log/mysql/mysql.log';
$lines = array_tail(file($file), 100);
foreach ($lines as $line) {
    echo $line . "<br />";
}
?>
