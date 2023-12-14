<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataDokumenLainDosenProdi_model extends MY_Model
{
    protected $table = 'tdetaildokumenlaindosen';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $idJenisDokumenLain = $this->input->post('idJenisDokumenLain');
        $idProdi = $this->session->userdata['idProdi'];
        $idTahunPelaksanaan = $this->getDefaultTahunPelaksanaan()->ID;
        $idDosen = $this->input->post('idDosen');
        
        $this->datatables->select("tdetaildokumenlaindosen.ID, tdetaildokumenlaindosen.kode,tdetaildokumenlaindosen.nama, case when tdetaildokumenlaindosen.dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);
        $this->datatables->join("tuser", "tdetaildokumenlaindosen.idUserDosen = tuser.ID");
        $this->datatables->where("tdetaildokumenlaindosen.idTahunPelaksanaan", $idTahunPelaksanaan);
        $this->datatables->where("tdetaildokumenlaindosen.idJenisDokumenLain", $idJenisDokumenLain);
        $this->datatables->where("tdetaildokumenlaindosen.idUserDosen", $idDosen);
        $this->datatables->where("tuser.idProdi", $idProdi);
        
        $this->datatables->add_column(
            'action',
            '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>',
            'ID'
        );

        return $this->datatables->generate();
    }
}