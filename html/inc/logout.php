<?php
session_start();
include ("scripts/dbconnect.php");
;
//remove all session associations
session_unset();

//direct the user to the home page
header("location:./");
?>
