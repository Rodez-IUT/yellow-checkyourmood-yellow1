<?php

use services\AccountsService;
use yasmf\DataSource;
require_once 'services/accountsservice.php';

class AccountsTest extends \PHPUnit\Framework\TestCase
{
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
        // Given the database initialized and  
        $this->pdo->beginTransaction();
        // When we check a specific account
        $resultats = $this->accountsService->getProfile($this->pdo, 1);
        $stringTest = "";
        while ($row = $resultats->fetch()) {
            $stringTest .= $row->User_ID."/";
            $stringTest .= $row->User_Name."/";
            $stringTest .= $row->User_Email."/";
            $stringTest .= $row->User_BirthDate."/";
            $stringTest .= $row->User_Gender."/";
            $stringTest .= $row->User_Password;
        }
        // Then we found the account expected
        $this->assertEquals("1/Edouard/edouard.balladur@gmail.com/1929-09-12/Homme/dfsdf22324fdf43", $stringTest);
            $this->pdo->rollBack();
        } catch (PDOException) {
            $this->pdo->rollBack();
        }
    }
}
