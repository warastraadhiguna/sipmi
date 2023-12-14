<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataDokumenLain_model extends MY_Model
{
    protected $table = 'tdetaildokumenlain';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select("tdetaildokumenlain.ID, tdetaildokumenlain.kode,tdetaildokumenlain.nama, case when tdetaildokumenlain.dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);
        $this->datatables->where("tdetaildokumenlain.idProdi", $idProdi);
        $this->datatables->where("tdetaildokumenlain.idTahunPelaksanaan", $idTahunPelaksanaan);
        $this->datatables->add_column('action', '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>', 'ID');
        return $this->datatables->generate();
    }
}