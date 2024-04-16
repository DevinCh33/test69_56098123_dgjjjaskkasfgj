<?php
session_start(); // start session
session_destroy(); // destroy all the current sessions

$url = 'index.php';
header('Location: ' . $url); // redireted to index page
