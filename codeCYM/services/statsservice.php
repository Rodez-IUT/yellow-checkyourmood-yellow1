<?php

namespace services;

use PDOException;

class StatsService
{
    /**
     * Récupère l'historique de toutes les humeurs de l'utilisateur
     * @param $pdo  la connexion à la base de données
     * @param $pagination numéro de la page  
     * @return $resultats  le résultat de la requête (toutes les humeurs entrées par un utilisateur)
     */
    public function getHistorique($pdo, $pagination, $id) {
        $requete = 'SELECT Humeur_TimeConst, CODE_User, Humeur_Libelle, Humeur_Emoji, Humeur_Time, Humeur_Description FROM Humeur WHERE CODE_User = :id ORDER BY Humeur_Time DESC LIMIT 15 OFFSET :pagination';
        $resultats = $pdo->prepare($requete);
        $resultats->execute(['id'=>$id,'pagination'=>($pagination - 1) * 15]);
        return $resultats;
    }
    
    /**
     * Récupère l'humeur qui apparait le plus ainsi que 
     * le nombre de fois où l'humeur a été saisie 
     * @param $pdo  la connexion à la base de données
     * @return $req  le résultat de la requête
     */
    public function getMaxHumeur($pdo, $id) {
        $req =$pdo->prepare("SELECT Humeur_Libelle, COUNT(Humeur_Libelle) as compteur, Humeur_Emoji from humeur join user ON user.User_ID = humeur.CODE_USER WHERE CODE_User = :id GROUP BY Humeur_Libelle ORDER BY compteur DESC LIMIT 1");
        $req->execute(['id'=>$id]);
        if($req->rowCount() == 0) {
            return "Vous n'avez saisi aucune humeur";
        }
        return $req;
    }

    /**
     * Récupère le nombre total d'humeur saisie entre 2 intervales de temps
     * @param $pdo  la connexion à la base de données
     * @return $req  le résultat de la requête
     */
    public function getAllValueBetweenDates($pdo, $startDate, $endDate, $id) {
        $req = $pdo->prepare("SELECT Humeur_Libelle, COUNT(Humeur_Libelle) as compteur from humeur join user ON user.User_ID = humeur.CODE_USER WHERE CODE_User = :id AND Humeur_Time BETWEEN :startDate AND :endDate GROUP BY Humeur_Libelle");
        $req->bindParam('id', $id);
        $req->bindParam('startDate', $startDate);
        $req->bindParam('endDate', $endDate);
        $req->execute();
        return $req;
    }

    /**
     * @param $pdo  la connexion à la base de données
     * @return True si l'humeur à été saisie entre 2 intervales de temps
     * @return False sinon
     */
    public function verifHumeurEstPresente($pdo, $startDate, $endDate, $humeur,$id) {
        $req =$pdo->prepare("SELECT * from humeur join user ON user.User_ID = humeur.CODE_USER WHERE CODE_User = :id AND Humeur_Libelle = :humeur AND Humeur_Time BETWEEN :startDate AND :endDate GROUP BY Humeur_Libelle");
        $req->bindParam('id', $id);
        $req->bindParam('startDate', $startDate);
        $req->bindParam('endDate', $endDate);
        $req->bindParam('humeur', $humeur);
        $req->execute();
        $count = $req->rowCount();
        if ($count == 0) {
            return false;
        }
        return true;
    }

    /**
     * Vérifie si une humeur a déja été saisie entre 2 intervalle de temps 
     * @param $pdo  la connexion à la base de données
     * @return True Si une humeur à déja été saisie
     * @return False sinon
     */
    public function verifIsThere($pdo, $startDate, $endDate, $id) {
        $req =$pdo->prepare("SELECT * from humeur join user ON user.User_ID = humeur.CODE_USER WHERE CODE_User = :id AND Humeur_Time BETWEEN :startDate AND :endDate GROUP BY Humeur_Libelle");
        $req->bindParam('id', $id);
        $req->bindParam('startDate', $startDate);
        $req->bindParam('endDate', $endDate);
        $req->execute();
        $count = $req->rowCount();
        if ($count == 0) {
            return false;
        }
        return true;
    }

    /**
     * Récupère le nombre de fois qu'un utilisateur à saisi chaque humeur
     * @param $pdo  la connexion à la base de données
     * @return $req  le résultat de la requête
     */
    public function getAllValue($pdo, $id) {
        $req = $pdo->prepare("SELECT Humeur_Libelle, COUNT(Humeur_Libelle) as compteur from humeur join user ON user.User_ID = humeur.CODE_USER WHERE CODE_User = :id GROUP BY Humeur_Libelle");
        $req->execute(['id'=>$id]);
        return $req;
    }

    /**
     * Récupère le nombre d'humeurs qu'un utilisateur a saisie
     * @param $pdo  la connexion à la base de données
     * @return $allRow  le résultat de la requête converti en int
     */
    public function getAllRow($pdo, $id) {
        $req = $pdo->prepare ("SELECT COUNT(*) AS allRow FROM humeur WHERE CODE_User = :id");
        $req->execute(['id'=>$id]);
        $splitResult = $req->fetchColumn();
        $allRow = (int) $splitResult;
        return $allRow;
    }

    /**
     * Récupère le nombre de fois qu'un utilisateur a eu une humeur entre 2 dates
     * @param $pdo  la connexion à la base de données
     * @param $startDate  la date de début choisit par l'utilisateur
     * @param $endDate  la date de fin choisit par l'utilisateur
     * @param $humeurs  l'humeur choisit par l'utilisateur
     * @return $result  le résultat de la requête
     */
    public function getMostUsed($pdo, $startDate, $endDate, $humeurs, $id) {
        $result = "";
        if ($humeurs == "TOUS") {
            $req = $pdo->prepare ("SELECT COUNT(*) AS 'NB_Humeur' FROM `humeur` WHERE `CODE_User` = :id AND `Humeur_Time` <= :endDate AND `Humeur_Time` >= :startDate");
            $req->execute(['id'=>$id, 'startDate'=>$startDate, 'endDate'=>$endDate]);
            while ($row = $req->fetch()) {
                $result = [$row->NB_Humeur];
            }
        } else {
            $req = $pdo->prepare ("SELECT COUNT(`Humeur_Libelle`) AS 'NB_Humeur', `Humeur_Emoji` AS 'Emoji' FROM `humeur` WHERE `CODE_User` = :id AND `Humeur_Libelle` = :libelle AND `Humeur_Time` <= :endDate AND `Humeur_Time` >= :startDate GROUP BY `Humeur_Libelle`");
            $req->execute(['id'=>$id, 'libelle'=>$humeurs, 'startDate'=>$startDate, 'endDate'=>$endDate]);
            while ($row = $req->fetch()) {
                $result = [$row->Emoji, $row->NB_Humeur];
            }
        }
        return $result;
    }

    /**
     * Récupère le nombre de fois qu'un utilisateur a eu une humeur entre 2 dates regroupé par jour
     * @param $pdo  la connexion à la base de données
     * @param $startDate  la date de début choisit par l'utilisateur
     * @param $endDate  la date de fin choisit par l'utilisateur
     * @param $humeurs  l'humeur choisit par l'utilisateur
     * @return $req  le résultat de la requête
     */
    public function getHumeurByTime($pdo, $startDate, $endDate, $humeurs, $id) {
        $req = $pdo->prepare("SELECT count(*) as nombreHumeur, Humeur_Libelle, DATE_FORMAT(Humeur_Time, '%d/%m/%Y') as Date from humeur where code_User=:id AND Humeur_Libelle = :libelle and Humeur_Time BETWEEN :startDate AND :endDate and Humeur_time GROUP BY (SELECT DATE_FORMAT(Humeur_Time, '%d/%m/%y'))");
        $req->execute(['id'=>$id, 'libelle'=>$humeurs, 'startDate'=>$startDate, 'endDate'=>$endDate]);
        return $req;
    }


    /**
     * récupère le nombre de saisies de l'humeur saisie par l'utilisateur
     */
    public function getNombreSaisiesHumeurSelectionnee($pdo, $humeurSelectionnee,$id) {
        $nombreSaisiesHumeurSelectionnee = "SELECT humeur_libelle FROM humeur WHERE code_user = :code_user AND humeur_libelle = :humeur_libelle";
        $nombreSaisiesHumeurSelectionnee = $pdo -> prepare($nombreSaisiesHumeurSelectionnee);
        $nombreSaisiesHumeurSelectionnee -> bindParam('humeur_libelle', $humeurSelectionnee);
        $nombreSaisiesHumeurSelectionnee -> bindParam('code_user', $id);
        $nombreSaisiesHumeurSelectionnee -> execute();
        $nombreSaisiesHumeurSelectionnee = $nombreSaisiesHumeurSelectionnee -> rowCount();
        return $nombreSaisiesHumeurSelectionnee;
    }

    public function delHumeur($pdo, $time, $libelle,$id) {
        $req = $pdo->prepare('DELETE FROM humeur WHERE Humeur_Time = :time AND Humeur_Libelle = :libelle AND CODE_User = :id');
        $req->bindParam('time', $time);
        $req->bindParam('libelle', $libelle);
        $req->bindParam('id', $id);
        $req->execute();
    }
    
    public function updateDesc($pdo, $time, $libelle, $desc, $id) {
        $req = $pdo->prepare('UPDATE humeur SET Humeur_Description = :desc WHERE CODE_User = :id AND Humeur_Time = :time AND Humeur_Libelle = :libelle');
        $req->bindParam('time', $time);
        $req->bindParam('libelle', $libelle);
        $req->bindParam('desc', $desc);
        $req->bindParam('id', $id);
        $req->execute();
    }

    public function updateTime($pdo, $time, $libelle, $changeTime, $id) {
        $req = $pdo->prepare('UPDATE humeur SET Humeur_Time = :changeTime WHERE CODE_User = :id AND Humeur_Time = :time AND Humeur_Libelle = :libelle');
        $req->bindparam('changeTime', $changeTime);
        $req->bindparam('id', $id);
        $req->bindparam('time', $time);
        $req->bindparam('libelle', $libelle);
        $req->execute();
    }

    /* Singleton d'instanciation */
    private static $defaultStatsService ;
    public static function getDefaultStatsService()
    {
        if (StatsService::$defaultStatsService == null) {
            StatsService::$defaultStatsService = new StatsService();
        }
        return StatsService::$defaultStatsService;
    }
}