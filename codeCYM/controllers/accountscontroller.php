<?php
namespace controllers;

use yasmf\HttpHelper;
use services\AccountsService;
use yasmf\View;

class AccountsController {

    private $accountsService;

    public function __construct()
    {
        session_start();
        $this->accountsService = AccountsService::getDefaultAccountsService();
    }

    /**
     * Fonction de base du controlleur, récupère le profil de l'utilisateur courant et l'affiche
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page pour pouvoir afficher les informations de l'utilisateur
     */
    public function index($pdo) {
        $id = $_SESSION['UserID'];
        $view = new View("CheckYourMood/codeCYM/views/Account");
        /* récupère dans la base de données les infos de l'utilisateur */
        $resultats = $this->accountsService->getProfile($pdo, $id); 

        /* met dans la view les données récupérées */
        while($row = $resultats->fetch()) {
            $view->setVar('mail', $row->User_Email);
            $view->setVar('username', $row->User_Name);
            $view->setVar('password', $row->User_Password);
            $view->setVar('birthDate', $row->User_BirthDate);
            $view->setVar('gender', $row->User_Gender);
        }

        return $view;
    }

    /**
     * permet de changer les informations du profil de l'utilisateur
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page d'édition du profile avec toutes les informations modifiable par l'utilisateur
     */
    public function editProfile($pdo) {

        /* création de la vue pour modifier son profil */
        $view = new View("CheckYourMood/codeCYM/views/editprofile");

        /* Création d'un objet profil contenant tous les paramètres lié au profil de l'utilisateur (mdp, email...) */
        /* créer un objet "Profile" qui stock tous les paramètres lié au profil de l'utilisateur envoyé par le formulaire */
        new Profile(); 

        /* récupération du profil de l'utilisateur courant */
        /* variable qui test si l'email existe déjà */
        $sameUsername = false;
        /* variable qui test si le nom d'utilisateur existe déjà */
        $sameEmail = false; 
        $this->getDefaultProfile($pdo, $view); // récupère le profil de l'utilisateur courant dans la base de données
        Profile::initialisation($view); // Envoie les données stockées dans l'objet "Profile" dans la view
        if(!empty(Profile::$update)) {
            /* appel des fonctions pour vérifier que l'email et le nom d'utilisateur n'existe pas déjà dans la base de données */
            /* true si l'email existe déjà, false sinon */
            $sameEmail = $this->checkSameEmail($pdo, Profile::$email); 
            /* true si le nom d'utilisateur existe déjà, false sinon */
            $sameUsername = $this->checkSameUsername($pdo, Profile::$username); 

            /* si l'email n'est pas vide et qu'il n'existe pas alors l'email est modifié */
            $this->updateEmail($pdo, $view, Profile::$email, 
                            $view->getParams("defaultEmail"), $sameEmail);

            /* si le nom d'utilisateur n'est pas vide et qu'il n'existe pas alors le nom d'utilisateur est changé */
            $this->updateUsername($pdo, $view, Profile::$username, 
                                $view->getParams("defaultUsername"), $sameUsername);

            /* si la date de naissance n'est pas la même que celle stocké dans la base de données pour l'utilisateur courant */
            /* alors elle est modifiée */
            $this->updateBirthDate($pdo, $view, Profile::$birthDate, $view->getParams("defaultBirthDate"));

            /* si le genre n'est pas le même que celui stocké dans la base de donnée alors il est modifié */
            $this->updateGender($pdo, $view, Profile::$gender, $view->getParams("defaultGender"));
        }

        return $view;
    }

    /**
     * Récupère toutes les informations modifiable du profil de l'utilisateur 
     * pour la page de modification des informations
     * @param $pdo  la connexion à la base de données
     * @return $view  les informations de l'utilisateur à afficher
     */
    public function getDefaultProfile($pdo, $view) {
        $id = $_SESSION['UserID'];

        /* récupère dans la base données les infos de l'utilisateur */
        $verif = $this->accountsService->getProfile($pdo, $id); 

        /* met dans la view les données récupérées */
        while($row = $verif->fetch()) {
            $defaultEmail = $row->User_Email;
            $defaultUsername = $row->User_Name;
            $defaultBirthDate = $row->User_BirthDate;
            $defaultGender = $row->User_Gender;
        }
        $view->setVar('defaultEmail', $defaultEmail);
        $view->setVar('defaultUsername', $defaultUsername);
        $view->setVar('defaultBirthDate', $defaultBirthDate);
        $view->setVar('defaultGender', $defaultGender);

        return $view;
    }

    /**
     * Vérifie dans la base de données si l'email existe déjà
     * @param $pdo  la connexion à la base de données
     * @param $aTester  le mail à vérifier
     * @return $sameEmail  true si l'email existe déjà dans la base de données, sinon false
     */
    public function checkSameEmail($pdo, $aTester) {
        
        $sameEmail = false;

        /* récupère dans la base données les emails qui sont les même que 'aTester' */
        $verifSameEmail = $this->accountsService->getEmails($pdo, $aTester);

        /* Si la requête retourne au moins 1 ligne alors l'email existe déjà */
        if($verifSameEmail->rowCount() != 0) $sameEmail = true;

        return $sameEmail;
    }

    /**
     * Vérifie dans la base de données si le nom d'utilisateur existe déjà
     * @param $pdo  la connexion à la base de données
     * @param $aTester  le nom d'utilisateur à vérifier
     * @return $sameUsername  true si le nom d'utilisateur existe déjà dans la base de données, sinon false
     */
    public function checkSameUsername($pdo, $aTester) {

        $sameUsername = false;

        /* récupère dans la base données tous les noms d'utilisateurs qui sont les même que 'aTester' */
        $verifSameUsername = $this->accountsService->getUsernames($pdo, $aTester);

        /* Si la requête retourne au moins 1 ligne alors le nom d'utilisateur existe déjà */
        if($verifSameUsername->rowCount() != 0) $sameUsername = true;

        return $sameUsername;
    }

    /**
     * Modifie l'email de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $view  la view à modifier
     * @param $email  la nouvelle adresse mail
     * @param $defaultEmail  l'ancienne adresse mail
     * @param $sameEmail  true si l'email est déjà existant, sinon false
     * @return $view  le message à afficher si il y a une erreur de modification ou non
     */
    public function updateEmail($pdo, $view, $email, $defaultEmail, $sameEmail) {
        $id = $_SESSION['UserID'];
        /* Si les données du formulaire sont envoyées et que l'email n'est pas vide et qu'il n'existe pas */
        if(!empty($email) && $email != $defaultEmail && $sameEmail == false) {
            $this->accountsService->editMail($pdo, $email, $id);             
            $view->setVar('message', "Votre email a bien été changé !");
        } else if(!empty($email) && $email != $defaultEmail && $sameEmail == true) {
            $view->setVar('erreur', "Email déjà existant !");
        }
        
        return $view;
    }

    /**
     * Modifie le nom d'utilisateur de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $view  la view à modifier
     * @param $username  le nouveau nom d'utilisateur
     * @param $defaultUsername  l'ancien nom d'utilisateur
     * @param $sameUsername  true si le nom d'utilisateur est déjà existant, sinon false
     * @return $view  le message à afficher si il y a une erreur de modification ou non
     */
    public function updateUsername($pdo, $view, $username, $defaultUsername, $sameUsername) {
        $id = $_SESSION['UserID'];
        /* Si les données du formulaire sont envoyées et que le nom d'utilisateur n'est pas vide et qu'il n'existe pas */
        /* le nom d'utilisateur est modifié sinon affiche une erreur */
        if(!empty($username) && $username != $defaultUsername && $sameUsername == false) {
            $this->accountsService->editUsername($pdo, $username, $id);
            $view->setVar('message', "Votre nom d'utilisateur a bien été changé !");
        } else if(!empty($username) && $username != $defaultUsername && $sameUsername == true) {
            $view->setVar('erreur', "nom d'utilisateur déjà existant !");
        }

        return $view;
    }

    /**
     * Modifie la date de naissance de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $view  la view à modifier
     * @param $birthDate  la nouvelle date de naissance
     * @param $defaultBirthDate  l'ancienne date de naissance
     * @return $view  le message à afficher si il y a une erreur de modification ou non
     */
    public function updateBirthDate($pdo, $view, $birthDate, $defaultBirthDate) {
        $id = $_SESSION['UserID'];
        /* Si les données du formulaire sont envoyées et que la date de naissance est inférieur à la date d'aujourd'hui */
        /* alors elle est modifié sinon affiche une erreur */
        if(!empty($birthDate) && $birthDate != $defaultBirthDate && $birthDate < date("Y-m-d")) {
            $this->accountsService->editBirthDate($pdo, $birthDate, $id);
            $view->setVar('message', "Votre date de naissance à bien été changée !");
        } else if($birthDate != $defaultBirthDate && $birthDate > date("Y-m-d")) {
            $view->setVar('erreur', "Votre date de naissance ne peut pas être supérieur à la date d'aujourd'hui !");
        }

        return $view;
    }

    /**
     * Modifie le genre de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @param $view  la view à modifier
     * @param $username  le nouveau genre
     * @param $defaultUsername  l'ancien genre
     * @return $view  le message à afficher si il y a une modification du genre
     */
    public function updateGender($pdo, $view, $gender, $defaultGender) {
        $id = $_SESSION['UserID'];
        /* Si les données du formulaire sont envoyée et que le genre sélectionné */
        /* n'est pas le même que celui sélectionné avant alors il change */
        $view->setVar('genderChanged', false);
        if(!empty($gender) && $gender != $defaultGender) {
            $this->accountsService->editGender($pdo, $gender, $id);
            $view->setVar('genderChanged', true);
            $view->setVar('message', "Votre genre a bien été changé !");
        }

        return $view;
    }
    

    /**
     * Change le mot de passe de l'utilisateur
     * @param $pdo  la connexion à la base de données
     * @return $view  le message à afficher si il y a une modification du mot de passe
     */
    public function editPassword($pdo) {
        $id = $_SESSION['UserID'];
        /* Création d'une nouvele vue */
        $view = new View("CheckYourMood/codeCYM/views/editpassword");

        /* Contrôle le champs ancien mot de passe */
        $view->setVar('resetPwd', 0);

        /* Création d'un objet "Passwords" et récupération des mots de passe de l'utilisateur courant */
        new Passwords();

        /* Mot de passe actuel de l'utilisateur */
        $stmt = $this->accountsService->getPasswords($pdo, $id);
        while($row = $stmt->fetch()) {
            $defaultPassword = $row->User_Password;
        }
        /* Vérification de l'ancien mot de passe */
        $testOldPassword = !empty(Passwords::$oldPassword) 
                           && strcmp($defaultPassword, md5(Passwords::$oldPassword)) == 0;
        $view->setVar('testOldPassword', $testOldPassword);
        /* Envoie des valeurs des mots de passe dans la view */
        Passwords::initialisation($view);
        /* Si le nouveau mot de passe n'est pas le même que l'ancien et que le nouveau mot de passe */
        /* et le même que celui de la confirmation du nouveau mot de passe alors le mot de passe est modifié */
        if($testOldPassword && Passwords::$testNewPassword && Passwords::$testOldPasswordNotSameAsNew) {
            $this->accountsService->editPassword($pdo, Passwords::$newPassword, $id); 
            $view->setVar('resetPwd', 1);      
            $view->setVar('message', "Votre mot de passe a bien été modifié !");
        } 
        return $view;
    }

    /**
     * Supprime le compte de l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @return $view  la page de la confirmation de la suppression du compte
     */
    public function deleteAccount($pdo) {
        $id = $_SESSION['UserID'];
        /* Chargement de la vue de la page pour supprimer son compte */
        $view = new View("CheckYourMood/codeCYM/views/deleteaccount");
        $delete = HttpHelper::getParam("delete");

        /* Si le bouton du formulaire à été cliqué alors on supprime le compte */
        if(!empty($delete)) {
            $this->accountsService->deleteProfile($pdo, $id);
            $view = new View("CheckYourMood/codeCYM/views/accountdeleted");
            session_destroy();
        } 

        return $view;
    }

    /**
     * Déconnecte l'utilisateur courant
     * @param $pdo  la connexion à la base de données
     * @return $view  la page d'accueil du site
     */
    public function disconnect($pdo) {

        /* Initialisation de la valeur de la session à null avant de la détruire */
        $_SESSION['UserID'] = null;
        session_destroy();

        /* Chargement de la vue de la page d'accueil du site */
        $view = new View("CheckYourMood/codeCYM/views/index");

        return $view;
    }

}

/**
 * crée un profile avec toutes les données modifiable d'un utilisateur
 * sauf le mot de passe qui est géré par la classe 'Password'
 */
class Profile {

    public static $email;
    public static $username;
    public static $birthDate;
    public static $gender;
    public static $update;
    public static $message;
    public static $erreur;

    public function __construct() {
 
        Profile::$email = HttpHelper::getParam("email");
        Profile::$username = HttpHelper::getParam("username");
        Profile::$birthDate = HttpHelper::getParam("birthDate");
        Profile::$gender = HttpHelper::getParam("genderSelect");
        Profile::$update = HttpHelper::getParam("envoyer");
        Profile::$message = null;
        Profile::$erreur = null;
    }

    public static function initialisation($view) {

        /* Ajout des valeurs dans la vue */
        $view->setVar('message', Profile::$message);
        $view->setVar('erreur', Profile::$erreur);
        $view->setVar('email', Profile::$email);
        $view->setVar('username', Profile::$username);
        $view->setVar('birthDate', Profile::$birthDate);
        $view->setVar('gender', Profile::$gender);
        $view->setVar('update', Profile::$update);
        return $view;
    }

}

/**
 * Crée un profile avec toutes les données nécessaires à la 
 * modifiaction du mot de passe de l'utilisateur
 */
class Passwords {

    public static $oldPassword;
    public static $newPassword;
    public static $confirmPassword;
    public static $update;
    public static $message;
    public static $testNewPassword;
    public static $testOldPasswordNotSameAsNew;

    public function __construct() {

        Passwords::$update = HttpHelper::getParam("envoyer");
        Passwords::$newPassword = HttpHelper::getParam("newPassword");
        Passwords::$confirmPassword = HttpHelper::getParam("confirmPassword");
        Passwords::$oldPassword = HttpHelper::getParam("oldPassword");
        Passwords::$message = null;
        Passwords::$testNewPassword = !empty(Passwords::$newPassword) && !empty(Passwords::$confirmPassword) 
                                      && strcmp(Passwords::$newPassword, Passwords::$confirmPassword) == 0;
        Passwords::$testOldPasswordNotSameAsNew = strcmp(Passwords::$oldPassword, Passwords::$newPassword) != 0;
    }

    public static function initialisation($view) {
        /* Ajout des valeurs dans la vue */
        $view->setVar('message', Passwords::$message);
        $view->setVar('update', Passwords::$update);
        $view->setVar('testNewPassword', Passwords::$testNewPassword);
        $view->setVar('testOldPasswordNotSameAsNew', Passwords::$testOldPasswordNotSameAsNew);
        $view->setVar('oldPassword', Passwords::$oldPassword);

        return $view;

    } 
}