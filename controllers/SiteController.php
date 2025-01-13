<?php

namespace App\controllers;

use App\core\Controller;

class SiteController extends Controller
{
    public function home()
    {
        echo $this->render('home', [
            'name' => 'purrPHp'
        ]);
    }
}