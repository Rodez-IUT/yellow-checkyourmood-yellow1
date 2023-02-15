<?php
namespace controllers;

use services\StatsService;
use services\HumeursService;
use yasmf\View;
use yasmf\HttpHelper;

class StatsController {

    private $statsService;

    public function __construct()
    {
        session_start();
        $this->statsService = StatsService::getDefaultStatsService();
        $this->humeursService = HumeursService::getDefaultHumeursService();
    }

    /**
     * Fonction de base du controlleur, si l'utilisateur n'est pas connecté 
     * le renvoi sur la page du connexion/inscription,
     * sinon affiche la page des statistiques de l'utilisateur
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page
     */
    public function index($pdo) {
        $view = new View("CheckYourMood/codeCYM/views/Stats");
        $startDate = HttpHelper::getParam("startDate");
        $endDate = HttpHelper::getParam("endDate");
        $humeurs = HttpHelper::getParam("humeurs");
        if (!isset($_SESSION['UserID'])) {
            $view = new View("CheckYourMood/codeCYM/views/Register");
        } else {
            $MaxHum = $this->statsService->getMaxHumeur($pdo, $_SESSION['UserID']);
            $MaxHum2 = $this->statsService->getMaxHumeur($pdo, $_SESSION['UserID']);
            $listeHumeurs = $this->humeursService->getListeHumeurs();
            $valueByDate1 = $this->statsService->getHumeurByTime($pdo, $startDate, $endDate, $humeurs,$_SESSION['UserID']);
            $valueByDate2 = $this->statsService->getHumeurByTime($pdo, $startDate, $endDate, $humeurs,$_SESSION['UserID']);
            $allRow = $this->statsService->getAllRow($pdo,$_SESSION['UserID']);
            $nombreSaisiesHumeurSelectionnee = $this->statsService->getNombreSaisiesHumeurSelectionnee($pdo, $humeurs,$_SESSION['UserID']);
            $AllValue1 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
            $AllValue2 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
            $AllValue3 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
            $AllValue4 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
            $AllValueBetweenTwoDate1 = $this->statsService->getAllValueBetweenDates($pdo, $startDate, $endDate,$_SESSION['UserID']);
            $AllValueBetweenTwoDate2 = $this->statsService->getAllValueBetweenDates($pdo, $startDate, $endDate,$_SESSION['UserID']);
            $valueExist = $this->statsService->verifHumeurEstPresente($pdo, $startDate, $endDate, $humeurs, $_SESSION['UserID']);
            $isThere = $this->statsService->verifIsThere($pdo, $startDate, $endDate,$_SESSION['UserID']);
            $view->setVar('allValue1', $AllValue1);
            $view->setVar('allValue2', $AllValue2);
            $view->setVar('allValue3', $AllValue3);
            $view->setVar('allValue4', $AllValue4);
            $view->setVar('listeHumeurs',$listeHumeurs);
            $view->setVar('MaxHumeur', $MaxHum);
            $view->setVar('MaxHumeur2', $MaxHum2);
            $view->setVar('valueByDate1', $valueByDate1);
            $view->setVar('valueByDate2', $valueByDate2);
            $view->setVar('humeurs', $humeurs);
            $view->setVar('startDate', $startDate);
            $view->setVar('endDate', $endDate);
            $view->setVar('allRow',$allRow);
            $view->setVar('nombreSaisiesHumeurSelectionnee', $nombreSaisiesHumeurSelectionnee);
            $view->setVar('AllValueBetweenTwoDate1', $AllValueBetweenTwoDate1);
            $view->setVar('AllValueBetweenTwoDate2', $AllValueBetweenTwoDate2);
            $view->setVar('Exist', $valueExist);
            $view->setVar('isThere' , $isThere);
        }
        return $view;
    }

    /**
     * affiche la page de l'historique des valeurs de l'utilisateur
     * si l'utilisateur n'est pas connecté, le renvoi sur la page de connexion/inscription
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page
     */
    public function historyVal($pdo) {
        $view = new View("CheckYourMood/codeCYM/views/history");
        if (!isset($_SESSION['UserID'])) {
            $view = new View("CheckYourMood/codeCYM/views/Register");
        } else {
            $pagination = HttpHelper::getParam('page');
            $resultats = $this->statsService->getHistorique($pdo, $pagination,  $_SESSION['UserID']);
            $allRow = $this->statsService->getAllRow($pdo,$_SESSION['UserID']);
            $view->setVar('historyValue',$resultats);
            $view->setVar('allRow',$allRow);
        }
        return $view;
    }

    /**
     * Affiche différentes informations et graphes sur l'humeur 
     * qui a été sélectionnée entre les dates sélectionnée
     * @param $pdo  la connexion à la base de données
     * @return $view  la vue de la page avec l'option sélectionnée
     */
    public function optionSelected($pdo) {
        $view = new View("CheckYourMood/codeCYM/views/Stats");
        $startDate = HttpHelper::getParam("startDate");
        $endDate = HttpHelper::getParam("endDate");
        $humeurs = HttpHelper::getParam("humeurs");
        $listeHumeurs = $this->humeursService->getListeHumeurs();
        $emojiUsed = $this->statsService->getMostUsed($pdo, $startDate, $endDate, $humeurs, $_SESSION['UserID']);
        if ($endDate == "" || $startDate == "") {
            $result = "<p>Veuillez selectionner la date de début ainsi que la date de fin.</p><p class='smiley'>🚫</p>";
        } else if ($endDate < $startDate) {
            $result = "<p>La date de début doit être antérieure à la date de fin.</p><p class='smiley'>🚫</p> ";
        } else if ($emojiUsed == "") {
            $result = "<p>L'humeur " . $humeurs . " n'a jamais été saisie entre le ".$startDate." et le ".$endDate."</p>";
        } else if (count($emojiUsed) == 2) {
            $result = "<p class='smiley'>".$emojiUsed[0]."</p><p> Vous avez eu l'humeur ".$emojiUsed[1]." fois entre le ".$startDate." et le ".$endDate."</p>";
        } else {
            $result = "<p class='smiley'>♾️</p><p>Vous avez utilisé un total de ".$emojiUsed[0]." humeurs entre le ".$startDate." et le ".$endDate."</p>";
        }
        $MaxHum = $this->statsService->getMaxHumeur($pdo,$_SESSION['UserID']);
        $MaxHum2 = $this->statsService->getMaxHumeur($pdo,$_SESSION['UserID']);
        $valueByDate1 = $this->statsService->getHumeurByTime($pdo, $startDate, $endDate, $humeurs,$_SESSION['UserID']);
        $valueByDate2 = $this->statsService->getHumeurByTime($pdo, $startDate, $endDate, $humeurs,$_SESSION['UserID']);
        $AllValue1 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
        $AllValue2 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
        $AllValue3 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
        $AllValue4 = $this->statsService->getAllValue($pdo,$_SESSION['UserID']);
        $nombreSaisiesHumeurSelectionnee = $this -> statsService -> getNombreSaisiesHumeurSelectionnee($pdo, $humeurs,$_SESSION['UserID']);
        $allRow = $this->statsService->getAllRow($pdo,$_SESSION['UserID']);
        $AllValueBetweenTwoDate1 = $this->statsService->getAllValueBetweenDates($pdo, $startDate, $endDate,$_SESSION['UserID']);
        $AllValueBetweenTwoDate2 = $this->statsService->getAllValueBetweenDates($pdo, $startDate, $endDate,$_SESSION['UserID']);
        $valueExist = $this->statsService->verifHumeurEstPresente($pdo, $startDate, $endDate, $humeurs,$_SESSION['UserID']);
        $isThere = $this->statsService->verifIsThere($pdo, $startDate, $endDate,$_SESSION['UserID']);
        $view->setVar('emojiUsed', $result);
        $view->setVar('listeHumeurs',$listeHumeurs);
        $view->setVar('startDate', $startDate);
        $view->setVar('endDate', $endDate);
        $view->setVar('humeurs', $humeurs);
        $view->setVar('MaxHumeur', $MaxHum);
        $view->setVar('MaxHumeur2', $MaxHum2);
        $view->setVar('allValue1', $AllValue1);
        $view->setVar('allValue2', $AllValue2);
        $view->setVar('allValue3', $AllValue3);
        $view->setVar('allValue4', $AllValue4);
        $view->setVar('valueByDate1', $valueByDate1);
        $view->setVar('valueByDate2', $valueByDate2);
        $view->setVar('allRow',$allRow);
        $view->setVar('nombreSaisiesHumeurSelectionnee', $nombreSaisiesHumeurSelectionnee);
        $view->setVar('AllValueBetweenTwoDate1', $AllValueBetweenTwoDate1);
        $view->setVar('AllValueBetweenTwoDate2', $AllValueBetweenTwoDate2);
        $view->setVar('Exist', $valueExist);
        $view->setVar('isThere' , $isThere);
        return $view;
    }

    /**
     * permet de récupérer les données quantitatives sur une ou plusieurs humeurs
     * affiche le nombre de saisies d'une humeur par rapport au nombre total
     * de saisies de toutes les humeurs, ainsi qu'un pourcentage de saisie
     * de cette humeur
     */

    public function deleteHumeur($pdo) {
        $view = new View("CheckYourMood/codeCYM/views/history");
        $pagination = HttpHelper::getParam('page');
        $time = HttpHelper::getParam("time");
        $libelle = HttpHelper::getParam("libelle");
        $this->statsService->delHumeur($pdo, $time, $libelle,$_SESSION['UserID']);
        $resultats = $this->statsService->getHistorique($pdo,$pagination, $_SESSION['UserID']);
        $allRow = $this->statsService->getAllRow($pdo,$_SESSION['UserID']);
        $view->setVar('historyValue',$resultats);
        $view->setVar('allRow',$allRow);
        return $view;
    }

    /**
     * Est utiliser pour la modification des données d'une humeur
     */
    public function update($pdo) {
        $view = new View("CheckYourMood/codeCYM/views/history");
        $pagination = HttpHelper::getParam('page');
        $time = HttpHelper::getParam("time");
        $libelle = HttpHelper::getParam("libelle");
        $desc = HttpHelper::getParam("desc");
        $changeTime = HttpHelper::getParam("change-time");
        $this->statsService->updateDesc($pdo, $time, $libelle, $desc,$_SESSION['UserID']);
        $this->statsService->updateTime($pdo, $time, $libelle, $changeTime,$_SESSION['UserID']);
        $resultats = $this->statsService->getHistorique($pdo, $pagination, $_SESSION['UserID']);
        $allRow = $this->statsService->getAllRow($pdo,$_SESSION['UserID']);
        $view->setVar('historyValue',$resultats);
        $view->setVar('allRow',$allRow);
        return $view;
    }
}