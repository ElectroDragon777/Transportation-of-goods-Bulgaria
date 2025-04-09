<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class HomeController extends Controller
{

    var $layout = 'admin';

    public function index()
    {
        $this->view($this->layout);
    }

    // For Admin and Couriers - show users with active orders (active is not here yet).

    // For Admin/Root Contact:

}
