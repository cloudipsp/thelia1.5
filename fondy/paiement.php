<?php

include_once(realpath(dirname(__FILE__)) . "/../../../classes/Navigation.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Commande.class.php");
include_once(realpath(dirname(__FILE__)) . "/config.php");
include_once(realpath(dirname(__FILE__)) . "/lib/fondy.inc.php");

session_start();

$total = 0;

$total = $_SESSION['navig']->panier->total() + $_SESSION['navig']->commande->port;
$total -= $_SESSION['navig']->commande->remise;
$total = round($total, 2) * 100;

$fondy = new fondycsl();
$currency = 'EUR';

if (PAYMENT_CURRENCY != '') {
    $currency = PAYMENT_CURRENCY;
} else {
    if ($_SESSION['navig']->commande->devise != 0) {
        $devise = new Devise();
        $reference = $_SESSION['navig']->commande->devise;
        $devise->charger($reference);
        $currency = $devise->code;
    }
}
$order_id = $_SESSION['navig']->commande->transaction;

$fondy_args = array(
    'order_id' => $order_id,
    'merchant_id' => MERCHANT_ID,
    'order_desc' => $order_id,
    'amount' => $total,
    'currency' => $currency,
    'server_callback_url' => NOTIFICATION_URL,
    'response_url' => RETURN_URL,
    'lang' => LANGUAGE_CODE,
    'sender_email' => $_SESSION['navig']->client->email);
$fondy_args['signature'] = $fondy->getSignature($fondy_args, SECRET_KEY);

$result = $fondy->do_pay($fondy_args);

if ($result->response->response_status == 'failure') {
    echo $result->response->error_message;
    exit;
}else{
    header("location:".$result->response->checkout_url);
    exit;
}
?>
