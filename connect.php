<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connect = mysqli_connect('localhost', 'root', '', 'library');

    if(!$connect) {
        die('Error connect.php to data base');
    }