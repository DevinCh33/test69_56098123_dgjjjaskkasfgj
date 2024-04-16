<?php
session_start(); // start session
session_destroy(); // destroy all the current sessions

$url = 'index.php';
header('Location: http://localhost/lfsc/seller/index.php'); // redireted to index page
