<?php
require_once('vendor/autoload.php');

//set the stripe secret and publishable keys
$stripe = array(
    "secret_key"      => "sk_test_pIfu1IeMUyEgIq3zTLrZBfrY",
    "publishable_key" => "pk_test_rhWpLkNvV0xNRzNhXoxfCNeZ"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>