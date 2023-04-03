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
use services\RegisterService;
use services\AccountsService;
use controllers\RegisterController;
use yasmf\View;

class RegisterControllerTest extends TestCase
{

    private RegisterController $registerController;
    private RegisterService $registerService;
    private AccountsService $accountsService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        session_destroy();

        // given a register service and an accounts service
        $this->registerService = $this->createStub(RegisterService::class);
        $this->accountsService = $this->createStub(AccountsService::class);
        // and a pdo and a pdo statement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // and a register controller
        $this->registerController = new RegisterController($this->registerService, $this->accountsService);
        $_SESSION['UserID'] = 1;
    }
    
    public function testIndex()
    {
        self::assertNotNull($this->accountsService);
        self::assertNotNull($this->registerService);
        self::assertNotNull($this->registerController);
        // when call to index
        $view = $this->registerController->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/Account", $view->getRelativePath());
    }

    public function testRegister()
    {
        $_GET['username'] = '';
        $_GET['email'] = '';
        $_GET['birthDate'] = '';
        $_GET['gender'] = '';
        $_GET['password'] = '';
        $_GET['confirmPassword'] = '';
        $_GET['login'] = '';

        // given an register service and the method insertUserValues will be used by the service
        $this->registerService->method('insertUserValues')->willReturn('');
        self::assertNotNull($this->accountsService);
        self::assertNotNull($this->registerService);
        self::assertNotNull($this->registerController);
        // when call to register
        $view = $this->registerController->register($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/Register", $view->getRelativePath());
        // and the string is set as a variable in the view 
        self::assertSame('Au moins un des champs n\'est pas rempli', $view->getVar("registerError"));
    } 
}
