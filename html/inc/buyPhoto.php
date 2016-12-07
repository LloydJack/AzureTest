<?php
require_once('config.php');
include ("scripts/dbconnect.php");
include ("scripts/header.php");

$token  = $_POST['stripeToken'];
$price = $_POST["price"];
$email = $_POST["email"];
$email = $_POST["userid"];


//create a customer array for stripe
$customer = \Stripe\Customer::create(array(
    'email' => $email,
    'source'  => $token
));

//create a charge array for stripe
$charge = \Stripe\Charge::create(array(
    'customer' => $customer->id,
    'amount'   => $price*100,
    'currency' => 'gbp'
));

try {
    // Use Stripe's library to make requests...
} catch(\Stripe\Error\Card $e) {
    // Since it's a decline, \Stripe\Error\Card will be caught
    $body = $e->getJsonBody();
    $err  = $body['error'];

    print('Status is:' . $e->getHttpStatus() . "\n");
    print('Type is:' . $err['type'] . "\n");
    print('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    print('Param is:' . $err['param'] . "\n");
    print('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
    // Too many requests made to the API too quickly
} catch (\Stripe\Error\InvalidRequest $e) {
    // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Error\Authentication $e) {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
} catch (\Stripe\Error\ApiConnection $e) {
    // Network communication with Stripe failed
} catch (\Stripe\Error\Base $e) {
    // Display a very generic error to the user, and maybe send
    // yourself an email
} catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
}


$photo_name = $_POST["photo_name"];
$s3Path = $_POST["s3_ext"];
$price = $_POST["price"];
$userID = $_SESSION['id'];


//insert the details of the bought photo into the database after successful payment

$stmt = new mysqli_stmt($db, "INSERT INTO boughtPhotos (userid, photo_name, s3_ext) VALUES (?,?,?);");
$stmt->bind_param("iss", $userID, $photo_name, $s3Path);
$stmt->execute();

?>

<h1>Successfully charged £<?php echo $price ?>!<br>
Your purchased photo can be viewing in your <a class="" href="/boughtPhotos">Bought photos</a></h1>;
