<?php

namespace Fyre\Engine;

use
    Config\Services;

abstract class Controller
{

    public function __construct()
    {
        Services::setSharedInstance($this);

        // $this->benchmark = Services::benchmark();
        // $this->config = Services::config();
        // $this->lang = Services::lang();
        // $this->logger = Services::logger();
        // $this->request = Services::request();
        // $this->response = Services::response();
        // $this->router = Services::router();
        // $this->security = Services::security();
    }

}
