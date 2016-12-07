<?php
include ("scripts/dbconnect.php");

$photo_id = $_POST["photoID"];

//delete the selected photo from the database
$sql= "DELETE FROM photos WHERE id = '" .$photo_id."'";
$db->query($sql);

header("location:photoList");

?>
