<?php
include ("scripts/dbconnect.php");
include ("scripts/header.php");

?>
<div class="col-md-4"></div>
<div class="col-md-4">
<h2>Users awaiting approval</h2>
<?php
//if the logged in user is an admin
if ($_SESSION['userlevel'] == 4) {

    $sql = ("SELECT username FROM users WHERE userlevel = '2'");
    $query = $db->query($sql);

    //output all of the shoppers that have requested approval as photographers
    while ($rowtwo = mysqli_fetch_array($query)) {
        echo '<p> Approve ' . $rowtwo['username'] . ' as a photographer?  </p>';

        ?>
        <html>


    <form action="updateLevel" method="post">
        <input type="submit" name="button" value="Click here to approve photographer"/>
        <input type="hidden" name="Approve_Photographer" value="<?php echo $rowtwo['username']; ?>">
    </form>
        </html><?php

    }
}
else {

   header("location:/");
}
    ?></div>



    
        
    
    

