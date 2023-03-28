<?php
namespace controllers;

use services\HumeursService;
use yasmf\HttpHelper;
use yasmf\View;
use PDO;

class HumeursController {

    private HumeursService $humeursService;
    

    public function __construct(HumeursService $humeursService)
    {
        session_start();
        $this->humeursService = $humeursService;
    }

    /**
     * Fonction de base du controlleur, récupère la liste des humeurs qui seront proposées,
     * si l'utilisateur n'est pas connecté renvoi sur la page de connexion/inscription
     * @param PDO $pdo  la connexion à la base de données
     * @return View $view  la vue de la page
     */
    public function index($pdo) {
        $view = new View("/views/Humeurs");
        $listeHumeurs = $this->humeursService->getListeHumeurs();
        $view->setVar('listeHumeurs',$listeHumeurs);
        if (!isset($_SESSION['UserID'])) {
            $view = new View("/views/Register");
        }
        if(isset($_SESSION['msgHumeur'])) {
            $view->setVar('msgHumeur', $_SESSION['msgHumeur']);
        }
        return $view;
    }

    
    /**
     * insère l'humeur saisie par l'utilisateur si elle est correcte, 
     * sinon ne l'insère pas et indique à l'utilisateur que l'humeur est incorrecte
     * @param PDO $pdo  la connexion à la base de données
     * @return View
     */
    public function setHumeur($pdo) {
        $view = new View("/views/Humeurs");
        $id = $_SESSION['UserID'];
        $description = htmlspecialchars(HttpHelper::getParam("description"));
        $humeur = HttpHelper::getParam("humeur");
        $smiley = HttpHelper::getParam("smiley");
        $isOK = $this->humeursService->setHumeur($pdo, $humeur, $smiley, $description, $id);
        if ($isOK) {
            $msgHumeur = "Votre humeur a bien été ajoutée.";
        } else {
            $msgHumeur ="L'humeur saisie n'existe pas !!!";
        }
        $_SESSION['msgHumeur'] = $msgHumeur;
        header('Location: ?action=index&controller=humeurs#');
        return $view;
    }
            
}