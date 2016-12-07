<?php
include ("scripts/dbconnect.php");

$comment_id = $_POST["id"];
$photo_id = $_POST["photo_id"];

//delete the selected comment from the database
$sql= "DELETE FROM comments WHERE id = '" . $comment_id ."'";
$db->query($sql);

header("location:photo/{$photo_id}");

?>
