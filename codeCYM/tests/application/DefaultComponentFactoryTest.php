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

namespace application;

use controllers\HomeController;
use controllers\AccountsController;
use controllers\HumeursController;
use controllers\RegisterController;
use controllers\StatsController;
use services\AccountsService;
use services\HumeursService;
use services\RegisterService;
use services\StatsService;
use PHPUnit\Framework\TestCase;
use yasmf\NoControllerAvailableForName;
use yasmf\NoServiceAvailableForName;


class DefaultComponentFactoryTest extends TestCase
{

    private DefaultComponentFactory $componentFactory;

    public function setUp(): void
    {
        parent::setUp();
        // given a component factory
        $this->componentFactory = new DefaultComponentFactory();
    }

    public function testBuildControllerByName_Home()
    {
        // when ask for Home controller
        $controller = $this->componentFactory->buildControllerByName("Home");
        // then the controller is HomeController instance
        self::assertInstanceOf(HomeController::class,$controller);
    }

    public function testBuildControllerByName_Homeh()
    {
        // when ask for home controller
        $controller = $this->componentFactory->buildControllerByName("home");
        // then the controller is HomeController instance
        self::assertInstanceOf(HomeController::class,$controller);
    }

    public function testBuildControllerByName_accounts()
    {
        // when ask for home controller
        $controller = $this->componentFactory->buildControllerByName("accounts");
        // then the controller is HomeController instance
        self::assertInstanceOf(AccountsController::class,$controller);
    }

    public function testBuildControllerByName_humeurs()
    {
        // when ask for home controller
        $controller = $this->componentFactory->buildControllerByName("humeurs");
        // then the controller is HomeController instance
        self::assertInstanceOf(HumeursController::class,$controller);
    }

    public function testBuildControllerByName_register()
    {
        // when ask for home controller
        $controller = $this->componentFactory->buildControllerByName("register");
        // then the controller is HomeController instance
        self::assertInstanceOf(RegisterController::class,$controller);
    }

    public function testBuildControllerByName_stats()
    {
        // when ask for home controller
        $controller = $this->componentFactory->buildControllerByName("stats");
        // then the controller is HomeController instance
        self::assertInstanceOf(StatsController::class,$controller);
    }

    public function testBuildControllerByName_Other()
    {
        // expected exception when ask for a non-existant controller
        $this->expectException(NoControllerAvailableForName::class);
        $controller = $this->componentFactory->buildControllerByName("NoController");
    }

    public function testBuildServiceByName_accounts()
    {
        // when ask for user service
        $service = $this->componentFactory->buildServiceByName("accounts");
        // then the service is UsersService instance
        self::assertInstanceOf(AccountsService::class,$service);
    }

    public function testBuildServiceByName_humeurs()
    {
        // when ask for user service
        $service = $this->componentFactory->buildServiceByName("humeurs");
        // then the service is UsersService instance
        self::assertInstanceOf(HumeursService::class,$service);
    }

    public function testBuildServiceByName_register()
    {
        // when ask for user service
        $service = $this->componentFactory->buildServiceByName("register");
        // then the service is UsersService instance
        self::assertInstanceOf(RegisterService::class,$service);
    }

    public function testBuildServiceByName_stats()
    {
        // when ask for user service
        $service = $this->componentFactory->buildServiceByName("stats");
        // then the service is UsersService instance
        self::assertInstanceOf(StatsService::class,$service);
    }

    public function testBuildServiceByName_Other()
    {
        // expected exception when ask for a non-existant service
        $this->expectException(NoServiceAvailableForName::class);
        $this->componentFactory->buildServiceByName("NoService");
    }
}