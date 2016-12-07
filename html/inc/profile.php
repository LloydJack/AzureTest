<?php
include("scripts/dbconnect.php");
include("scripts/header_l2.php");
?>
<?php

$profileUsername = $params['profileUsername'];
$username = $_SESSION['username'];


$sql = "SELECT * FROM users WHERE username='$profileUsername'";
$result = $db->query($sql);
//Mysql_num_row is counting table row
$count = mysqli_num_rows($result);


if ($count == 1) {
    $row = mysqli_fetch_object($result);//fetch object
    $searchedUsername = $row->username;
    $bio = $row->bio;
    $hasProfile = $row->hasProfile;
    $userlevel = $row->userlevel;
    $profile_photo_ext = $row->profile_photo_ext;

}

if ($searchedUsername != $profileUsername) {
    header("location:../photographers");
}


if ($_SESSION['userlevel'] == '4' && $username != $profileUsername) { ?>

    <form action="../banUser" method="post">
        <input type="hidden" class="form-control" name="profileUsername"
               value="<?php echo $profileUsername ?>">
        <button type="submit" class="btn btn-default">Ban user</button>

    </form>
    <?php
}

if ($hasProfile == 0){

if ($username == $profileUsername){
    ?>


    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form data-toggle="validator" role="form" action="../uploadProfilePhoto" method="post"
              enctype="multipart/form-data">
            <div class="form-group">
                <label for="usr">Please upload a profile picture:</label>
                <input type="file" name="file"/>
                <input type="submit"/>
        </form>
    </div>
    </form>
    <?php if (!empty($profile_photo_ext)) {
        echo "<img src='https://s3.amazonaws.com/{$profile_photo_ext}' class=\"img-rounded\"  width=\"125\" height=\"114px\">";
    }; ?>
    <form data-toggle="validator" role="form" action="../addProfile" method="post">
        <div class="form-group">
            <label for="usr">Please insert some information about yourself:</label>
            <input type="text" class="form-control" name="bio"
                   value="">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    </div>
    <div class="col-md-4"></div>


    </div><?php
}
else {
echo "User has not created a profile";

$sql = "SELECT * FROM photos WHERE username = '" . $profileUsername . "'";
$userResult = $db->query($sql);

if (!Empty($userResult)) {
    echo "<h2>Photos</h2>";
    while ($row = $userResult->fetch_array()) {
        $photo_id = $row['id'];
        $photo_name = $row['photo_name'];
        $s3_ext = $row['s3_ext'];


        echo "<a href='../photo/{$photo_id}'><img src='https://s3.amazonaws.com/{$s3_ext}'  width=\"250\" height=\"228px\">  ";
        echo "<li><a href='../photo/{$photo_id}'>{$photo_name}</a></li>";

    }
} ?><?php
}
}

else if ($userlevel == 0){
echo "This user is banned";
}

else {
?>

<div class="container">
    <div class="row">
        <h1 class="text-center">
            <?php echo $profileUsername; ?></h1>
        <?php if (!Empty($profile_photo_ext)) {

            echo "<img src='https://s3.amazonaws.com/{$profile_photo_ext}' class=\"img-rounded\"  width=\"250\" height=\"228px\">";
        }; ?>
        <p>Bio: <?php echo $bio; ?></p>

        <?php
        $sql = "SELECT * FROM photos WHERE username = '" . $profileUsername . "'";
        $userResult = $db->query($sql);

        if (!Empty($userResult)) {
            echo "<h2>Photos</h2>";
            while ($row = $userResult->fetch_array()) {
                $photo_id = $row['id'];
                $photo_name = $row['photo_name'];
                $s3_ext = $row['s3_ext'];


                echo "<a href='../photo/{$photo_id}'><img src='https://s3.amazonaws.com/{$s3_ext}'   width=\"250\" height=\"228px\">  ";
                echo "<li><a href='../photo/{$photo_id}'>{$photo_name}</a></li>";

            }
        } ?>


        <?php
        if ($username == $profileUsername) {
            ?>


            <h4>Edit Profile</h4>
            <label>Choose a different profile picture:</label>
            <form action="../uploadProfilePhoto" method="post" enctype="multipart/form-data">

                <input type="file" name="file"/>
                <input type="submit"/>
            </form>
            <?php if (!empty($profile_photo_ext)) {
                echo "<img src='https://s3.amazonaws.com/{$profile_photo_ext}' class=\"img-rounded\"  width=\"125\" height=\"114px\">";
            }
        ;
            ?>
            <form action="../editProfile" method="post">

                <label for="usr">Bio:</label>
                <input type="text" class="form-control" name="bio"
                       value="<?php echo $bio ?>">


                <button type="submit" class="btn btn-default">Submit</button>

            </form>


            <?php
        }


        if ($_SESSION['userlevel'] == '4' && $username != $profileUsername) { ?>

            <form action="../banUser" method="post">
                <input type="hidden" class="form-control" name="profileUsername"
                       value="<?php echo $profileUsername ?>">
                <button type="submit" class="btn btn-default">Ban user</button>

            </form>
            <?php
        }

        }; ?>
    </div>
</div>

