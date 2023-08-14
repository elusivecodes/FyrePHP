<?php
declare(strict_types=1);

namespace App\Controller;

use Fyre\Config\Config;
use Fyre\Controller\Controller;
use Fyre\Error\ErrorHandler;

/**
 * ErrorController
 */
class ErrorController extends Controller
{

    /**
     * Render the error view.
     */
    public function index(): void
    {
        $exception = ErrorHandler::getException();    
        $code = $exception->getCode();

        if (Config::get('App.debug')) {
            $title = $exception->getMessage();
            $template = 'Error/debug';
        } else if ($code >= 400 && $code < 500) {
            $title = 'Page Not Found';
            $template = 'Error/404';
        } else {
            $title = 'Something Went Wrong';
            $template = 'Error/production';
        }

        $this->set('title', $title);
        $this->set('exception', $exception);
        $this->setTemplate($template);
        $this->getView()->setLayout(null);
    }

}
