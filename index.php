<?php

session_start();

ini_set('error_reporting', E_ALL | E_NOTICE | E_STRICT);
ini_set('display_errors', '1');
ini_set('track_errors', 'On');

try {
    if (!isset($_SESSION["user"])) {
        require "source/login.php";
    } else {
        require "source/dashboard.php";
    }
} catch (Exception $e) {
	echo "<pre>" . print_r($e,1) . "</pre>";
}