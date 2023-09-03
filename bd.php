<?php
    $connect = mysqli_connect('localhost', 'root', '', 'library');

    if(!$connect) {
        die('Error connect.php to data base');
    }
