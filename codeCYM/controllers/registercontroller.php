<?php
namespace controllers;

use services\RegisterService;
use services\AccountsService;
use yasmf\HttpHelper;
use yasmf\View;

class RegisterController {

    private $registerService;
    private $accountService;

    public function __construct()
    {
        session_start();
        $this->registerService = RegisterService::getDefaultRegisterService();
        $this->accountService = AccountsService::getDefaultAccountsService();
    }

    /**
     * Fonction de base du controlleur, si l'utilisateur est connecté le renvoi sur la page du compte,
     * sinon affiche la page de connexion/inscription
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page
     */
    public function index($pdo) {
        if (isset($_SESSION['UserID'])) {
            $view = new View("CheckYourMood/codeCYM/views/Account");
            $resultats = $this->accountService->getProfile($pdo, $_SESSION['UserID']);
            while($row = $resultats->fetch()) {
                $view->setVar('mail', $row->User_Email);
                $view->setVar('username', $row->User_Name);
                $view->setVar('password', $row->User_Password);
                $view->setVar('birthDate', $row->User_BirthDate);
                $view->setVar('gender', $row->User_Gender);
            }
        } else {
            $view = new View("CheckYourMood/codeCYM/views/Register");
        }
        return $view;
    }

    /**
     * vérifie que les champs soit tous remplis pour l'inscription de 
     * l'utilisateur et crée un utilisateur si tout est correct, sinon renvoi un message d'erreur
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page
     */
    public function register($pdo) {
        new User();
        $view = new View("CheckYourMood/codeCYM/views/Register");

        if (User::$username != null && User::$email != null && User::$birthDate != null && User::$gender != "Choisissez votre genre" && User::$password != null && User::$confirmPassword != null) {
            $error = $this->registerService->insertUserValues($pdo, User::$username, User::$email, User::$birthDate, User::$gender, User::$password, User::$confirmPassword);
            if ($error == "") {
                User::$email = null;
                User::$birthDate = null;
                User::$gender = null;
                User::$confirmPassword = null;
            }
            $view->setVar('registerError', $error);
        } else {
            $view->setVar('registerError', "Au moins un des champs n'est pas rempli");
        }

        return User::sendValues($view);
    }

    /**
     * vérifie que les champs soit tous remplis pour la connexion de 
     * l'utilisateur et connecte l'utilisateur si tout est correct, sinon renvoi un message d'erreur
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page
     */
    public function login($pdo) {
        new User();
        $view = new View("CheckYourMood/codeCYM/views/Register");
        if (isset($_SESSION['UserID'])) {
            $view = new View("CheckYourMood/codeCYM/views/Account");
        } else if (User::$username != null && User::$password != null && User::$login == 1) {
            $result = $this->registerService->getLoginIn($pdo, User::$username, User::$password);
            if (is_integer($result)) {
                $_SESSION['UserID'] = $result;
                $view = new View("CheckYourMood/codeCYM/views/index");
                header('Location: ?action=index&controller=home#');
                return $view;
            }
            $view->setVar('loginError', $result);
        } else {
            $view->setVar('loginError', "Au moins un des champs n'est pas rempli");
        }
        return User::sendValues($view);
    }
}

/**
 * Crée un profile avec toutes les données d'un utilisateur
 */
class User {
    public static $username;
    public static $email;
    public static $birthDate;
    public static $gender;
    public static $password;
    public static $confirmPassword;
    public static $login;

    public function __construct()
    {
        
        User::$username = htmlentities(HttpHelper::getParam("username"), ENT_QUOTES);
        User::$email = htmlentities(HttpHelper::getParam("email"), ENT_QUOTES);
        User::$birthDate = htmlentities(HttpHelper::getParam("birthDate"), ENT_QUOTES);
        User::$gender = htmlentities(HttpHelper::getParam("gender"), ENT_QUOTES);
        User::$password = htmlentities(HttpHelper::getParam("password"), ENT_QUOTES);
        User::$confirmPassword = htmlentities(HttpHelper::getParam("confirmPassword"), ENT_QUOTES);
        User::$login = htmlentities(HttpHelper::getParam("login"), ENT_QUOTES); 
    }

    public static function sendValues($view) {
        /* Ajout des valeurs dans la vue */
        $view->setVar('username', User::$username);
        $view->setVar('email', User::$email);
        $view->setVar('birthDate', User::$birthDate);
        $view->setVar('gender', User::$gender);
        $view->setVar('password', User::$password);
        $view->setVar('confirmPassword', User::$confirmPassword);
        return $view;
    }
}