<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends MY_Model
{
    protected $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $idTahunPelaksanaan = $this->getDefaultTahunPelaksanaan()->ID;

        $this->datatables->select("'' as ID, tfakultas.nama as namaFakultas,tprodi_unit.nama as namaProdi, FORMAT(nilai,2,'de_DE') as rerata, ifnull(jumlahDosen, 0) as jumlahDosen, ifnull(jumlahDokumen, 0) as jumlahDokumen ");
        $this->datatables->from("tevaluasi");
        $this->datatables->join("tprodi_unit", "tprodi_unit.ID=tevaluasi.idProdi");
        $this->datatables->join("(select count(tuser.ID) as jumlahDosen, tuser.idProdi from tuser  where level='dosen' and isActive=1 group by tuser.idProdi ) as rangkumanProdi", "rangkumanProdi.idProdi=tprodi_unit.ID", "left");
        $this->datatables->join("(SELECT count(idUserDosen) as jumlahDokumen, idProdi FROM tdetaildokumenlaindosen a inner join tuser b on a.idUserDosen=b.ID where dokumen is not null and dokumen <> '' group by idProdi) as rangkumanDokumen", "rangkumanDokumen.idProdi=tprodi_unit.ID", "left");
        $this->datatables->join("tfakultas", "tfakultas.ID=tprodi_unit.idFakultas");
        $this->datatables->where("tevaluasi.idTahunPelaksanaan", $idTahunPelaksanaan);
        
        $this->datatables->group_by("tprodi_unit.nama,tfakultas.nama");
                    
        return $this->datatables->generate();
    }
}