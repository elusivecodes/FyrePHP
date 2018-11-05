<?php

namespace Fyre\Engine\Router;

interface RouterInterface
{

    public function add(string $source, $destination): RouterInterface;
    public function delete(string $source, $destination): RouterInterface;
    public function find(string $uri);
    public function get(string $source, $destination): RouterInterface;
    public function groupStart(string $source): RouterInterface;
    public function groupEnd(): RouterInterface;
    public function post(string $source, $destination): RouterInterface;
    public function put(string $source, $destination): RouterInterface;
    public function redirect(string $source, string $redirect): RouterInterface;
    public function setDefault($destination): RouterInterface;
    public function setError($destination): RouterInterface;
    public function setNamespace(string $namespace): RouterInterface;

}
