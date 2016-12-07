<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");
echo "
<body>
<main>
    <h1>Welcome to the photoShare!</h>
</main>
";

$user_id = $_SESSION['id'];
$userlevel = $_SESSION['userlevel'];

//if the logged in user is a shopper, allow them to apply to become a photographer
if ($userlevel == 1) {
    ?><h1>
    <form action="applyPhotographer" method="post">
        <input type="hidden" class="form-control" name="user_id" value="<?php echo $user_id; ?>">
        <button type="submit" class="btn btn-default">Click here to apply to become a photographer</button>
    </form></h1>
    </body>
    <?php
}


?> 