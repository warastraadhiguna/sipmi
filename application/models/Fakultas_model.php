<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fakultas_model extends MY_Model
{   
    protected $table =  'tfakultas';
    protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json() {
        $this->datatables->select("ID,nama, isActive ");
        $this->datatables->from($this->table);  
        $this->datatables->where("ID != 1");          
        $this->datatables->add_column('action', anchor(site_url('Fakultas/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('Fakultas/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'ID');
        return $this->datatables->generate();
    }
}