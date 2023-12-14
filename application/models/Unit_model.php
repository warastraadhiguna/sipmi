<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unit_model extends MY_Model
{
    protected $table = 'tprodi_unit';
	protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json() 
    {		
        $this->datatables->select('tprodi_unit.ID,tprodi_unit.nama, isActive');
        $this->datatables->from($this->table);       
		$this->datatables->where('idFakultas', "1");
        $this->datatables->add_column('action', anchor(site_url('Unit/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')." ".anchor(site_url('Unit/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'ID');
        return $this->datatables->generate();
    }
}
