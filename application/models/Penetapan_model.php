<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penetapan_model extends MY_Model
{
    protected $table = 'tkebijakan';
    protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json() 
    {
        $idTahunPelaksanaan = $this->getIdTahunPelaksanaan();		
        $this->datatables->select("ID,kode,nama,case when dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);    
        $this->datatables->where("idTahunPelaksanaan",$idTahunPelaksanaan);
        $this->datatables->add_column('action', '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>', 'ID');
        return $this->datatables->generate();
    }
}