<?php

use services\RegisterService;
use yasmf\DataSource;
require_once 'services/registerservice.php';

class RegisterTest extends \PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private RegisterService $registerService;

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
        $this->registerService = new RegisterService();
    }

    /*
    public function testInsertUserValues() {
        try {
            // GIVEN : Une connexion a une base de données 
            $this->pdo->beginTransaction();

            // WHEN : On insère un compte dans la base de données
            $this->registerService->insertUserValues($this->pdo, 'Max','Max.max@gmail.com', '2002-10-14', 'Homme', 'pwd', 'pwd');
            // Et que l'on recherche ce compte
            $pwd = md5('pwd');
            $req = $this->pdo->query("SELECT * FROM user WHERE User_Name = 'Max' AND User_Email = 'Max.max@gmail.com'");
            $stringTest = "";
            while ($resultats = $req->fetch()) {
                $stringTest .= $resultats["User_Name"]."/";
                $stringTest .= $resultats["User_Email"]."/";
                $stringTest .= $resultats["User_BirthDate"]."/";
                $stringTest .= $resultats["User_Gender"]."/";
                $stringTest .= $resultats["User_Password"];
            }

            // THEN : On retrouve bien le compte crée précédemment
            $this->assertEquals($stringTest,"Max/Max.max@gmail.com/2002-10-14/Homme/$pwd");
            // WHEN : On veut créer une adresse mail déja existante THEN : On retourne un message d'erreur
            //$returnValue = $this->registerService->insertUserValues($this->pdo, 'Edouard','edouard.balladur@gmail.com', '2002-10-14', 'Homme', 'pwd', 'pwd');
            //$this->assertEquals($returnValue,"création du compte impossible (l'email est déjà utilisé) ou la base de données est inaccessible");
            // WHEN : On veut créer un compte avec des mots de passe différents THEN : On retourne un message d'erreur
            $returnValue = $this->registerService->insertUserValues($this->pdo, 'Max','Max.max@gmail.com', '2002-10-14', 'Homme', 'd', 'pwd');
            $this->assertEquals($returnValue,"Les deux mots de passe ne sont pas identique");
            // WHEN : On veut créer un compte avec une date de naissance supérieur ou égale à la date du jour THEN : On retourne un message d'erreur
            $returnValue = $this->registerService->insertUserValues($this->pdo, 'Jean','Jean.michel@gmail.com', '2048-06-05', 'Homme', 'pwd', 'pwd');
            $this->assertEquals($returnValue,"Date de naissance supérieur ou égale à la date du jour");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }*/

    public function testGetLoginIn() {
        try {
            // GIVEN : Une connexion a une base de données 
            $this->pdo->beginTransaction();

            
            // WHEN : On veut récupérer un login à partir de l'username et du mot de passe 
            $returnValue = $this->registerService->getLoginIn($this->pdo, 'Edouard', 'test1234');
            // THEN : On retrouve le login attendu
            $this->assertEquals($returnValue, '1');
            // WHEN : On veut récupérer un login à partir de l'username et d'un mot de passe incorrect
            $returnValue = $this->registerService->getLoginIn($this->pdo, 'Max', 'test');
            // THEN : On retourne un message d'erreur
            $this->assertEquals($returnValue, "Login invalide, identifiant ou mot de passe incorrect !");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    
    public function testGetDefaultRegisterService() {
        $returnValue = $this->registerService->getDefaultRegisterService();
        // EXPECTED: new RegisterService
        $this->assertEquals($returnValue, new RegisterService());
    }
}