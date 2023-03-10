<?php 
	
	require_once("json.php");
	require_once("donnees.php");

	$request_method = $_SERVER["REQUEST_METHOD"];  // GET / POST / DELETE / PUT
	switch($_SERVER["REQUEST_METHOD"]) {
		case "GET" :
			if (!empty($_GET['demande'])) {

				// décomposition URL par les / et  FILTER_SANITIZE_URL-> Supprime les caractères illégaux des URL
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				
				switch($url[0]) {
					
					// si apres le premier / il y a fiveLastHumeurs
					case 'fiveLastHumeurs' :
						$donnees = json_decode(file_get_contents("php://input"),true);
						// fonction qui verifie la validité de l'APIKEY
						authentification($donnees['code_user']); 
						// fonction qui renvoie les 5 dernieres humeurs
						getFiveLastHumeurs($donnees);
						break ;

					// si apres le premier / il y a login
					case 'login' : 
						// affectation aux variables le login et le mot de passe
						if (isset($url[1])) {
							$login = $url[1];
						} else {
							$login = "";
						}
						if (isset($url[2])) {
							$password = $url[2];
						} else {
							$password = "";
						}
						// fonction qui génère et stock l'APIKEY dans la bd
						verifLogin($login, $password);
						break ;

					case 'getCodeUser' : 
						// affectation aux variables le login et le mot de passe
						if (isset($url[1])) {
							$login = $url[1];
						} else {
							$login = "";
						}
						if (isset($url[2])) {
							$password = $url[2];
						} else {
							$password = "";
						}
						// fonction qui génère et stock l'APIKEY dans la bd
						getCodeUser($login, $password);
						break ;
					
						
					default : 
						$infos['Statut']="KO";
						$infos['message']=$url[0]." inexistant";
						sendJSON($infos, 404) ;
				}
			} else {
				$infos['Statut']="KO";
				$infos['message']="URL non valide";
				sendJSON($infos, 404) ;
			}
			break ;
			
		case "PUT" :
			if (!empty($_GET['demande'])) {
				// Récupération des données envoyées
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				switch($url[0]) {
					// si apres le premier / il y a addHumeur
					case 'addHumeur' :
						if (!empty($url[1])) {  // Attention si valeur 0 = false ->  vrai
							// fonction permettant de vérifier la validité de l'APIKEY
							authentification($url[1]); 
							$donnees = json_decode(file_get_contents("php://input"),true);
							// fonction qui va ajouter a la bd une humeur
							addHumeurToAnAccount($donnees,$url[1] );
						} else {
							$infos['Statut']="KO";
							$infos['message']="Vous n'avez pas renseigné de code User";
							sendJSON($infos, 400) ;
						}
						
						break ;
					
					default : 
						$infos['Statut']="KO";
						$infos['message']=$url[0]." inexistant";
						sendJSON($infos, 404) ;
				}	
			} else {
				$infos['Statut']="KO";
				$infos['message']="URL non valide";
				sendJSON($infos, 404) ;
			}
			break;
		
		default :
			$infos['Statut']="KO";
			$infos['message']="URL non valide";
			sendJSON($infos, 404) ;
	}
			
		
?>