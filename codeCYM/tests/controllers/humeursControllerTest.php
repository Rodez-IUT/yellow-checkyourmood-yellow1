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
use services\HumeursService;
use controllers\HumeursController;
use yasmf\View;

class HumeursControllerTest extends TestCase
{

    private HumeursController $humeursController;
    private HumeursService $humeursService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;
    private $test = '';

    public function setUp(): void
    {
        parent::setUp();
        session_destroy();

        // given a humeurs service
        $this->humeursService = $this->createStub(HumeursService::class);
        // and a pdo and a pdo statement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // and a humeurs controller
        $this->humeursController = new HumeursController($this->humeursService);
        $_SESSION['msgHumeur'] = '';
        $_SESSION['UserID'] = 1;
    }
    
    public function testIndex_withNoSESSIONUserIDAndSESSIONmsgHumeur()
    {
        // given an humeurs service and the method getListeHumeurs will be used by the service
        // and a session variable will be used for test the returned statement
        $this->humeursService->method('getListeHumeurs')->willReturn($this->pdoStatement);
        $_SESSION['msgHumeur'] = 'Votre humeur a bien été ajoutée.';
        self::assertNotNull($this->humeursService);
        self::assertNotNull($this->humeursController);
        // when call to index
        $view = $this->humeursController->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/Humeurs", $view->getRelativePath());
        // and the statement returned by the service is set as a variable in the view
        self::assertSame('Votre humeur a bien été ajoutée.', $view->getVar("msgHumeur"));
    }

    public function testSetHumeur() 
    {
        $_GET['description'] = 'test';
        // given an humeurs service and the method setHumeur will be used by the service
        $this->humeursService->method('setHumeur')->willReturn('');
        self::assertNotNull($this->humeursService);
        self::assertNotNull($this->humeursController);
        // when call to setHumeur
        $view = $this->humeursController->setHumeur($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/Humeurs", $view->getRelativePath());
    }
}