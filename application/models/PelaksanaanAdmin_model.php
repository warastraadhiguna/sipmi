<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PelaksanaanAdmin_model extends MY_Model
{
    public $table = 'tpelaksanaan';
    public $id = 'ID';
    public $order = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json($idTahunPelaksanaan)
    {
        $this->datatables->select("tpelaksanaan.ID, idKebijakan,tkebijakan.nama as namaStandar, kode, tpelaksanaan.nama ");
        $this->datatables->from($this->table);
        $this->datatables->join('tkebijakan', 'tpelaksanaan.idKebijakan = tkebijakan.ID');
        $this->datatables->where('tkebijakan.idTahunPelaksanaan', $idTahunPelaksanaan);
        $this->datatables->add_column('action', anchor(site_url('PelaksanaanAdmin/update/$1'), '<button type="button" class="btn btn-warning" title="Edit Data"><i class="fa fa-pencil" aria-hidden="true"></i></button>')." ".anchor(site_url('PelaksanaanAdmin/delete/$1'), '<button type="button" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash" aria-hidden="true"  ></i></button>', 'onclick="javascript: return confirm(\'Anda yakin menghapus data ini?\')"'), 'ID');
        return $this->datatables->generate();
    }
}