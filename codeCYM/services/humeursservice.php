<?php

namespace services;

use PDOException;

class HumeursService
{

    /* Singleton d'instanciation */
    private static $defaultHumeursService;
    public static function getDefaultHumeursService()
    {
        if (HumeursService::$defaultHumeursService == null) {
            HumeursService::$defaultHumeursService = new HumeursService();
        }
        return HumeursService::$defaultHumeursService;
    }

    /**
     * Permet d'obtenir la liste des humeurs depuis un fichier externes
     * @return liste contenant toutes les humeurs 
     */
    public function getListeHumeurs() {
        try {
            $nomficTypes= dirname(__FILE__)."\humeurs.csv";
            if ( !file_exists($nomficTypes) ) {
                throw new Exception('Fichier '.$nomficTypes.' non trouvé.');
            }
            $liste = file($nomficTypes, FILE_IGNORE_NEW_LINES);
            return $liste;
        } catch ( Exception $e ) {
            return null;
        }
    }

    /**
     * Permet l'insertion de l'humeur d'un utilisateur si elle est dans la liste des humeurs disponibles
     * @param $pdo  la connexion à la base de données
     * @param $humeur libellé de l'humeur
     * @param $smiley smiley associé à l'humeur
     * @param $description commentaire que peut saisir un utilisateur (facultatif)
     * @return $isOk  true si l'humeur a bien été inséré, sinon false
     */
    public function setHumeur($pdo, $humeur, $smiley, $description, $id) {
        $isOk = false;
        if ($humeur != "") {
            $liste = self::getListeHumeurs();
            foreach ((array) $liste as $i) {
                if (strcasecmp($i, $humeur) == 0) {
                    $libelle = htmlspecialchars($humeur);
                    $requete = $pdo->prepare("INSERT INTO `humeur`(`CODE_User`, `Humeur_Libelle`, `Humeur_Emoji`, `Humeur_Time`, `Humeur_Description`, `Humeur_TimeConst`) 
                                                VALUES (:id,:libelle,:smiley,CURRENT_TIMESTAMP,:description,CURRENT_TIMESTAMP)");
                    $requete->bindParam("id", $id);
                    $requete->bindParam("libelle", $libelle);
                    $requete->bindParam("smiley", $smiley);
                    $requete->bindParam("description", $description);
                    $requete->execute();
                    $isOk = true;
                } 
            }
        }
        return $isOk;
    }
}