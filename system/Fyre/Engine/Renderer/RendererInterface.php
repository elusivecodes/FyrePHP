<?php

namespace Fyre\Engine\Renderer;

interface RendererInterface
{

    public function getData(): array;
    public function setData(array $data): RendererInterface;
    public function view(string $file): RendererInterface;
    public function viewCell(string $file): string;

}
