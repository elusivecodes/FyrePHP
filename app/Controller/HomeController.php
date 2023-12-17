<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * HomeController
 */
class HomeController extends AppController
{

    /**
     * Render the index view.
     */
    public function index(): void
    {
        $this->set('title', 'FyrePHP V5');
    }

}
