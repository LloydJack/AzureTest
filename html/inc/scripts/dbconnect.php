<?php

$db = new mysqli(
    "phpmyadmin.cjqnwonnifqv.eu-west-1.rds.amazonaws.com",
    "phpMyAdmin",
    "securepassword1212",
    "userdatabase"
);
// test if connection was established, and print any errors
if (!$db) {
    die('Connect Error: ' . mysqli_connect_errno());
}