<?php

namespace Fyre\Engine\Response;

use
    Fyre\Config\BaseConfig;

class ContentSecurityPolicyConfig extends BaseConfig
{
    public $blockAllMixed = false;
    public $upgradeInsecure = false;

    public $baseUri = [];
    public $defaultSrc = [];
    public $fontSrc = [];
    public $formAction = [];
    public $imageSrc = [];
    public $mediaSrc = [];
    public $objectSrc = [];
    public $scriptSrc = [];
    public $styleSrc = [];
}
