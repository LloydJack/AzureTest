<?php
include ("scripts/dbconnect.php");

// escape the input, removing everything that could be (html/javascript-) code
$photo_id = $db->real_escape_string(strip_tags($_POST["photo_id"], ENT_QUOTES));
$user_id = $db->real_escape_string(strip_tags($_POST["user_id"], ENT_QUOTES));
$comment = $db->real_escape_string(strip_tags($_POST["comment"], ENT_QUOTES));
$date = date("Y-m-d H:i:s");

//Insert the comment with it's information into the database using a prepared statement to protect from SQL injections
$stmt = new mysqli_stmt($db, "INSERT INTO comments (user_id, comment, date, photo_id) VALUES (?,?,?,?);");
$stmt->bind_param("isss", $user_id, $comment, $date, $photo_id);
$stmt->execute();


//direct the user back to the photo with the newly added comment
header("location:photo/{$photo_id}");

?>
