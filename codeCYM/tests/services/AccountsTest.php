<?php

use services\AccountsService;
use yasmf\DataSource;
require_once 'services/accountsservice.php';

class AccountTest extends \PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private AccountsService $accountsService;

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
        $this->accountsService = new AccountsService();
    }

    public function testGetProfile() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();
            
            // WHEN : Je veux récupérer le profil correspondant a l'ID
            $resultats = $this->accountsService->getProfile($this->pdo, 1);
            $stringTest = "";
            while ($row = $resultats->fetch()) {
                $stringTest .= $row->User_ID."/";
                $stringTest .= $row->User_Name."/";
                $stringTest .= $row->User_Email."/";
                $stringTest .= $row->User_BirthDate."/";
                $stringTest .= $row->User_Gender."/";
                $stringTest .= $row->User_Password."/";
                $stringTest .= $row->APIKEY;
            }

            // THEN : Me renvoie le bon compte
            $this->assertEquals("1/Edouard/edouard.balladur@gmail.com/1929-09-12/Homme/16d7a4fca7442dda3ad93c9a726597e4/e55cf8791cf43b0b9d1d9901739370ac", $stringTest);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
        
    }

    public function testGetEmail() {
        try {
            // GIVEN : Une connexion a une base de données et UserEmail
            $this->pdo->beginTransaction();
            $aTester = "edouard.balladur@gmail.com";

            // WHEN : Je veux vérifier si l'email est existant
            $resultats = $this->accountsService->getEmails($this->pdo, $aTester);
            $val = $resultats->fetchColumn();

            // THEN : Renvoie l'email 
            $this->assertEquals("edouard.balladur@gmail.com", $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetUserName() {
        try {
            // GIVEN : Une connexion a une base de données et un UserName
            $this->pdo->beginTransaction();
            $aTester = "Edouard";

            // WHEN : Je veux vérifier si le nom d'utilisateur est existant
            $resultats = $this->accountsService->getUsernames($this->pdo, $aTester);
            $val = $resultats->fetchColumn();

            // THEN : Renvoie le nom d'utilisateur
            $this->assertEquals("Edouard", $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }

    public function testGetPassword() {
        try {
            // GIVEN : Une connexion a une base de données et un ID de compe
            $this->pdo->beginTransaction();

            // WHEN : je veux recupérer le mot de passe correspondant a un User_ID
            $resultats = $this->accountsService->getPasswords($this->pdo, 1);
            $val = $resultats->fetchColumn();

            // THEN : Renvoie le mdp sous la forme md5
            $this->assertEquals("16d7a4fca7442dda3ad93c9a726597e4", $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }
}