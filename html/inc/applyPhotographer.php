<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");


$user_id = $_POST["user_id"];

//Change the user's userlevel to display that they wish to be approved
$sql= "UPDATE users SET userlevel = '2' WHERE id = '" .$user_id."'";
$db->query($sql);



header("location:./");?>
