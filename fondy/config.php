<?php

include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

$urlsite = new Variable();
$urlsite->charger("urlsite");

DEFINE( 'MERCHANT_ID', 'XXXXXX' ); // ID Marchand
DEFINE( 'SECRET_KEY', 'XXXXXX' ); // clef secrète

DEFINE( 'PAYMENT_CURRENCY', '' ); // EUR, USD, GBP, RUB, UAH
DEFINE( 'ORDER_CURRENCY', PAYMENT_CURRENCY );

DEFINE( 'SECURITY_MODE', 'SSL' ); // Protocole (ex: SSL = HTTPS)
DEFINE( 'LANGUAGE_CODE', 'fr' ); // Langue

DEFINE('NOTIFICATION_URL', $urlsite->valeur . '/client/plugins/fondy/confirmation.php');	// Adresse de notification auto
DEFINE('RETURN_URL', $urlsite->valeur . '/?fond=merci');	// Adresse de retour

?>