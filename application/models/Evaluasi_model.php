<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Evaluasi_model extends MY_Model
{
    protected $table = 'tevaluasi';
    protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json() 
    {		
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select(" tdetailevaluasi.ID, tdetailevaluasi.nilai, tdetailevaluasi.nilaiAuditor ,tkebijakan.nama as namaKebijakan, tkebijakan.kode");
        $this->datatables->from("tdetailevaluasi");    
        $this->datatables->join("tevaluasi", "tevaluasi.ID=idEvaluasi"); 
        $this->datatables->join("tkebijakan", "tkebijakan.ID=tdetailevaluasi.idKebijakan");        
        $this->datatables->where('tevaluasi.idProdi', $idProdi);      
        $this->datatables->where('tevaluasi.idTahunPelaksanaan', $idTahunPelaksanaan);                     
        return $this->datatables->generate();
    }
}