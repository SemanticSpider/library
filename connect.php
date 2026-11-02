<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
/**
 * Enter your login and password
 */
$login = 'login';
$password = 'password';
$connect = mysqli_connect('localhost', $login, $password, 'library');

if (!$connect) {
    die('Error connect.php to data base');
}
