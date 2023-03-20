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

    public function testInsertUserValues() {
        try {
            // GIVEN : Une connexion a une base de données 
            $this->pdo->beginTransaction();

            // WHEN :
            $this->registerService->insertUserValues($this->pdo, 'Max','Max.max@gmail.com', '2002-10-14', 'Homme', 'pwd', 'pwd');
            $pwd = md5('pwd');
            $req = $this->pdo->query("SELECT * FROM user WHERE User_Name = 'Max' AND User_Email = 'Max.max@gmail.com'");
            $stringTest = "";
            while ($resultats = $req->fetch()) {
                $stringTest .= $resultats->User_Name."/";
                $stringTest .= $resultats->User_Email."/";
                $stringTest .= $resultats->User_BirthDate."/";
                $stringTest .= $resultats->User_Gender."/";
                $stringTest .= $resultats->User_Password;
            }

            // THEN :
            //$this->assertEquals($stringTest,"Max/Max.max@gmail.com/2002-10-14/Homme/$pwd");
            $returnValue = $this->registerService->insertUserValues($this->pdo, 'Axel','Max.max@gmail.com', '2002-10-14', 'Homme', 'pwd', 'pwd');
            //$this->assertEquals($returnValue,"création du compte impossible (le nom d'utilisateur est indisponible ou l'email est déjà utilisé) ou la base de données est inaccessible");
            $returnValue = $this->registerService->insertUserValues($this->pdo, 'Max','Max.max@gmail.com', '2002-10-14', 'Homme', 'd', 'pwd');
            $this->assertEquals($returnValue,"Les deux mots de passe ne sont pas identique");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetLoginIn() {
        try {
            // GIVEN : Une connexion a une base de données 
            $this->pdo->beginTransaction();

            $returnValue = $this->registerService->getLoginIn($this->pdo, 'Edouard', '16d7a4fca7442dda3ad93c9a726597e4');
            $this->assertEquals($returnValue, '1');
            $returnValue = $this->registerService->getLoginIn($this->pdo, 'Max', 'test');
            $this->assertEquals($returnValue, "Login invalide, identifiant ou mot de passe incorrect !");

            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetDefaultRegisterService() {
        $serviceRegister = new RegisterService; 

        $returnValue = $serviceRegister->getDefaultRegisterService();
        $this->assertEquals($returnValue, new RegisterService());
    }
}