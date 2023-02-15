<?php
namespace controllers;

use yasmf\View;

class HomeController {

    public function __construct()
    {
        session_start();
    }

    public function index() {
        $view = new View("CheckYourMood/codeCYM/views/index");
        return $view;
    }

}