<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Panduan_model extends MY_Model
{
    protected $table = 'tpanduanpengisian';
    protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json($idTahunPelaksanaan) {		
        $this->datatables->select("tpanduanpengisian.ID,tpanduanpengisian.keterangan,case when tpanduanpengisian.dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from('tpanduanpengisian');   
        $this->datatables->join('tinfo', 'tpanduanpengisian.idInfo = tinfo.ID');
        $this->datatables->where('tinfo.idTahunPelaksanaan', $idTahunPelaksanaan);             
        $this->datatables->add_column('action', '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>', 'ID');
        return $this->datatables->generate();
    }
}