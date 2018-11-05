<?php

namespace Fyre\Engine\Router;

use
    Config\Services,
    Fyre\Engine\Router\Exceptions\RouterException;

use const
    APP_PATH;

use function
    array_pop,
    array_shift,
    array_slice,
    class_exists,
    explode,
    file_exists,
    implode,
    is_callable,
    method_exists,
    preg_match,
    preg_replace,
    strtoupper,

    show_404;

class Router implements RouterInterface
{
    private $namespace = 'App\Controller';
    private $defaultRoute;
    private $errorRoute;
    private $routes = [];
    private $groups;

    public function __construct(?string $config)
    {
        if ($config) {
            $path = APP_PATH.'Config/'.$config.'.php';

            if ( ! file_exists($path)) {
                RouterException::configNotExists($path);
            }

            $router = &$this;

            require $path;
        }

        Services::logger()->debug('Router class loaded');
    }

    public function add(string $source, $destination, ?string $type = null): RouterInterface
    {
        if ( ! empty($this->groups)) {
            $source = implode('/', $this->groups).'/'.$source;
        }

        $this->routes[] = [
            'source' => $source,
            'destination' => $destination,
            'type' => $type
        ];

        return $this;
    }

    public function delete(string $source, $destination): RouterInterface
    {
        return $this->add($source, $destination, 'delete');
    }

    public function find(string $uri)
    {
        $destination = '';
        $arguments = [];

        if ( ! $uri) {
            $destination = $this->defaultRoute ?? '';
        } else {
            $requestType = Services::request()->method();

            foreach ($this->routes AS $route) {
                if ($route['type'] &&
                    $route['type'] !== 'redirect' &&
                    strtoupper($route['type']) !== $requestType) {
                    continue;
                }

                $regex = '`^'.$route['source'].'$`';

                if ( ! preg_match($regex, $uri, $match)) {
                    continue;
                }

                if (is_callable($route['destination'])) {
                    return $route['destination'](...array_slice($match, 1));
                }

                $destination = preg_replace($regex, $route['destination'], $uri);

                if ($route['type'] === 'redirect') {
                    Services::response()->redirect($destination);
                }

                break;
            }
        }

        if ( ! $destination) {
            $destination = $this->errorRoute;
        }

        if ( ! $destination) {
            show_404();
        }

        $arguments = explode('/', $destination);

        $destination = array_shift($arguments);

        $destination = explode('::', $destination);

        $className = $this->namespace.'\\'.array_shift($destination);

        if ( ! class_exists($className, true)) {
            RouterException::classNotExists($className);
        }

        $method = array_shift($destination) ?? 'index';

        if ( ! method_exists($className, $method)) {
            RouterException::methodNotExists($className, $method);
        }

        $class = new $className;
        $class->{$method}(...$arguments);

        return true;
    }

    public function get(string $source, $destination): RouterInterface
    {
        return $this->add($source, $destination, 'get');
    }

    public function groupStart(string $source): RouterInterface
    {
        $this->groups[] = $source;

        return $this;
    }

    public function groupEnd(): RouterInterface
    {
        array_pop($this->groups);

        return $this;
    }

    public function post(string $source, $destination): RouterInterface
    {
        return $this->add($source, $destination, 'post');
    }

    public function put(string $source, $destination): RouterInterface
    {
        return $this->add($source, $destination, 'put');
    }

    public function redirect(string $source, string $redirect): RouterInterface
    {
        return $this->add($source, $redirect, 'redirect');
    }

    public function setDefault($destination): RouterInterface
    {
        $this->defaultRoute = $destination;

        return $this;
    }

    public function setError($destination): RouterInterface
    {
        $this->errorRoute = $destination;

        return $this;
    }

    public function setNamespace(string $namespace): RouterInterface
    {
        $this->namespace = $namespace;

        return $this;
    }

}
