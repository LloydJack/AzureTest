<?php
include ("scripts/dbconnect.php");

$username = $_POST["Approve_Photographer"];
$userlevel = 3;

//Insert the updated userlevel into the database using a prepared statement to protect from SQL injections
$stmt = new mysqli_stmt($db, "UPDATE users SET userlevel = ? WHERE username = ?");
$stmt->bind_param("is", $userlevel, $username);
$stmt->execute();


header("location:admin");



?>