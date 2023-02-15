<?php

namespace services;

use PDOException;

class AccountsService
{
    /**
     * Récupère les informations du profil de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @return $resultats  le résulat de la requête (toutes les données d'un utilisateur)
     */
    public static function getProfile($pdo, $id) {

        $requete = "SELECT * FROM User WHERE User_ID = $id";
        $resultats=$pdo->query($requete);

        return $resultats;
    }

    /**
     * Récupère tous les emails qui correspondent à 'aTester'
     * @param $pdo  la connexion à la base de données
     * @param $aTester  l'email à vérifier
     * @return $resultats  le résulat de la requête (tous les emails dans 
     *                     la base de donnée qui correspondent à 'aTester')
     */
    public function getEmails($pdo, $aTester) {

        $requete = "SELECT User_Email FROM User WHERE User_Email LIKE '$aTester'";
        $resultats= $pdo->query($requete);

        return $resultats;
    }

    /**
     * Récupère tous les noms d'utilisateurs qui correspondent à 'aTester'
     * @param $pdo  la connexion à la base de données
     * @param $aTester  le nom d'utilisateur à vérifier
     * @return $resultats  le résulat de la requête (tous les noms d'utilisateurs dans 
     *                     la base de donnée qui correspondent à 'aTester')
     */
    public function getUsernames($pdo, $aTester) {

        $requete = "SELECT User_Name FROM User WHERE User_Name LIKE '$aTester'";
        $resultats= $pdo->query($requete);

        return $resultats;
    }

    /**
     * Récupère le mot de passe actuel de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @return $resultats  le résulat de la requête (le mot de passe de l'utilisateur, stocké dans la base de données)
     */
    public function getPasswords($pdo, $id) {
        $requete = "SELECT User_Password FROM User WHERE User_ID = $id";
        $resultats=$pdo->query($requete);

        return $resultats;
    }

    /**
     * Modifie le mot de passe de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $newPassword  le nouveau mot de passe
     */
    public function editPassword($pdo, $newPassword, $id) {

        $stmt = $pdo->prepare("UPDATE user SET User_Password = :pwd WHERE User_ID = $id");
        $newPassword = md5($newPassword);
        $stmt->bindParam('pwd', $newPassword);
        $stmt->execute();
    }

    /**
     * Modifie le mail de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $newEmail  la nouvelle adresse mail
     */
    public function editMail($pdo, $newEmail, $id) {

        $stmt = $pdo->prepare("UPDATE user SET User_Email = :email WHERE User_ID = $id");
        $stmt->bindParam('email', $newEmail);
        $stmt->execute();
    }

    /**
     * Modifie le nom d'utilisateur de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $newUsername  le nouveau nom d'utilisateur
     */
    public function editUsername($pdo, $newUsername, $id) {

        $stmt = $pdo->prepare("UPDATE user SET User_Name = :username WHERE User_ID = $id");
        $stmt->bindParam('username', $newUsername);
        $stmt->execute();
    }

    /**
     * Modifie la date de naissance de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $newBirthDate  la nouvelle date de naissance
     */
    public function editBirthDate($pdo, $newBirthDate, $id) {

        $stmt = $pdo->prepare("UPDATE user SET User_BirthDate = :birthDate WHERE User_ID = $id");
        $stmt->bindParam('birthDate', $newBirthDate);
        $stmt->execute();
    }

    /**
     * Modifie le genre de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $newGender  le nouveau genre
     */
    public function editGender($pdo, $newGender, $id) {
        
        $stmt = $pdo->prepare("UPDATE user SET User_Gender = :gender WHERE User_ID = $id");
        $stmt->bindParam('gender', $newGender);
        $stmt->execute();
    
    }

    /**
     * Supprime le profil de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     */
    public function deleteProfile($pdo, $id) {
        
        $stmt = $pdo->prepare("DELETE FROM humeur WHERE CODE_USER = $id");
        $stmt->execute();
        $stmt = $pdo->prepare("DELETE FROM user WHERE User_ID = $id");
        $stmt->execute();
    }

    /* Singleton d'instanciation */
    private static $defaultAccountsService ;
    public static function getDefaultAccountsService()
    {
        if (AccountsService::$defaultAccountsService == null) {
            AccountsService::$defaultAccountsService = new AccountsService();
        }
        return AccountsService::$defaultAccountsService;
    }
}
