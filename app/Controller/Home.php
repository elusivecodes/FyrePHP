<?php
declare(strict_types=1);

namespace App\Controller;

use
    Fyre\Controller\Controller;

/**
 * Home
 */
class Home extends Controller
{

    /**
     * Render the index view.
     */
    public function index()
    {
        $this->render('Home/index');
    }

}
