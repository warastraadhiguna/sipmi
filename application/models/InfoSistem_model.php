<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class InfoSistem_model extends MY_Model
{
    protected $table = 'tinfosistem';
    protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }  
}