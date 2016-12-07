<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");

echo "
<main>
<div class=\"col-md-4\"></div>
<div class=\"col-md-4\">
<h2>Photographers</h2>

<ul>
";

//display all photographers with a link to their profile
$sql = "SELECT * FROM users WHERE userlevel > 2";
$userResult = $db->query($sql);
while($row = $userResult->fetch_array())
{
    $profileUsername = $row['username'];
    $username = $row['username'];
    
    echo "<li><a href='profile/{$profileUsername}'>{$username}</a></li>";
}
echo "
</main></div>
";

?>