<?php

namespace services;

use PDOException;
use PDO;
use PDOStatement;

class RegisterService
{

    /**
     * Création d'un compte, insertion des données de l'utilisateur
     * @param PDO $pdo  la connexion à la base de données
     * @param String $username  le nom de l'utilisateur
     * @param String $email  l'email de l'utilisateur
     * @param String $birthDate  la date de naissance de l'utilisateur au format préféfini par l'input correspondant
     * @param String $gender  le genre de l'utilisateur
     * @param String $password  le mot de passe de l'utilisateur
     * @return String chaîne vide si la création du compte a pu être réalisé avec succès
     *                le message d'erreur correspondant à l'erreur renvoyé 
     *                par mySQL si la création n'a pas pu être faite.
     */
    public static function insertUserValues($pdo, $username, $email, $birthDate, $gender, $password, $confirmPassword) {
        try {
            $date = date("Y-m-d");
            // tout les caractères qui peuvent se trouver dans l'APIKEY
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            // boucle qui créer l'APIKEY
            for($i=0; $i<10; $i++){
                $string .= $chars[rand(0, strlen($chars)-1)];
            }
            $insert = $pdo->prepare('INSERT INTO user (User_Name,User_Email,User_BirthDate,User_Gender,User_Password,APIKEY) 
                                    VALUES (:username,:email,:birthDate,:gender,:pswd,:cle)');
            if ($password != $confirmPassword) {
                return "Les deux mots de passe ne sont pas identique";
            }
            if ($birthDate >= $date) {
                return "Date de naissance supérieur ou égale à la date du jour";
            }
            /* cryptage du mot de passe en md5*/
            $password = md5($password);
            $crypate = md5($string);
            $insert->execute(array('username'=>$username,'email'=>$email,'birthDate'=>$birthDate,'gender'=>$gender,'pswd'=>$password,'cle'=>$crypate));
            return "";
        } catch (PDOException $e) {
            $errorMessage = "création du compte impossible (le nom d'utilisateur est indisponible ou l'email est déjà utilisé) ou la base de données est inaccessible";
            return $errorMessage;
        }
    }

    

    /**
     * Récupère l'ID de l'utilisateur si le nom d'utilisateur et le mot de passe sont correct
     * @param PDO $pdo la connexion à la base de données
     * @param String $username nom d'utilisateur
     * @param String $password mot de passe
     * @return String l'id de l'utilisateur si le nom d'utilisateur et le mot de passe sont correct,
     *              Un message d'erreur dans le cas contraire
     */
    public static function getLoginIn($pdo, $username, $password) {
        $sql = "SELECT `User_ID` FROM `user` WHERE User_Name = :name AND User_Password = :pass";
        $searchStmt = $pdo->prepare($sql);
        $password = md5($password);
        $searchStmt->execute(['name'=>$username, 'pass'=>$password]);
        $id = null;
        while ($row = $searchStmt->fetch()) {
            $id = $row["User_ID"];
        }
        if ($id == null) {
            return "Login invalide, identifiant ou mot de passe incorrect !";
        }
        return $id;
    }
    
}