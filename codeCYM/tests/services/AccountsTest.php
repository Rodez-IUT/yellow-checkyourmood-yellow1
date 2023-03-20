<?php

use services\AccountsService;
use yasmf\DataSource;
require_once 'services/accountsservice.php';

class AccountsTest extends \PHPUnit\Framework\TestCase {

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
            $pwd = md5('16d7a4fca7442dda3ad93c9a726597e4');
            $this->assertEquals("1/Edouard/edouard.balladur@gmail.com/1929-09-12/Homme/".$pwd."/e55cf8791cf43b0b9d1d9901739370ac", $stringTest);
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
            $this->assertEquals(md5("16d7a4fca7442dda3ad93c9a726597e4"), $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }
    
    public function testEditPassword() {

        try {
            // GIVEN : Une connexion a une base de données , un nouveau mot de passe et un ID
            $this->pdo->beginTransaction();
            $newPassword = "test5";

            // WHEN : on modifie le mot de passe avec le nouveau
            $this->accountsService->editPassword($this->pdo, $newPassword, 1);
            $resultats = $this->pdo->query("SELECT User_Password FROM user WHERE User_Password = 'e3d704f3542b44a621ebed70dc0efe13'");
            $val = $resultats->fetchColumn();

            // THEN : Me renvoie le nouveau mot de passe
            $this->assertEquals(md5($newPassword), $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }   
    }
    
    public function testEditEmail() {
        try {
            // GIVEN : Une connexion a une base de données , un nouveau email et un ID
            $this->pdo->beginTransaction();
            $newMail = "cym@gmail.com";

            // WHEN : on modifie l'email avec le nouveau
            $this->accountsService->editMail( $this->pdo, $newMail, 1);
            $resultats = $this->pdo->query("SELECT User_Email FROM user WHERE User_Email = 'cym@gmail.com'");
            $val = $resultats->fetchColumn();

            // THEN : Me renvoie le nouveau email
            $this->assertEquals($newMail, $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }   
    }

    public function testEditUserName() {
        try {
            // GIVEN : Une connexion a une base de données , un nouveau UserName et un ID
            $this->pdo->beginTransaction();
            $newUsername = "CYM";

            // WHEN : on modifie le UserName avec le nouveau
            $this->accountsService->editUsername( $this->pdo, $newUsername, 1);
            $resultats = $this->pdo->query("SELECT User_Name FROM user WHERE User_Name = 'CYM'");
            $val = $resultats->fetchColumn();

            // THEN : Me renvoie le nouveau UserName
            $this->assertEquals($newUsername, $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }  
    }

    public function testEditBirthDate() {
        try {
            // GIVEN : Une connexion a une base de données , une nouvelle date de naissance et un ID
            $this->pdo->beginTransaction();
            $newBirthDate = "2007-01-05";

            // WHEN : on modifie la date de naissance avec la nouvelle
            $this->accountsService->editBirthDate( $this->pdo, $newBirthDate, 1);
            $resultats = $this->pdo->query("SELECT User_BirthDate FROM user WHERE User_BirthDate = '2007-01-05'");
            $val = $resultats->fetchColumn();

            // THEN : Me renvoie la nouvelle date de naissance
            $this->assertEquals($newBirthDate, $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }  
    }

    public function testEditGender() {
        try {
            // GIVEN : Une connexion a une base de données , un nouveau genre et un ID
            $this->pdo->beginTransaction();
            $newGender = "Femme";

            // WHEN : on modifie le genre avec le nouveau
            $this->accountsService->editGender($this->pdo, $newGender, 1);
            $resultats = $this->pdo->query("SELECT User_Gender FROM user WHERE User_Gender = 'Femme'");
            $val = $resultats->fetchColumn();

            // THEN : Me renvoie le nouveau genre
            $this->assertEquals($newGender, $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }  
    }

    public function testDeleteProfile() {
        try {
            // GIVEN : Une connexion a une base de données et un ID
            $this->pdo->beginTransaction();

            // WHEN : on supprime le compte
            $this->accountsService->deleteProfile($this->pdo, 1);
            $resultats = $this->pdo->query("SELECT * FROM user WHERE User_ID = 1");
            $val = $resultats->rowCount();

            // THEN : on renvoie 0 si le compte est supprimé
            $this->assertEquals(0, $val);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }  
    }
}