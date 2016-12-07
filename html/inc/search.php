<?php
include("scripts/dbconnect.php");
include("scripts/header.php");


//$search = $params['searchInput'];
$search = $db->real_escape_string(strip_tags($_POST["searchinput"], ENT_QUOTES));

?>
<div class="col-md-4"></div>
<div class="col-md-4">

<?php
//$search = "";
//if (isset($_GET["searchinput"])) {
//$search = $mysqli->real_escape_string($_GET["searchinput"]);
//}
//$search_type = "";
//if (isset($_GET["search_type"])) {
//$search_type = $_GET["search_type"];
//} else {
//$search_type = "user";
//}


$search = "%" . $search . "%";

//search the user table in the databse using a prepared statement to protect against SQL injection
$query = "SELECT username FROM users WHERE username LIKE ?";
$stmt = new mysqli_stmt($db, $query);
if ($stmt) {
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $stmt->bind_result($username);
    while ($stmt->fetch()) {
        $user_search_results["data"][] = array(
            "username" => $username,

        );
    }
}

    //output each profile and username with a link
    if (!Empty($user_search_results)) {
        echo "<ul class='list-group'>  <h2>Users</h2>";
        foreach ($user_search_results["data"] as $key => $val):
            ?>
            <li>

                <a href="./<?php echo "profile/" . $val['username']; ?>">
                    <?php echo $val['username'] ?>
                </a>
            </li>
            <?php
        endforeach;
        echo "</ul>";
    }


//search the photo table in the databse using a prepared statement to protect against SQL injection
$query2 = "SELECT photo_name, id, s3_ext FROM photos WHERE photo_name LIKE ?";
$stmt2 = new mysqli_stmt($db, $query2);
if ($stmt2) {
    $stmt2->bind_param("s", $search);
    $stmt2->execute();
    $stmt2->bind_result($photo_name, $photo_id, $s3_ext);
    while ($stmt2->fetch()) {
        $photo_search_results["data"][] = array(
            "photo_name" => $photo_name,
            "photo_id" => $photo_id,
            "s3_ext" => $s3_ext,

        );
    }

    //output each image with a link to its page
    if (!Empty($photo_search_results)) {
        echo "<ul class='list-group'>  <h2>Photos</h2>";
        foreach ($photo_search_results["data"] as $key => $val):
            ?>
            <li>
                <?php echo "<a href='photo/{$val['photo_id']}'><img src='https://s3.amazonaws.com/{$val['s3_ext']}' class=\"img-rounded\" width=\"250\" height=\"228px\">"; ?></li>
            <a href="./<?php echo "photo/" . $val['photo_id']; ?>">
                <?php echo $val['photo_name'] ?>
            </a>
            </li>
            <?php
        endforeach;
        echo "</ul>";
    }
}

    ?></div>