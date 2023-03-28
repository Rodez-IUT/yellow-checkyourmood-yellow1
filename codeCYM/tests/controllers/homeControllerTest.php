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
use services\UsersService;

class HomeControllerTest extends TestCase
{

    private HomeController $homeController;
    private PDO $pdo;

    public function setUp(): void
    {
        parent::setUp();

        // given a pdo 
        $this->pdo = $this->createStub(PDO::class);
        // and a home controller
        $this->homeController = new HomeController();
    }

    public function testIndex()
    {
        self::assertNotNull($this->homeController);
        // when call to index
        $view = $this->homeController->index();
        // then the view point to the expected view file
        self::assertEquals("/views/index", $view->getRelativePath());
    }
}
