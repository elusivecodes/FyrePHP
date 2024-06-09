<?php
declare(strict_types=1);

use Fyre\Router\Router;

Router::get('/', fn(): string => view('welcome', [
    'title' => 'FyrePHP V6'
]));
