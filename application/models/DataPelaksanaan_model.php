<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DataPelaksanaan_model extends MY_Model
{
    protected $table = 'tdetailpelaksanaan';
    protected $order = 'DESC';
    private $idUser,$dataTahunPelaksanaan;

    function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
        $this->dataTahunPelaksanaan = $this->getDefaultTahunPelaksanaan();
    }

    function json() 
    {		
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select("tdetailpelaksanaan.ID,tprodi_unit.nama as namaProdi,tkebijakan.kode,tkebijakan.nama as namaKebijakan,tpelaksanaan.nama as namaPelaksanaan,case when tdetailpelaksanaan.dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);     
        $this->datatables->join ("tpelaksanaan", "tdetailpelaksanaan.idPelaksanaan = tpelaksanaan.ID");
        $this->datatables->join ("tkebijakan", "tpelaksanaan.idKebijakan = tkebijakan.ID");
        $this->datatables->join ("tprodi_unit", "tdetailpelaksanaan.idProdi = tprodi_unit.ID");
        $this->datatables->where('tprodi_unit.ID', $idProdi);      
        $this->datatables->where('idTahunPelaksanaan', $idTahunPelaksanaan);                 
        $this->datatables->add_column('action',  '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>', 'ID');
        return $this->datatables->generate();
    }

    function generateData()
    {
        $idUser = $this->idUser;
        $idTahunPelaksanaan = $this->dataTahunPelaksanaan->ID;

        try 
        {
            $this->db->trans_begin();    


            $sqlQuery = "SELECT ID FROM tpelaksanaan where isDeleted=0";
            $listIdPelaksanaan = $this->db->query($sqlQuery)->result();     

            foreach ($listIdPelaksanaan as $idTemp) 
            {
                $sqlQuery = "SELECT count(ID) as total from tdetailpelaksanaan where idPelaksanaan=$idTemp->ID and idUser=$idUser and idTahunPelaksanaan=$idTahunPelaksanaan";

                $idDetailPelaksanaanTemp =  $this->db->query($sqlQuery)->row()->total;   

                if($idDetailPelaksanaanTemp == '0')
                {
                    $sqlQuery = "INSERT into tdetailpelaksanaan(idPelaksanaan, idUser,idTahunPelaksanaan) values($idTemp->ID,$idUser,$idTahunPelaksanaan)";
                    $this->db->query($sqlQuery);  
                }
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return 'Data gagal direload, silakan refresh halaman!';
            }
            else
            {
                $this->db->trans_commit();
                return "";
            }
        } 
        catch (Exception $exception) 
        {
            $this->db->trans_rollback();
            return 'Terjadi gangguan data, silakan refresh halaman';
        }         
    }
}