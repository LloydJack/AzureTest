<?php

$db = new mysqli(
    "RDS ENDPOINT",
    "****",
    "*****",
    "***"
);
// test if connection was established, and print any errors
if (!$db) {
    die('Connect Error: ' . mysqli_connect_errno());
}
