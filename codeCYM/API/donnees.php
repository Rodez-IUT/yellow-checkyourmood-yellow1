<?php
namespace API;

use PDO;
use PDOException;
	// Données
		
	function getPDO(){
		// Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='cym';		// Nom de la BD
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		// Constitution variable DSN
		$dsn="mysql:host=$host;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES=>false];
		
		try{	// Bloc try bd injoignable ou si erreur SQL
			$pdo=new PDO($dsn,$user,$pass,$options);
			return $pdo ;			
		} catch(PDOException $e){
			//Il y a eu une erreur de connexion
			$infos['Statut']="KO";
			$infos['message']="Problème connexion base de données";
			sendJSON($infos, 500) ;
			die();
		}
	}
	

	/**
     * Retourne les 5 dernieres humeurs correspondant a un code_user
     * @param $donnees correspondant au code_user
     */
	function getFiveLastHumeurs($donnees) {
		try {
			// connexion a la bd
			$pdo=getPDO();
			// requete mysql
			$maRequete='SELECT * FROM humeur WHERE CODE_User = :leCode ORDER BY Humeur_Time DESC LIMIT 5';

			// Préparation de la requête
			$stmt = $pdo->prepare($maRequete);						

			// affectation des parametres pour sécuriser la requete
			$stmt->bindParam("leCode", $donnees);

			// Execution de la requete
			$stmt->execute();	
			$nb = $stmt->rowCount();
			
			// recuperation des données et inversement du tableau
			$humeurs=$stmt->fetchALL();
			$humeurs = array_reverse($humeurs);
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			// cas de test pour le status
			if ($nb!=0) {
				sendJSON($humeurs, 200) ;
			} else {
				sendJSON($humeurs, 404) ;
			}

		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
	}

	/**
     * Verifie que le login et le mot de passe correspondent a un compte
	 * Si c'est bon alors une  APIKEY est généré et stocké en bd 
     * @param $login correspond au login du compte
	 * @param $password correspond au password du compte
     */
	function verifLogin($login, $password) {
		try {
			$aReturn = '';
			// connexion a la bd
			$pdo = getPDO();
			// cryptage du mot de passe 
			$password = md5($password);
			// requete mysql
			$maRequete = 'SELECT APIKEY FROM user WHERE USER_Name = :leLogin AND USER_Password = :lePassword';

			// preparation de la requete
			$stmt = $pdo->prepare($maRequete);
			
			// affectation des parametres pour sécuriser la requete
			$stmt->bindParam("leLogin", $login);
			$stmt->bindParam("lePassword", $password);

			// execution de la requete
			$stmt->execute();	
			$nb = $stmt->rowCount();
			while($apiKey = $stmt->fetch()) {
				$aReturn = $apiKey['APIKEY'];
			}

			// si $nb == 0 alors le login et le mot de passe ne correspondent pas a un compte
			if ($nb==0) {
				$infos['Statut']="KO";
				$infos['Message']="Logins incorrects";
				sendJSON($infos, 401) ;
				die();
			} else {
				$infos['Statut']="OK";
				$infos['Message']="Connexion reussi";
				$infos['APIKEY']= $aReturn;
				sendJSON($infos, 200) ;
			}
		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
	}

	/**
     * Verifie que le login et le mot de passe correspondent a un compte
	 * Si c'est bon alors une  APIKEY est généré et stocké en bd 
     * @param $login correspond au login du compte
	 * @param $password correspond au password du compte
     */
	function getCodeUser($login, $password) {
		try {
			$code = '';
			// connexion a la bd
			$pdo = getPDO();
			// cryptage du mot de passe 
			$password = md5($password);
			// requete mysql
			$maRequete = 'SELECT User_ID FROM user WHERE USER_Name = :leLogin AND USER_Password = :lePassword';

			// preparation de la requete
			$stmt = $pdo->prepare($maRequete);
			
			// affectation des parametres pour sécuriser la requete
			$stmt->bindParam("leLogin", $login);
			$stmt->bindParam("lePassword", $password);

			// execution de la requete
			$stmt->execute();	
			$nb = $stmt->rowCount();
			while($row = $stmt->fetch()) {
				$code = $row['User_ID'];
			}

			// si $nb == 0 alors le login et le mot de passe ne correspondent pas a un compte
			if ($nb==0) {
				$infos['Statut']="KO";
				$infos['Message']="Logins incorrects";
				sendJSON($infos, 401) ;
				die();
			} else {
				$infos['Statut']="OK";
				$infos['Message']="Login Correct";
				$infos['Code_User']= $code;
				sendJSON($infos, 200) ;
			}
		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
	}

	

	/**
     * Verifie si l'APIKEY est correct
     */
	function authentification($id) {
		try {
			// si l'APIKEY a été entrée en tant que parametres de la fonction qui fait appel a l'API
			if (isset($_SERVER["HTTP_CYMAPIKEY"])) {
				$cleAPI=$_SERVER["HTTP_CYMAPIKEY"];
				
				// connexion bd
				$pdo = getPDO();
				// requete sql
				$maRequete = 'SELECT * FROM user WHERE APIKEY = :laAPIKEY AND User_ID = :leId';

				// preparation de la requete
				$stmt = $pdo->prepare($maRequete);
				
				// affectation des parametres pour sécuriser la requete
				$stmt->bindParam("laAPIKEY", $cleAPI);
				$stmt->bindParam("leId", $id);

				// execution de la requete
				$stmt->execute();
				$nb = $stmt->rowCount();	
				
				// si $nb == 0 alors l'APIKEY est invalide
				if ($nb==0) {
					$infos['Statut']="KO";
					$infos['message']="APIKEY invalide.";
					sendJSON($infos, 403) ;
					die();
				}
			// l'utilisateur n'a pas entrée d'APIKEY
			}else {
				// Pas de clé API envoyée, pas d'accès à l'Api
				$infos['Statut']="KO";
				$infos['message']="Authentification necessaire par APIKEY.";
				sendJSON($infos, 401) ;
				die();
			}
		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
		
	}
	
	/**
     * Ajout une humeur
	 * @param $donnees correspond a toute les donnees nécéssaire pour ajouter une humeur
	 * @param $codeUser correspond au code de l'utilisateur
     */
	function addHumeurToAnAccount($donnees, $codeUser) {
		// verification de toutes les donnees qui ne doivent pas etre null
		if(!empty($donnees['libelle']) && !empty($donnees['emoji']) && !empty($donnees['time']) && !empty($donnees['timeConst'])
		  ){
			  // Données remplies, on ajout l'humeur
			try {
				// connexion bd
				$pdo=getPDO();
				// description peut etre null
				if (empty($donnees['description'])) {
					// requete sql
					$maRequete='INSERT INTO humeur (`CODE_User`, `Humeur_Libelle`, `Humeur_Emoji`, `Humeur_Time`, `Humeur_Description`, `Humeur_TimeConst`)
					VALUES (:leCode, :leLibelle, :leEmoji, :leTime, :laDescription, :leTimeConst)';

					// Préparation de la requête
					$stmt = $pdo->prepare($maRequete);						

					// affectation des parametres pour sécuriser la requete
					$stmt->bindParam("leCode", $codeUser);
					$stmt->bindParam("leLibelle", $donnees['libelle']);
					$stmt->bindParam("leEmoji", $donnees['emoji']);
					$stmt->bindParam("leTime", $donnees['time']);
					$stmt->bindParam("laDescription", "");
					$stmt->bindParam("leTimeConst", $donnees['timeConst']);

				} else {
					// requete sql
					$maRequete='INSERT INTO humeur (`CODE_User`, `Humeur_Libelle`, `Humeur_Emoji`, `Humeur_Time`, `Humeur_Description`, `Humeur_TimeConst`)
					VALUES (:leCode, :leLibelle, :leEmoji, :leTime, :laDescription, :leTimeConst)';
					
					// Préparation de la requête
					$stmt = $pdo->prepare($maRequete);					

					// affectation des parametres pour sécuriser la requete
					$stmt->bindParam("leCode", $codeUser);
					$stmt->bindParam("leLibelle", $donnees['libelle']);
					$stmt->bindParam("leEmoji", $donnees['emoji']);
					$stmt->bindParam("leTime", $donnees['time']);
					$stmt->bindParam("laDescription", $donnees['description']);
					$stmt->bindParam("leTimeConst", $donnees['timeConst']);
				}

				// execution requete
				$stmt->execute();	
				$nb = $stmt->rowCount();
				
				$stmt=null;
				$pdo=null;
				
				// si $nb == 0 alors ajout non effectuéé
				if ($nb==0) {
					$infos['Statut']="KO";
					$infos['Message']="Erreur lors de l'ajout";
					sendJSON($infos, 400) ;
				} else {
					// Modification réalisée
					$infos['Statut']="OK";
					$infos['Message']="Ajout effectuée";
					sendJSON($infos, 201) ;
				}

			} catch(PDOException $e){
				// Retour des informations au client 
				$infos['Statut']="KO";
				$infos['message']=$e->getMessage();
				sendJSON($infos, 500) ;
			}
		}else {
			// Données manquantes, Retour des informations au client 
			$infos['Statut']="KO";
			$infos['message']="Données incomplètes";
			sendJSON($infos, 400) ;
		}
	}
	
?>