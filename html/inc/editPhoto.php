<?php
include("scripts/dbconnect.php");
include("scripts/header.php");

// escape the input, removing everything that could be (html/javascript-) code
$price = $db->real_escape_string(strip_tags($_POST["price"], ENT_QUOTES));
$photo_name = $db->real_escape_string(strip_tags($_POST["photo_name"], ENT_QUOTES));
$meta_data = $db->real_escape_string(strip_tags($_POST["meta_data"], ENT_QUOTES));
$photo_id = $_POST["photo_id"];

//insert the updated photo name into the database using a prepared statement to protect from SQL injections
$stmt = new mysqli_stmt($db, "UPDATE photos SET photo_name = ?, meta_data = ? WHERE id = ?");
$stmt->bind_param("ssi", $photo_name, $meta_data, $photo_id);
$stmt->execute();

if (!Empty($price)) {

    $forSale = 1;
    //insert the updated photo price into the database using a prepared statement to protect from SQL injections
    $stmt = new mysqli_stmt($db, "UPDATE photos SET for_sale = ?, price = ? WHERE id = ?");
    $stmt->bind_param("isi", $forSale, $price, $photo_id);
    $stmt->execute();


}

//direct the user back to the photo with the newly editted photo details
$str = 'location:photo/' . $photo_id;
header($str);


