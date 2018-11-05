<?php

namespace Fyre\Engine\Renderer;

use
    Closure,

    Config\Services,
    Fyre\Engine\Renderer\Exceptions\RendererException;

use const
    APP_PATH;

use function
    extract,
    file_exists,
    ob_start,
    ob_get_contents,
    ob_end_clean,

    instance,
    log_message;

class Renderer implements RendererInterface
{
    private $config;
    private $data = [];

    public function __construct(RendererConfig &$config)
    {
        $this->config = &$config;

        Services::logger()->debug('Renderer class loaded');
    }

    public function &getData(): array
    {
        return $this->data;
    }

    public function setData(?array $data): RendererInterface
    {
        if ($data) {
            $this->data = $data + $this->data;
        }

        return $this;
    }

    public function view(string $file): RendererInterface
    {
        Services::Response()->send(
            $this->viewCell($file)
        );

        return $this;
    }

    public function viewCell(string $file): string
    {
        $path = APP_PATH.'View/'.$file.'.php';

        if ( ! file_exists($path)) {
            RendererException::viewNotExists($path);
        }

        return Closure::bind(function($___file, $data) {
            extract($data);

            ob_start();

            include $___file;

            $output = ob_get_contents();

            ob_end_clean();

            return $output;
        }, instance(), null)($path, $this->data);
    }
}
