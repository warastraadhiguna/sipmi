<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class History_model extends MY_Model
{
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $tanggalAwal = $this->getReverseDate($this->input->post('tanggalAwal'));
        $tanggalAkhir = $this->getReverseDate($this->input->post('tanggalAkhir'));

        $this->datatables->select("tevent.*, DATE_FORMAT(waktu, '%d-%m-%Y %H:%i')  as waktu_tampil, jenisEvent, nama");
        $this->datatables->from("tevent");
        $this->datatables->join("tuser", "tevent.idUser=tuser.ID");
        $this->datatables->join("tjenisevent", "tjenisevent.ID=tevent.idJenisEvent");
        $this->datatables->where("waktu <='". $tanggalAkhir ." 23:59:59'");
        $this->datatables->where("waktu >='". $tanggalAwal ." 00:00:00'");

        //$this->datatables->where("tevaluasi.idTahunPelaksanaan", $idTahunPelaksanaan);
            
        return $this->datatables->generate();
    }
}