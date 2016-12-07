<?php
include("scripts/dbconnect.php");
include("scripts/header.php");

// escape the input, removing everything that could be (html/javascript-) code
$bio = $db->real_escape_string(strip_tags($_POST["bio"], ENT_QUOTES));
$username = $db->real_escape_string(strip_tags($_SESSION["username"], ENT_QUOTES));
$update_has_profile = 1;

//Insert the user's information into the database using a prepared statement to protect from SQL injections
$stmt = new mysqli_stmt($db, "UPDATE users SET bio = ?, hasProfile = ? WHERE username = ?");
$stmt->bind_param("sis", $bio, $update_has_profile, $username);
$stmt->execute();


$str = 'location:profile/' . $username;
header($str);

