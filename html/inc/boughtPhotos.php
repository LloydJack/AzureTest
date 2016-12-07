<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");
echo "
<main>
<div class=\"col-md-4\"></div>
<div class=\"col-md-4\">
<h2>Bought Photos</h2>
";

$userid = $_SESSION['id'];

//Select all photos bought by the logged in user from the RDS database
$sql = "SELECT * FROM boughtPhotos WHERE userid = '" . $userid . "'";
$userResult = $db->query($sql);

if (!Empty($userResult)) {

    //Display all bought photos with their name
    while ($row = $userResult->fetch_array()) {
        $photo_id = $row['id'];
        $photo_name = $row['photo_name'];
        $s3_ext = $row['s3_ext'];


        echo "<img src='https://s3.amazonaws.com/{$s3_ext}'  width=\"250\" height=\"228px\">  ";
        echo "<li>{$photo_name}</a></li>";

    }

} ?></div>