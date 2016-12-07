<?php

include("scripts/dbconnect.php");
include("scripts/header.php");
?>

<main>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form data-toggle="validator" role="form" action="login" method="post">
            <div class="form-group">
                <label for="inputUsername" class="control-label">Username</label>
                <input type="text" class="form-control" name="username" placeholder="" required>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="control-label">Password</label>
                <div class="form-inline row">
                    <div class="form-group col-sm-6">
                        <input type="password" data-minlength="6" class="form-control" name="password"
                               id="inputPassword" placeholder="Password" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <div class="col-md-4"></div>


    <?

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        // escape the input, removing everything that could be (html/javascript-) code
        $clearedUsername = $db->real_escape_string($_POST['username']);
        $clearedPassword = $db->real_escape_string($_POST['password']);

        // database query, getting all the info of the selected user
        $stmt = new mysqli_stmt($db, "SELECT id, userlevel, password, email FROM users WHERE username = ?;");
        $stmt->bind_param("s", $clearedUsername);
        $stmt->execute();
        $stmt->bind_result($userID, $userlevel, $databasePassword, $email);
        $stmt->store_result();


        // if this user exists
        if ($stmt->num_rows() == 1) {
            while ($stmt->fetch()) {


                // using PHP password_verify() function to check if the provided password fits
                // the hash of that user's password
                if (password_verify($clearedPassword, $databasePassword)) {
                    //Display a message that the user has logged in successfully
                    ?>
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Log in successful</strong>
                    </div>
                    <?php


                    session_start();
                    // write user data into PHP SESSION
                    $_SESSION['id'] = $userID;
                    $_SESSION['userlevel'] = $userlevel;
                    $_SESSION['email'] = $email;


                    header("location:./");

                } else {
                    //Display a message that the password is wrong
                    ?>
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Wrong password</strong>
                    </div>
                    <?php
                }
            }
        } else {
            //Display a message that the user doesn't exist
            ?>
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>This user does not exist</strong>
            </div>
            <?php
        }

    } else {

    }
    ?>
