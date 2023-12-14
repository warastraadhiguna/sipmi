<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pengendalian_model extends MY_Model
{
    protected $table = 'tdetailevaluasi';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select("tdetailevaluasi.ID, tdetailevaluasi.rekomendasiAuditor, tdetailevaluasi.temuanAuditor, tdetailevaluasi.evaluasiDiriAuditor, tkebijakan.nama as namaKebijakan, tkebijakan.kode, case when tdetailevaluasi.dokumen is null or tdetailevaluasi.dokumen='' then 'Kosong' else 'ada' end as dokumen");
        $this->datatables->from("tdetailevaluasi");
        $this->datatables->join("tevaluasi", "tevaluasi.ID=idEvaluasi");
        $this->datatables->join("tkebijakan", "tkebijakan.ID=tdetailevaluasi.idKebijakan");
        $this->datatables->where("tevaluasi.idProdi", $idProdi);
        $this->datatables->where("tevaluasi.idTahunPelaksanaan", $idTahunPelaksanaan);
        $this->datatables->where("tdetailevaluasi.rekomendasiAuditor is not null");
        $this->datatables->add_column('action', '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>', 'ID');

        return $this->datatables->generate();
    }

    public function get_asesmen_kecukupan($idTahunPelaksanaan, $idProdi)
    {
        $sqlQuery = "SELECT * FROM tevaluasi where idTahunPelaksanaan=$idTahunPelaksanaan and idProdi=$idProdi";
        return $this->db->query($sqlQuery)->row();
    }
}