<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsPaiements.class.php");
	
	class Fondy extends PluginsPaiements{

		function init(){
			$this->ajout_desc("Fondy", "Fondy", "", 1);
	
		}

		function __construct(){
			parent::__construct("fondy");
		}
		
	
		function paiement($commande){

			header("Location: " . "client/plugins/fondy/paiement.php");
		}
	
	}

?>
