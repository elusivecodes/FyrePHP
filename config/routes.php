<?php
declare(strict_types=1);

$router->get('/', fn(): string => view('welcome', [
    'title' => 'FyrePHP V9',
]));
