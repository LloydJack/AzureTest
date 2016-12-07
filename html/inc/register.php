<?php
include("scripts/dbconnect.php");
include("functions/functions.php");
include("scripts/header.php");
?>
<main>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form data-toggle="validator" role="form" action="register" method="post">
            <div class="form-group">
                <label for="inputEmail" class="control-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder=""
                       data-error="That email address is invalid" required>
            </div>
            <div class="form-group">
                <label for="inputUsername" class="control-label">Username (A-Z/0-9 only)</label>
                <input type="text" class="form-control" pattern="[a-zA-Z]{1,}" name="username" placeholder="" required>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="control-label">Password</label>
                <div class="form-inline row">
                    <div class="form-group col-sm-6">
                        <input type="password" data-minlength="6" class="form-control" name="password"
                               id="inputPassword" placeholder="Password" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="password" class="form-control" name="passwordrepeat" id="inputPasswordConfirm"
                               data-match="#inputPassword" data-match-error="Whoops, these don't match"
                               placeholder="Confirm" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <div class="col-md-4"></div>


</main>
<?


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


// escape the input, removing everything that could be (html/javascript-) code
    $email = $db->real_escape_string(strip_tags($_POST['email'], ENT_QUOTES));
    $username = $db->real_escape_string(strip_tags($_POST['username'], ENT_QUOTES));
    $password = $db->real_escape_string(strip_tags($_POST['password'], ENT_QUOTES));
    $passwordrepeat = $db->real_escape_string(strip_tags($_POST['passwordrepeat'], ENT_QUOTES));

    $sql = "SELECT * FROM users WHERE username = '" . $username . "';";
    $query_check_user_name = $db->query($sql);
    $count=mysqli_num_rows($query_check_user_name);



    $emailSql = "SELECT * FROM users WHERE email = '" . $email . "';";
    $query_check_email = $db->query($emailSql);
    $emailCount=mysqli_num_rows($query_check_email);


    if ($password !== $passwordrepeat) {
        ?>
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Password and password repeat did not match</strong>
        </div>
        <?php
    } else if ($count>=1) {
        ?>
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>This username has been taken, please choose another</strong>
        </div>
        <?php
    } else  if($emailCount>=1) {
        ?>
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>This email has been taken, please choose another</strong>
        </div>
    <?php
    } else {


        //set the cost to 11
        $options = [
            'cost' => 11,
        ];

        //hash the user's password using the very secure bcrypt function
        $hash = password_hash($password, PASSWORD_BCRYPT, $options);


        //Insert the user's information into the database using a prepared statement to protect from SQL injections
        $userlevel = 1;
        $stmt = new mysqli_stmt($db, "INSERT INTO users (username, password, email, userlevel) VALUES (?,?,?,?);");
        $stmt->bind_param("sssi", $username, $hash, $email, $userlevel);


        if ($stmt->execute())
        {
             ?>


            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>You have successfully registered, Please login! </strong>
            </div>
            <?php
        }

    }
}
?>
