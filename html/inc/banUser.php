<?php
include ("scripts/dbconnect.php");

$usernameToBan = $_POST["profileUsername"];

//set the userlevel to prohibit the user from accessing photoShare
$sql= "UPDATE users SET userlevel = 0 WHERE username = '" .$usernameToBan."'";
$db->query($sql);


header("location:photographers");

?>
