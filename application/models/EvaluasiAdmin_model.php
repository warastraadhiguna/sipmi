<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EvaluasiAdmin_model extends MY_Model
{
    protected $table = 'tEvaluasi';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
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

    public function get_single_evaluasi($idProdi, $idTahunPelaksanaan)
    {
        $this->db->select(" tevaluasi.*");
        $this->db->from("tevaluasi");
        $this->db->where('tevaluasi.idProdi', $idProdi);
        $this->db->where('tevaluasi.idTahunPelaksanaan', $idTahunPelaksanaan);
        return $this->db->get()->row();
    }

    public function getDataEvaluasi($idProdi, $idTahunPelaksanaan)
    {
        $this->db->select(" tdetailevaluasi.*, tkebijakan.nama as namaKebijakan, tkebijakan.kode as kode");
        $this->db->from("tdetailevaluasi");
        $this->db->join("tevaluasi", "tevaluasi.ID=idEvaluasi");
        $this->db->join("tkebijakan", "tkebijakan.ID=tdetailevaluasi.idKebijakan");
        $this->db->where('tevaluasi.idProdi', $idProdi);
        $this->db->where('tevaluasi.idTahunPelaksanaan', $idTahunPelaksanaan);
        return $this->db->get()->result();
    }
}