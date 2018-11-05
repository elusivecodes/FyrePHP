<?php

namespace Config\Engine;

use
    Fyre\Engine\Response\ContentSecurityPolicyConfig;

class ContentSecurityPolicy extends ContentSecurityPolicyConfig
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
