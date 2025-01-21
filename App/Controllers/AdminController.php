<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class AdminController extends Controller {

    var $layout = 'login';
    
    function __construct() {
        if(empty($_SESSION['users'])){
            //header("Location: " . INSTALL_URL . "?controller=Admin&action=login", true, 301);
        }
    }

    public function login() {

    }
}
