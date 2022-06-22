<?php
declare(strict_types=1);

namespace App\Controller;

use
    Fyre\Controller\Controller,
    Fyre\Error\ErrorHandler;

/**
 * Error
 */
class Error extends Controller
{

    /**
     * Render the error view.
     */
    public function index()
    {
        $exception = ErrorHandler::getException();

        $this->set('title', $exception->getMessage());
        $this->set('exception', $exception);

        $this->render('Error/default');
    }

}
