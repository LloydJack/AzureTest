<?php
session_start();
include("scripts/dbconnect.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="bootstrap/favicon.ico">

    <title>photoShare</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->
    <link rel="stylesheet" href="css/style.css">
</head>
<div>
    <div class="container">
        <h1>photoShare</h1>
    </div>
</div>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <ul>
                <a class="navbar-brand" href="./">Home Page</a>
                <a class="navbar-brand" href="photoList">Photos</a>
                <a class="navbar-brand" href="photographers">Photographers</a>


                <?
                $id = $_SESSION['id'];
                $sql = "SELECT * FROM users WHERE id = $id;";
                $result = $db->query($sql);

                // Mysql_num_row is counting table row
                $count = mysqli_num_rows($result);

                // If result matched $myusername and $mypassword, table row must be 1 row
                if ($count == 1) {
                    $row = mysqli_fetch_object($result);//fetch object
                    $_SESSION["username"] = $row->username;
                    $_SESSION["id"] = $row->id;
                    $_SESSION["userlevel"] = $row->userlevel;
                    $_SESSION["email"] = $row->email;


                }
                
                //if the user is banned then display the banned page no matter what
                if ((isset($_SESSION['username'])) && ($_SESSION['userlevel'] == 0)) {
                    header("location:youAreBanned");
                }

                //if the user is an admin display admin pages
                if ((isset($_SESSION['username'])) && ($_SESSION['userlevel'] == 4)) {

                    echo "<a class=\"navbar-brand\" href='admin'>Admin page</a>";
                }

                //if the user is a photographer or above display photo and profiles pages
                if ((isset($_SESSION['username'])) && ($_SESSION['userlevel'] >= 3)) {

                    $myUsername = $_SESSION['username'];
                    echo "<a class=\"navbar-brand\" href='profile/$myUsername'>$myUsername</a>";
                    echo "<a class=\"navbar-brand\" href='uploadPhoto'>Upload Photo</a>";

                    // echo "<a class=\"navbar-brand\" href='chat'>Chat</a>";
                }

                if ((isset($_SESSION['username'])) && ($_SESSION['userlevel'] >= 1)) {

                    echo "<a class=\"navbar-brand\" href='boughtPhotos'>Bought photos</a>";
                    echo "<a class=\"navbar-brand\" href='logout'>Logout</a>";
                } else {
                    echo "<a class=\"navbar-brand\" href='register'>Register</a>";
                    echo "<a class=\"navbar-brand\" href='login'>Login</a>";
                }

                ?>

            </ul>
        </div>
            <form class="navbar-form" role="search" method="post" action="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="searchinput" id="srch-term">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit" name="search_type" value="photo">
                            <span class="glyphicon glyphicon-search"> Search</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>


</nav>
