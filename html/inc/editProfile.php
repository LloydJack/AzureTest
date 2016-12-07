<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");

// escape the input, removing everything that could be (html/javascript-) code
$bio = $db->real_escape_string(strip_tags($_POST["bio"], ENT_QUOTES));
$username = $_SESSION['username'];


//insert the updated bio into the database using a prepared statement to protect from SQL injections
$stmt = new mysqli_stmt($db, "UPDATE users SET bio = ? WHERE username = ?");
$stmt->bind_param("ss", $bio, $username);
$stmt->execute();

//direct the user back to the photo with the newly editted profile information
$str = 'location:profile/' . $username;
header($str);


