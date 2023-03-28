<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\AccountsService;
use controllers\AccountsController;
use yasmf\View;

class AccountsControllerTest extends TestCase
{

    private AccountsController $accountsController;
    private AccountsService $accountsService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        session_destroy();
        
        // given a accounts service
        $this->accountsService = $this->createStub(AccountsService::class);
        // and a pdo and a pdo statement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // and a accounts controller
        $this->accountsController = new AccountsController($this->accountsService);
        $_SESSION['UserID'] = 1;
    }

    public function testIndex()
    {
        // given an accounts service and the method getProfile will be used by the service
        $this->accountsService->method('getProfile')->willReturn($this->pdoStatement);
        self::assertNotNull($this->accountsService);
        self::assertNotNull($this->accountsController);
        // when call to index
        $view = $this->accountsController->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/Account", $view->getRelativePath());
        // and the statement returned by the service is set as a variable in the view
        self::assertSame($this->pdoStatement, $view->getVar("resultats"));
    }

    public function testgetDefaultProfile()
    {
        // given a view from editProfile function,
        // an accounts service and the method getProfile will be used by the service
        $view1 = $this->accountsController->editProfile($this->pdo);
        $this->accountsService->method('getProfile')->willReturn($this->pdoStatement);
        self::assertNotNull($this->accountsService);
        self::assertNotNull($this->accountsController);
        // when call to getDefaultProfile
        $view = $this->accountsController->getDefaultProfile($this->pdo, $view1);
        // then the view point to the expected view file
        self::assertEquals("/views/editprofile", $view->getRelativePath());
        // and the statement returned by the service is set as a variable in the view
        self::assertSame($this->pdoStatement, $view->getVar("verif"));
    }
    
    public function testEditPassword()
    {
        // given an accounts service and the method getPasswords will be used by the service
        $this->accountsService->method('getPasswords')->willReturn($this->pdoStatement);
        self::assertNotNull($this->accountsService);
        self::assertNotNull($this->accountsController);
        // when call to editPassword
        $view = $this->accountsController->editPassword($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/editpassword", $view->getRelativePath());
        // and the statement returned by the service is set as a variable in the view
        self::assertSame($this->pdoStatement, $view->getVar("stmt"));
        self::assertSame(0, $view->getVar("resetPwd"));
    }

    public function testDisconnect() 
    {
        self::assertNotNull($this->accountsController);
        // when call to disconnect
        $view = $this->accountsController->disconnect();
        // then the view point to the expected view file
        self::assertEquals("/views/index", $view->getRelativePath());    
    }

}
