<?php

use services\StatsService;
use yasmf\DataSource;
require_once 'services/statsservice.php';

class StatsTest extends \PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private StatsService $statsService;

    /* Initializing the Db of test */
    public function setUp(): void
    {
        parent::setUp();
        // given a pdo for tests
        $datasource = new DataSource(
            $host = 'localhost',
            $port = 3306, # to change with the port your mySql server listen to
            $db_name = 'CYM_TEST', # to change with your db name
            $user = 'root', # to change with your db username
            $pass = 'root', # to change with your db password
            $charset = 'utf8mb4'
        );
        $this->pdo = $datasource->getPdo();
        // and an account service
        $this->statsService = new StatsService();
    }

    public function testGetHistorique() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();

            $returnValue = $this->statsService->getHistorique($this->pdo, 1, 1);
            $stringTest = "";
            while ($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats->Humeur_Libelle."/";
                $stringTest .= $resultats->Humeur_Emoji."/";
                $stringTest .= $resultats->Humeur_Description."/";
                $stringTest .= $resultats->Humeur_Time."/";
            }

            $this->assertEquals($stringTest, "Joie/😁//2022-12-12 15:53:13/Ennui/🥱//2022-12-12 08:53:13/Degout/🤢/je me sentais mal/2022-12-02 11:31:11/Joie/😁/je me sentais bien/2022-12-01 11:20:11/Anxiete/😣/je me sentais bien/2022-12-01 11:20:11/");
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetMaxHumeur() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();

            $returnValue = $this->statsService->getMaxHumeur($this->pdo, 1);
            $stringTest = "";
            while ($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats->Humeur_Libelle."/";
                $stringTest .= $resultats->compteur."/";
                $stringTest .= $resultats->Humeur_Emoji;
            }

            $this->assertEquals($stringTest,"Joie/2/😁");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetAllValueBetweenDates() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();

            $returnValue = $this->statsService->getAllValueBetweenDates($this->pdo, '2022-12-01 11:20:11', '2022-12-12 15:53:13', 1);
            $stringTest = "";

            while($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats->Humeur_Libelle."/";
                $stringTest .= $resultats->compteur."/";
            }
            
            $this->assertEquals($stringTest, "Joie/2/Anxiete/1/Degout/1/Ennui/1/");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

     public function testVerifHumeurEstPresente() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();

            $returnValue = $this->statsService->verifHumeurEstPresente($this->pdo, '2022-12-01 11:20:11', '2022-12-12 15:53:13','Joie', 1);

            $this->assertTrue($returnValue);

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testVerifIsThere() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();

            $returnValue = $this->statsService->verifIsThere($this->pdo, '2022-12-01 11:20:11', '2022-12-12 15:53:13', 1);

            $this->assertTrue($returnValue);
            
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    // public function testGetAllValue() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $returnValue = $statsService->getAllValue($pdo, 12);
    //     $stringTest = "";
    //     while($resultats = $returnValue->fetch()) {
    //         $stringTest .= $resultats->Humeur_Libelle."/";
    //         $stringTest .= $resultats->compteur."/";
    //     }

    //     $this->assertEquals($stringTest, "Joie/1/Colère/2/Nostalgie/1/");

    //     $pdo->rollBack();
    // }

    // public function testGetAllRow() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $returnValue = $statsService->getAllRow($pdo, 12);
    //     $this->assertEquals($returnValue, 4);
        
    //     $pdo->rollBack();
    // }

    // public function testGetMostUsed() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $returnValue = $statsService->getMostUsed($pdo, '2023-01-12 09:06:00', '2023-01-12 09:09:00','Colère', 12);
    //     $this->assertEquals($returnValue[1], 2);

    //     $pdo->rollBack();
    // }

    // public function testGetHumeurByTime() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $returnValue = $statsService->getHumeurByTime($pdo, '2023-01-12 09:06:00', '2023-01-12 09:09:00','Colère', 12);
    //     $stringTest = "";
    //     while($resultats = $returnValue->fetch()) {
    //         $stringTest .= $resultats->Humeur_Libelle."/"; 
    //         $stringTest .= $resultats->nombreHumeur."/"; 
    //         $stringTest .= $resultats->Date; 
    //     }

    //     $this->assertEquals($stringTest, "Colère/2/12/01/2023");

    //     $pdo->rollBack();
    // }

    // public function testGetNombreSaisiesHumeurSelectionnee() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $returnValue = $statsService->getNombreSaisiesHumeurSelectionnee($pdo, 'Colère', 12);
    //     $this->assertEquals($returnValue, 2);

    //     $pdo->rollBack();
    // }

    // public function testDelHumeur() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $statsService->delHumeur($pdo, '2023-01-12 09:06:30', 'Joie', 12);
    //     $req = $pdo->query("SELECT * FROM humeur WHERE Humeur_Libelle = 'Joie' AND CODE_User = 12");
    //     $resultats = $req->rowCount();

    //     $this->assertEquals($resultats, 0);

    //     $pdo->rollBack();
    // }
    
    // public function testUpdateDesc() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();

    //     $statsService->updateDesc($pdo, '2023-01-12 09:06:30', 'Joie', 'Maintenant je suis joyeux !', 12);
    //     $req = $pdo->query("SELECT Humeur_Description FROM Humeur WHERE Humeur_Libelle = 'Joie' AND CODE_User = 12");
    //     $returnValue = $req->fetch();
    //     $resultats = $returnValue->Humeur_Description;
    //     $this->assertEquals($resultats, 'Maintenant je suis joyeux !');

    //     $pdo->rollBack();
    // }

    // public function testUpdateTime() {
    //     // GIVEN : Une connexion a une base de données et un ID
    //     $this->pdo->beginTransaction();
    
    //     $statsService->updateTime($pdo, '2023-01-12 09:06:30', 'Joie', '2023-01-12 10:06:30', 12);
    //     $req = $pdo->query("SELECT Humeur_Time FROM Humeur WHERE Humeur_Libelle = 'Joie' AND CODE_User = 12");
    //     $returnValue = $req->fetch();
    //     $resultats = $returnValue->Humeur_Time;
    //     $this->assertEquals($resultats, '2023-01-12 10:06:30');
        
    //     $pdo->rollBack();
    // }

}