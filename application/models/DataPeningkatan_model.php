<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DataPeningkatan_model extends MY_Model
{
    protected $table = 'tdetailpeningkatan';
    protected $order = 'DESC';
    private $idUser,$dataTahunPelaksanaan;

    function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
        $this->dataTahunPelaksanaan = $this->getDefaultTahunPelaksanaan();
    }

    function json() 
    {		
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select("tdetailpeningkatan.ID, tdetailpeningkatan.kode,tdetailpeningkatan.nama, case when tdetailpeningkatan.dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);   
        $this->datatables->where("tdetailpeningkatan.idProdi", $idProdi); 
        $this->datatables->where("tdetailpeningkatan.idTahunPelaksanaan", $idTahunPelaksanaan);                  
        $this->datatables->add_column('action',  '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>', 'ID');
        return $this->datatables->generate();
    } 
}