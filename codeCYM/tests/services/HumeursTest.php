<?php

use services\HumeursService;
use yasmf\DataSource;
require_once 'services/humeursservice.php';

class HumeursTest extends \PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private HumeursService $humeursService;

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
        $this->humeursService = new HumeursService();
    }

    public function testGetDefaultHumeursService() {
            $returnValue = $this->humeursService->getDefaultHumeursService();
            // EXPECTED: new HumeursService
            $this->assertEquals($returnValue, new HumeursService());
    }


    /*
    public function testGetListeHumeurs() {

        // WHEN : Je veux récupérer la liste de toutes les humeurs
        $resultats = $this->humeursService->getListeHumeurs();
        $stringTest = "";
        foreach ($resultats as $val) {
            $stringTest .= $val."/";
        }

        // THEN : On recupère la liste des humeurs
        $this->assertEquals($stringTest, 'Admiration/Adoration/Appréciation esthétique/Amusement/Colère/Anxiété/Émerveillement/Malaise/Ennui/Calme/Confusion/Envie/Dégoût/Douleur empathique/Intérêt étonné, intrigué/Excitation/Peur/Horreur/Intérêt/Joie/Nostalgie/Soulagement/Romance/Tristesse/Satisfaction/Désir sexuel/Surprise/');

    }*/

    
    public function testSetHumeur() {
        try {
            // GIVEN : Une connexion a une base de données
            $this->pdo->beginTransaction();

            // WHEN : Je veux ajouter une humeur
            $resultats = $this->humeursService->setHumeur($this->pdo, 'Peur', '😨', "j'ai peur sah !", 1);
            $req = $this->pdo->query("SELECT * FROM humeur WHERE Humeur_Libelle = 'Peur' AND CODE_User = 1");
            $stringTest = "";
            while($resultats = $req->fetch()) {
                $stringTest .= $resultats["Humeur_Libelle"]."/";
                $stringTest .= $resultats["Humeur_Description"]."/";
                $stringTest .= $resultats["Humeur_Emoji"];
            }
            
            // THEN : Je recupère la derniere humeur contenant le libelle 'Peur'
            $this->assertEquals($stringTest,"Peur/j'ai peur sah !/😨");
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }
}