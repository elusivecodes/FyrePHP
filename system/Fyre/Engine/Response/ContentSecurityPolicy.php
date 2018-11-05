<?php

namespace Fyre\Engine\Response;

class ContentSecurityPolicy
{
    protected static $cspRegex = '/^(?:(?:self|unsafe-inline|unsafe-eval|none|strict-dynamic|report-sample)|(?:nonce|sha(?:256|384|512))\-.+)$/';

    private $config;

    public function __construct(ContentSecurityPolicyConfig &$config)
    {
        $this->config = &$config;
    }

    public function addBase(string $source): void
    {
        $this->config->baseUri[] = $source;
    }

    public function addDefault(string $source): void
    {
        $this->config->defaultSrc[] = $source;
    }

    public function addFont(string $source): void
    {
        $this->config->fontSrc[] = $source;
    }

    public function addForm(string $source): void
    {
        $this->config->formAction[] = $source;
    }

    public function addImage(string $source): void
    {
        $this->config->imageSrc[] = $source;
    }

    public function addMedia(string $source): void
    {
        $this->config->mediaSrc[] = $source;
    }

    public function addObject(string $source): void
    {
        $this->config->objectSrc[] = $source;
    }

    public function addScript(string $source): void
    {
        $this->config->scriptSrc[] = $source;
    }

    public function addStyle(string $source): void
    {
        $this->config->styleSrc[] = $source;
    }

    public function blockMixed(bool $block = true): void
    {
        $this->config->blockAllMixed = $block;
    }

    public function buildCSP(): string
    {
        $csp = [];

        if ($this->config->blockAllMixed) {
            $csp[] = 'block-all-mixed-content;';
        }

        if ($this->config->upgradeInsecure) {
            $csp[] = 'upgrade-insecure-requests;';
        }

        if ( ! empty($this->baseUri)) {
            $csp[] = 'base-uri '.implode(' ', $this->parseSrc($this->config->baseUri)).';';
        }

        if ( ! empty($this->defaultSrc)) {
            $csp[] = 'default-src '.implode(' ', $this->parseSrc($this->config->defaultSrc)).';';
        }

        if ( ! empty($this->fontSrc)) {
            $csp[] = 'font-src '.implode(' ', $this->parseSrc($this->config->fontSrc)).';';
        }

        if ( ! empty($this->formAction)) {
            $csp[] = 'form-action '.implode(' ', $this->parseSrc($this->config->formAction)).';';
        }

        if ( ! empty($this->imageSrc)) {
            $csp[] = 'img-src '.implode(' ', $this->parseSrc($this->config->imageSrc)).';';
        }

        if ( ! empty($this->mediaSrc)) {
            $csp[] = 'media-src '.implode(' ', $this->parseSrc($this->config->mediaSrc)).';';
        }

        if ( ! empty($this->objectSrc)) {
            $csp[] = 'object-src '.implode(' ', $this->parseSrc($this->config->objectSrc)).';';
        }

        if ( ! empty($this->scriptSrc)) {
            $csp[] = 'script-src '.implode(' ', $this->parseSrc($this->config->scriptSrc)).';';
        }

        if ( ! empty($this->styleSrc)) {
            $csp[] = 'style-src '.implode(' ', $this->parseSrc($this->config->styleSrc)).';';
        }

        return implode(' ', $csp);
    }

    public function upgradeInsecure(bool $upgrade = true): void
    {
        $this->config->upgradeInsecure = $upgrade;
    }

    private function parseSrc(array $sources): string
    {
        return array_map(
            function($source) {
                return preg_match(static::$cspRegex, $source) ?
                    '\''.$source.'\'' :
                    $source;
            },
            $sources
        );
    }

}
