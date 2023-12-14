<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

set_include_path(APPPATH . 'third_party/' . PATH_SEPARATOR . get_include_path());

require_once APPPATH . 'third_party/Google/src/Client.php';


class Google extends Client
{
    public function __construct($params = array())
    {
        parent::__construct();
    }
}