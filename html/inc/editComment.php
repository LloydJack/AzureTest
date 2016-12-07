<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");

// escape the input, removing everything that could be (html/javascript-) code
$edittedComment = $db->real_escape_string(strip_tags($_POST["editComment"], ENT_QUOTES));
$commentID = $_POST["id"];
$photo_id = $_POST["photo_id"];


//Insert the editted comment into the database using a prepared statement to protect from SQL injections
$stmt = new mysqli_stmt($db, "UPDATE comments SET comment = ? WHERE id = ?");
$stmt->bind_param("si", $edittedComment, $commentID);
$stmt->execute();


//direct the user back to the photo with the newly editted comment
header("location:photo/{$photo_id}");?>


