<?php
include_once("../../../classes/Commande.class.php");
include_once("../../../fonctions/divers.php");

include_once('config.php');
include_once('lib/fondy.inc.php');

if (empty($_POST)) {
    $callback = json_decode(file_get_contents("php://input"));
    if(empty($callback)){
        die('go away!');
    }
    $_POST = array();
    foreach ($fap as $key => $val) {
        $_POST[$key] = $val;
    }
}

$fondy = new fondycsl();
$options = array(
    'merchant' => MERCHANT_ID,
    'secretkey' => SECRET_KEY
);
$paymentInfo = $fondy->isPaymentValid($options, $_POST);

if ($paymentInfo === true) {
    if ($_POST['order_status'] == 'approved') {
        $commande = new Commande();
        $commande->charger_trans($_POST['order_id']);
        $commande->statut = 2;
        $commande->genfact();
        $commande->maj();
    }
    if ($_POST['order_status'] == 'expired' or $_POST['order_status'] == 'declined') {
        $commande = new Commande();
        $commande->charger_trans($_POST['order_id']);
        $commande->statut = 5;
        $commande->genfact();
        $commande->maj();
    }
    echo 'Ok';
} else {
    die('Not OK');
}
?>