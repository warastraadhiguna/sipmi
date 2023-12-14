<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataDokumenLainDosenAdmin_model extends MY_Model
{
    protected $table = 'tdetaildokumenlaindosen';
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
    }

    public function json()
    {
        $idJenisDokumenLain = $this->input->post('idJenisDokumenLain');
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');
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
            anchor(
                site_url('DataDokumenLainDosenAdmin/update/$1'),
                '<button type="button" class="btn btn-warning" title="Edit Data"><i class="fa fa-pencil" aria-hidden="true"></i></button>'
            )." ".
            anchor(
                site_url('DataDokumenLainDosenAdmin/delete/$1'),
                '<button type="button" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                'onclick="javascript: return confirm(\'Anda yakin menghapus data ini?\')"'
            )." ".
            '<a data-toggle="modal" href="[link]" onclick="unggahBerkas($1)" class="btn btn-primary" title="Unggah Dokumen"><i class="fa fa-cloud-upload " aria-hidden="true"></i></a>' ." " .
            '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>'." " .
            '<button type="button" class="btn btn-danger" onclick="deleteFile($1)" title="Hapus Dokumen"><i class="fa fa-window-close " aria-hidden="true" ></button>',
            'ID'
        );

        return $this->datatables->generate();
    }
}