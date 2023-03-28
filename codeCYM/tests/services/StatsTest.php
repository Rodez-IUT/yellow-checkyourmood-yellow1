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

            // GIVEN : Une connexion a une base de données et un ID 
            $id = 2;

            // WHEN : Je veux récupérer l'humeur que l'utilisateur a rentré le plus 
            $returnValue = $this->statsService->getMaxHumeur($this->pdo, $id);
            // THEN : On retourne un message d'erreur car l'utilisateur n'a rentré aucune erreur
            $this->assertEquals($returnValue,"Vous n'avez saisi aucune humeur");

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

            // GIVEN : Une connexion a une base de données et un ID 
            $id = 2;
            $humeur = 'Joie';
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux savoir si une humeur spécifique a été rentré entre un intervalle de date 
            $returnValue = $this->statsService->verifHumeurEstPresente($this->pdo, $dateFin, $dateFin, $humeur, $id);
            // THEN : On retourne faux car l'utilisateur n'a rentré aucune humeur
            $this->assertFalse($returnValue);

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

            // GIVEN : Une connexion a une base de données et un ID 
            $id = 2;
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux savoir si une humeur a été rentré entre un intervalle de date 
            $returnValue = $this->statsService->verifIsThere($this->pdo, $dateFin, $dateFin, $id);
            // THEN : On retourne faux car l'utilisateur n'a rentré aucune humeur
            $this->assertFalse($returnValue);
            
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

    public function testGetAllRow() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();
            $id = 1;
            
            // WHEN : Je veux récupèrer toutes les humeurs rentrés par l'utilisateur
            $returnValue = $this->statsService->getAllRow($this->pdo, $id);
            // THEN : Je retrouve le nombre attendu
            $this->assertEquals($returnValue, 5);
        
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetMostUsed() {
        try {
            // GIVEN : Une connexion a une base de données, un ID, une humeur et deux dates
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux récupèrer le nombre de fois qu'un utilisateur a eu une humeur entre 2 dates
            $returnValue = $this->statsService->getMostUsed($this->pdo, $dateDebut, $dateFin, $humeur, $id);
            // THEN : Je retrouve le nombre attendu
            $this->assertEquals(2, $returnValue[0][1]);

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetHumeurByTime() {
        try {
            // GIVEN : Une connexion a une base de données, un ID, une humeur et deux dates
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';
            $dateDebut = '2022-12-01 11:20:11';
            $dateFin = '2022-12-12 15:53:13';

            // WHEN : Je veux récupèrer le nombre de fois que l'utilisateur a eu une humeur entre 2 dates regroupé par jour
            $returnValue = $this->statsService->getHumeurByTime($this->pdo, $dateDebut, $dateFin, $humeur, $id);
            $stringTest = "";
            while($resultats = $returnValue->fetch()) {
                $stringTest .= $resultats["Humeur_Libelle"]."/"; 
                $stringTest .= $resultats["nombreHumeur"]."/"; 
                $stringTest .= $resultats["Date"]."/"; 
            }
            // THEN : Je récupère les humeurs par jour attendues
            $this->assertEquals($stringTest, "Joie/1/01/12/2022/Joie/1/12/12/2022/");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetNombreSaisiesHumeurSelectionnee() {
        try {
            // GIVEN : Une connexion a une base de données, un ID et une humeur 
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';

            // WHEN : Je veux récupèrer le nombre de saisies de l'humeur saisie par l'utilisateur
            $returnValue = $this->statsService->getNombreSaisiesHumeurSelectionnee($this->pdo, $humeur, $id);
            // THEN : Je récupère le nombre d'humeurs attendu
            $this->assertEquals($returnValue, 2);

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testDelHumeur() {
        try {
            // GIVEN : Une connexion a une base de données, un ID, une humeur et une date
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';
            $date = '2022-12-01 11:20:11';
            
            // WHEN : Je supprime une humeur saisi par l'utilisateur 
            $this->statsService->delHumeur($this->pdo, $date, $humeur, $id);
            // et que je cherche le nombre de cette humeur après la supression
            $req = $this->pdo->prepare("SELECT * FROM humeur WHERE Humeur_Libelle = :humeur AND CODE_User = :id");
            $req->execute(['humeur'=>$humeur, 'id'=>$id]);
            $resultats = $req->rowCount();
            
            // THEN : Je trouve le nombre d'humeur attendu
            $this->assertEquals($resultats, 1);

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }
    
    public function testUpdateDesc() {
        try {
            // GIVEN : Une connexion a une base de données, un ID, une humeur, une date et une description
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';
            $date = '2022-12-01 11:20:11';
            $description = 'Maintenant je suis joyeux !';

            // WHEN : Je modifie la description d'une humeur
            $this->statsService->updateDesc($this->pdo, $date, $humeur, $description, $id);
            // et que je récupère la description actuelle pour vérifier la modification
            $req = $this->pdo->prepare("SELECT Humeur_Description FROM Humeur WHERE Humeur_Libelle = :humeur AND CODE_User = :id");
            $resultats = $req->execute(['humeur'=>$humeur, 'id'=>$id]);
            // THEN : Je trouve bien la decription modifié antérieurement
            $this->assertEquals($description, $resultats);

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testUpdateTime() {
        try {
            // GIVEN : Une connexion a une base de données, un ID, une humeur et deux dates
            $this->pdo->beginTransaction();
            $id = 1;
            $humeur = 'Joie';
            $dateActuelle = '2022-12-01 11:20:11';
            $dateFinale = '2022-12-12 15:53:13';
    
            // WHEN : Je modifie la date de saisie d'une humeur 
            $this->statsService->updateTime($this->pdo, $dateActuelle, $humeur, $dateFinale, $id);
            // et que je verifie si elle a bien été modifié
            $req = $this->pdo->prepare("SELECT Humeur_Time FROM Humeur WHERE Humeur_Libelle = :humeur AND CODE_User = :id");
            $resultats = $req->execute(['humeur'=>$humeur, 'id'=>$id]);

            //THEN : Je retrouve bien l'heure modifié
            $this->assertEquals($resultats, $dateFinale);
            
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }


}