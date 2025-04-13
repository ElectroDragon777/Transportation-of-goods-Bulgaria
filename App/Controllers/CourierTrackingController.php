<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class CourierTrackingController extends Controller
{

    var $layout = 'admin';

    public function index()
    {
        $this->view($this->layout);
    }
}
