<?php
declare(strict_types=1);

use
    Fyre\Router\Router;

Router::setDefaultNamespace('App\Controller');

Router::setDefaultRoute('Home');
Router::setErrorRoute('Error');
