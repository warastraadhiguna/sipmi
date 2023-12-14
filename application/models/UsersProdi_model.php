<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class UsersProdi_model extends MY_Model
{
    protected $table =  'tuser';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $this->datatables->select("tuser.ID,tuser.username,tuser.nama,tuser.password,tuser.level,tuser.isActive,tuser.id_sessions");
        $this->datatables->from($this->table);
        $this->datatables->where("tuser.level = 'dosen'");
        $this->datatables->where("tuser.idProdi = '".$this->session->userdata['idProdi']."'");
        $this->datatables->add_column('action', anchor(site_url('UsersProdi/update/$1'), '<button type="button" class="btn btn-warning" title="Edit User"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('UsersProdi/delete/$1'), '<button type="button" class="btn btn-danger" title="Hapus User"><i class="fa fa-trash" aria-hidden="true"></i></button>', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"')." ".'<a data-toggle="modal" href="[link]" onclick="ubahPassword($1)" class="btn btn-primary" title="Ubah Password"><i class="fa fa-edit " aria-hidden="true"></i></a>', 'ID');
        return $this->datatables->generate();
    }
}