<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Menu_model extends MY_Model
{
    protected $table = 'tmenu';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $this->datatables->select("ID,nama,link,icon,main,level,isActive");
        $this->datatables->from($this->table);
        $this->datatables->add_column('action', anchor(site_url('SuperAdmin/updateMenu/$1'), '<button type="button" class="btn btn-warning" title="Edit User"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('SuperAdmin/deleteMenu/$1'), '<button type="button" class="btn btn-danger" title="Hapus User"><i class="fa fa-trash" aria-hidden="true"></i></button>', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'ID');

        return $this->datatables->generate();
    }
}