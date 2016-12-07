<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<?php

include("scripts/dbconnect.php");
include("scripts/header_l2.php");


$photoID = $params['photoID'];
$username = $_SESSION["username"];
$userlevel = $_SESSION["userlevel"];
$userID = $_SESSION["id"];

//select all database information about the selected photo
$sql = "SELECT * FROM photos WHERE id='$photoID'";
$result = $db->query($sql);
//Mysql_num_row is counting table row
$count = mysqli_num_rows($result);

//if the statement finds one result i.e. there is one photo in the database with the provided ID
if ($count == 1) {
    $row = mysqli_fetch_object($result);//fetch object
    $queryPhotoID = $row->id;
    $photo_username = $row->username;
    $photo_name = $row->photo_name;
    $meta_data = $row->meta_data;
    $photo_ext = $row->ext;
    $for_sale = $row->for_sale;
    $price = $row->price;
    $s3_ext = $row->s3_ext;
}

//if the id provided in the url is not an id in the database, direct the user back to the photolist
if ($queryPhotoID != $photoID) {
    header("location:../photoList");
}


?>

<div class="container">
    <div class="col-md-6 col-md-offset-1 ">
        <h1 class="text-center">
            <?php
            //display the photo name and who uploaded the photo allowing the user to click on the username to visit the profile
            echo "{$photo_name} by <a href='../profile/{$photo_username}'>{$photo_username}"; ?></a></h1>


        <?php
        //display the photo and if it is for sale; display the price
        echo " <img src='https://s3.amazonaws.com/{$s3_ext}' width=\"500\" height=\"456px\">   "; ?><br>
        <p><?php if ($for_sale == 1) {

                echo "This photo is on sale for £{$price}";
            }; ?></p>

        <?php ?>


        <!-- Trigger the modal with a button -->
        <button type="button" data-toggle="modal" data-target="#metaDataModal">View Meta Data
        </button>

        <!-- Modal -->
        <div id="metaDataModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">View Meta Data</h4>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">

                                <label for="usr">Meta Data:</label>
                                <textarea readonly class="form-control" name="description" rows="20"
                                          cols="80"><?php echo $meta_data;
                                    ?></textarea>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                            </button>

                        </div>
                </div>
                </form>
            </div>
        </div>

        <br>
        <br>

        <!-- Trigger the modal with a button -->
        <button type="button" data-toggle="modal" data-target="#exifModal">View EXIF Data
        </button>

        <!-- Modal -->
        <div id="exifModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">View EXIF Data</h4>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">

                                <label for="usr">EXIF Data:</label>
                                <textarea readonly class="form-control" name="description" rows="20"
                                          cols="80"><?php

                                    //rea the meta data from the photo and output the data if the user wishes to view the information
                                    $exif = exif_read_data('https://s3.amazonaws.com/' . $s3_ext, 0, true);
                                    echo "Meta data for photo for potential buyers::\n";
                                    foreach ($exif as $key => $section) {
                                        foreach ($section as $name => $val) {
                                            echo "$key.$name: $val \n";
                                        }
                                    }
                                    ?></textarea>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                            </button>

                        </div>
                </div>
                </form>
            </div>
        </div>

        <br>
        <br>


        <?php
        //if the photo is for sale, display a button with a pop up API to buy the photo with a card payment
        if ($for_sale == 1 && $userlevel > 0) {

            require_once('config.php'); ?>
            <label for="usr">Click below to buy this photo</label>
            <form action="../buyPhoto" method="post">
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="<?php echo $stripe['publishable_key']; ?>"
                        data-description="Enter card details to buy photo"
                        data-amount="<?php echo($price * 100); ?>"
                        data-email="<?php echo $_SESSION['email']; ?>"
                        data-currency="gbp"
                        data-locale="auto"></script>
                <input type="hidden" name="s3_ext" value="<?php echo $s3_ext; ?>">
                <input type="hidden" name="photo_name" value="<?php echo $photo_name; ?>">
                <input type="hidden" name="price" value="<?php echo $price; ?>">
                <input type="hidden" name="userid" value="<?php echo $userID; ?>">
                <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">

            </form>
        <?php }

        ?>

        <div class="row">
            <div
                class="col-md-5 col-md-offset-1 comments-section">
                <h2 class = "pull-left">Comments <br></h2>
            </div>
        </div>


        <?php

        //select all comments attached to the photo
        $commentSql = "SELECT * FROM comments WHERE photo_id = '$photoID'";
        $commentsResult = $db->query($commentSql);
        while ($row = $commentsResult->fetch_array())
        {

        $id = $row['id'];
        $user_id = $row['user_id'];
        $date = $row['date'];
        $comment = $row['comment'];

        //if there are any comments then display them with all information saved i.e. the username of who posted it and the exact time and date
        If (!empty($comment)){

        $userCommentsSql = "SELECT username FROM users WHERE id= $user_id";
        $userCommentsResult = $db->query($userCommentsSql);
        //Mysql_num_row is counting table row
        while ($row = $userCommentsResult->fetch_array()) {
            $commentUsername = $row['username'];
        }


        ?>



                <section>
                    <div class="">
                        <label
                            class=""><?php echo $commentUsername;
                            ?></label>
                        <label
                            class="pull-right"><?php echo $date; ?></label>
                    </div>

                    <div
                        class="comment">
                        <?php echo $comment;
                        ?>

                    </div>

                    <?php
                    //if the user is logged in and if the comment's poster is the logged in user then allow them to edit their comment
                    if ($userlevel > 0) { ?>
                        <?php if ($user_id == $_SESSION['id']) { ?>
                            <form action="../editComment" method=post>
                                <textarea rows="3" cols="75" name='editComment' id='editComment'
                                          placeholder="<?php echo $comment ?>"></textarea><br/>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="photo_id" value="<?php echo $photoID; ?>">
                                <input type='submit'
                                       value="<?php echo "Click to submit your edited comment"; ?>""/>
                            </form>


                        <?php }; ?>
                    <?php }; ?>

                    <?php
                    //if the user is an admin or if the comment's poster or photo's poster is the logged in user or the then allow them to delete the comment
                    if (($userlevel == 4) || ($photo_username == $_SESSION['username']) || ($user_id == $_SESSION['id'])) { ?>


                        <form action="../deleteComment" method="post">
                            <input type="submit" name="deleteComment" value="Click here to delete comment"/>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="photo_id" value="<?php echo $photoID; ?>">
                        </form>


                    <?php }; ?>
                </section>


                <?php }
                };
                //if the logged in user is a shopper of above then allow them to insert a comment
                if (($userlevel > 0)) { ?>
                    <form action="../insertComment" method=post>
                <textarea rows="3" cols="80" name='comment' id='comment'
                          placeholder="Insert comment here"></textarea><br/>
                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>">
                        <input type="hidden" name="photo_id" value="<?php echo $photoID; ?>">
                        <input type='submit' value="<?php echo "Click to submit your comment" ?> ">
                    </form>
                <?php }; ?>



                <?php
                //if the logged in user is the photo's poster then allow them to edit their photo's information
                if ($username == $photo_username) {
                    ?>


                    <!-- Trigger the modal with a button -->
                    <button type="button" data-toggle="modal" data-target="#editPhotoModal">Edit photo
                    </button>
                    <!-- Modal -->
                    <div id="editPhotoModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Edit photo</h4>
                                </div>
                                <form action="../editPhoto" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="usr">Photo name:</label>
                                            <input type="text" class="form-control" name="photo_name"
                                                   value="<?php echo $photo_name; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Meta data:</label>
                                            <input type="text" class="form-control" name="meta_data"
                                                   value="<?php echo $meta_data; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">Price:</label>
                                            <input type="text" class="form-control" name="price"
                                                   value="<?php echo $price; ?>">
                                            <input type="hidden" name="photo_id" value="<?php echo $photoID; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default">Submit
                                        </button>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <br>
                    <br>

                    <?php
                }

                //if the user logged in is the poster or the user is an admin then allow them to delete the photo
                if ($username == $photo_username || $userlevel == 4){
                ?>

                <form action="../deletePhoto" method="post">
                    <input type="hidden" class="form-control" name="photoID" value="<?php echo $photoID; ?>">
                    <button type="submit" class="btn btn-default">Delete Photo</button>
                </form>
    </div></div>
    <?php
    }

    ?>

