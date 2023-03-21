<?php
	// Envoi JSON
	function sendJSON($infos, $codeRetour){
		header("Access-Control-Allow-Origin: *"); // Permet que tout le monde peut y acceder (toutes les IP)
		header("Content-Type: application/json; charset=UTF-8"); // Type de données envoyées de type JSON

		header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
			
		// header("Access-Control-Max-Age: 3600"); // Durée de la requete
		http_response_code($codeRetour);
		echo json_encode($infos,JSON_UNESCAPED_UNICODE);
		die();
	}
?>