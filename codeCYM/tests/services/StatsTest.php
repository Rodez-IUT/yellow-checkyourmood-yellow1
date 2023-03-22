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
            $id = 1;

            // WHEN : Je veux récupérer l'historique des humeurs correspondant a l'ID 1
            $returnValue = $this->statsService->getHistorique($this->pdo, 1, $id);
            $stringTest = "";
            while ($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats["Humeur_Libelle"]."/";
                $stringTest .= $resultats["Humeur_Emoji"]."/";
                $stringTest .= $resultats["Humeur_Description"]."/";
                $stringTest .= $resultats["Humeur_Time"]."/";
            }

            // THEN : On retrouve bien l'historique des humeurs attendus
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
            $id = 1;

            // WHEN : Je veux récupérer l'humeur que l'utilisateur a rentré le plus 
            $returnValue = $this->statsService->getMaxHumeur($this->pdo, $id);
            $stringTest = "";
            while ($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats["Humeur_Libelle"]."/";
                $stringTest .= $resultats["compteur"]."/";
                $stringTest .= $resultats["Humeur_Emoji"];
            }
            // THEN : On retrouve bien l'humeur attendu
            $this->assertEquals($stringTest,"Joie/2/😁");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetAllValueBetweenDates() {
        try {
            // GIVEN : Une connexion a une base de données, un ID et deux dates
            $this->pdo->beginTransaction();
            $id = 1;
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux récupérer toutes les humeurs que l'utilisateur a rentré entre un intervalle de date
            $returnValue = $this->statsService->getAllValueBetweenDates($this->pdo, $dateDebut, $dateFin, $id);
            $stringTest = "";

            while($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats["Humeur_Libelle"]."/";
                $stringTest .= $resultats["compteur"]."/";
            }
            // THEN : On retrouve bien les humeurs attendu
            $this->assertEquals($stringTest, "Joie/2/Anxiete/1/Degout/1/Ennui/1/");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

     public function testVerifHumeurEstPresente() {
        try {
            // GIVEN : Une connexion a une base de données, un ID, une humeur et deux dates
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux savoir si une humeur spécifique a été rentré entre un intervalle de date
            $returnValue = $this->statsService->verifHumeurEstPresente($this->pdo, $dateFin, $dateFin, $humeur, $id);
            // THEN : On retrouve bien que l'humeur est présente
            $this->assertTrue($returnValue);

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testVerifIsThere() {
        try {
            // GIVEN : Une connexion a une base de données, un ID et deux dates
            $this->pdo->beginTransaction();
            $id = 1;
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux savoir si une humeur a été rentré entre un intervalle de date
            $returnValue = $this->statsService->verifIsThere($this->pdo, $dateDebut, $dateFin, $id);
            // THEN : On retrouve bien que au moins une humeur est présente
            $this->assertTrue($returnValue);
            
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetAllValue() {
        try {
        // GIVEN : Une connexion a une base de données et un ID
        $this->pdo->beginTransaction();
        $id = 1;

        // WHEN : Je veux récupérer toutes les humeurs que l'utilisateur a rentré 
        $returnValue = $this->statsService->getAllValue($this->pdo, $id);
        $stringTest = "";
        while($resultats = $returnValue->fetch()) {
            $stringTest .= $resultats["Humeur_Libelle"]."/";
            $stringTest .= $resultats["compteur"]."/";
        }
        // THEN : On retrouve bien les humeurs attendu
        $this->assertEquals($stringTest, "Joie/2/Anxiete/1/Degout/1/Ennui/1/");

        $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }


}