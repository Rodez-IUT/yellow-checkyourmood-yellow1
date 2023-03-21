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
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForName;
use yasmf\NoServiceAvailableForName;

/**
 *  The controller factory
 */
class DefaultComponentFactory implements ComponentFactory
{
    private ?AccountsService $accountsService = null;
    private ?HumeursService $humeursService = null;
    private ?RegisterService $registersService = null;
    private ?StatsService $statsService = null;



    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws NoControllerAvailableForName when controller is not found
     */
    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "home" => $this->buildHomeController(),
            "accounts" => $this->buildAccountController(),
            "humeurs" => $this->buildHumeurController(),
            "register" => $this->buildRegisterController(),
            "stats" => $this->buildStatsController(),
            default => throw new NoControllerAvailableForName($controller_name)
        };
    }

    /**
     * @param string $service_name the name of the service
     * @return mixed the created service
     * @throws NoServiceAvailableForName when service is not found
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return match($service_name) {
            "accounts" => $this->buildAccountsService(),
            "humeurs" => $this->buildHumeursService(),
            "register" => $this->buildRegisterService(),
            "stats" => $this->buildStatsService(),
            default => throw new NoServiceAvailableForName($service_name)
        };
    }

    /**
     * @return AccountsService
     */
    private function buildAccountsService(): AccountsService
    {
        if ($this->accountsService == null) {
            $this->accountsService = new AccountsService();
        }
        return $this->accountsService;
    }

    /**
     * @return HumeursService
     */
    private function buildHumeursService(): HumeursService
    {
        if ($this->humeursService == null) {
            $this->humeursService = new HumeursService();
        }
        return $this->humeursService;
    }

    /**
     * @return RegisterService
     */
    private function buildRegisterService(): RegisterService
    {
        if ($this->registersService == null) {
            $this->registersService = new RegisterService();
        }
        return $this->registersService;
    }

    /**
     * @return StatsService
     */
    private function buildStatsService(): StatsService
    {
        if ($this->statsService == null) {
            $this->statsService = new StatsService();
        }
        return $this->statsService;
    }

    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController();
    }

    /**
     * @return AccountsController
     */
    private function buildAccountController(): AccountsController
    {
        return new AccountsController($this->buildAccountsService());
    }

    /**
     * @return HumeursController
     */
    private function buildHumeurController(): HumeursController
    {
        return new HumeursController($this->buildHumeursService());
    }

    /**
     * @return RegisterController
     */
    private function buildRegisterController(): RegisterController
    {
        return new RegisterController($this->buildRegisterService(), $this->buildAccountsService());
    }

    /**
     * @return StatsController
     */
    private function buildStatsController(): StatsController
    {
        return new StatsController($this->buildStatsService(), $this->buildHumeursService());
    }
}