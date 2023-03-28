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
use services\StatsService;
use services\HumeursService;
use controllers\StatsController;
use yasmf\View;

class StatsControllerTest extends TestCase
{

    private StatsController $statsController;
    private StatsService $statsService;
    private HumeursService $humeursService;
    private PDO $pdo;
    private PDOStatement $pdoStatement1;
    private PDOStatement $pdoStatement2;

    public function setUp(): void
    {
        parent::setUp();
        session_destroy();

        // given a stats service and an humeurs service
        $this->statsService = $this->createStub(StatsService::class);
        $this->humeursService = $this->createStub(HumeursService::class);
        // and a pdo and a 2 pdo statement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement1 = $this->createStub(PDOStatement::class);
        $this->pdoStatement2 = $this->createStub(PDOStatement::class);
        // and a stats controller
        $this->statsController = new StatsController($this->statsService, $this->humeursService);
        $_SESSION['UserID'] = 1;

    }
    
    public function testUpdate()
    {
        // given an stats service and the method updateDesc will be used by the service
        // an stats service and the method updateTime will be used by the service
        // an stats service and the method getHistorique will be used by the service
        // an stats service and the method getAllRow will be used by the service

        $this->statsService->method('updateDesc')->willReturn('');
        $this->statsService->method('updateTime')->willReturn('');
        $this->statsService->method('getHistorique')->willReturn($this->pdoStatement1);
        $this->statsService->method('getAllRow')->willReturn($this->pdoStatement2);

        self::assertNotNull($this->statsService);
        self::assertNotNull($this->humeursService);
        self::assertNotNull($this->statsController);
        // when call to update
        $view = $this->statsController->update($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/history", $view->getRelativePath());
        // and the statement returned by the third service is set as a variable in the view
        self::assertSame($this->pdoStatement1, $view->getVar("historyValue"));
        // and the statement returned by the fourth service is set as a variable in the view
        self::assertSame($this->pdoStatement2, $view->getVar("allRow"));
    }

    public function testDeleteHumeur()
    {
        // given an stats service and the method delHumeur will be used by the service
        // an stats service and the method getHistorique will be used by the service
        // an stats service and the method getAllRow will be used by the service
        $this->statsService->method('delHumeur')->willReturn('');
        $this->statsService->method('getHistorique')->willReturn($this->pdoStatement1);
        $this->statsService->method('getAllRow')->willReturn($this->pdoStatement2);
        
        self::assertNotNull($this->statsService);
        self::assertNotNull($this->humeursService);
        self::assertNotNull($this->statsController);
        // when call to deleteHumeur
        $view = $this->statsController->deleteHumeur($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("/views/history", $view->getRelativePath());
        // and the statement returned by the second service is set as a variable in the view
        self::assertSame($this->pdoStatement1, $view->getVar("historyValue"));
        // and the statement returned by the third service is set as a variable in the view
        self::assertSame($this->pdoStatement2, $view->getVar("allRow"));
    }
    
}
