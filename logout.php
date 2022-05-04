<?php
require_once 'include/errors.php';
session_start();
 
session_unset();
session_destroy();

// Pass along query strings
// Example:
// logout.php?registered=true -> login.php?registered=true

$location = 'login.php';

if (isset($_GET['location']) && !empty($_GET['location'])) {
    $location = $_GET['location'];
}

if (empty($_SERVER['QUERY_STRING'])) {
    $query = '';
}
else {
    $query = '?' . $_SERVER['QUERY_STRING'];
}

header("location: " . $location . $query);
exit;
?>