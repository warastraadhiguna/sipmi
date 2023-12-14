<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inputpelaksanaan_model extends MY_Model
{
    protected $table = 'tdetailpelaksanaan';
    protected $order = 'DESC';
    private $idUser,$idProdi;

    function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
        $this->idProdi = $this->session->userdata['idProdi'];
    }

    function json($idTahunPelaksanaan) 
    {		
        $idProdi = $this->idProdi;
        $this->datatables->select("tdetailpelaksanaan.ID, tkebijakan.kode, tkebijakan.nama as namaKebijakan, tpelaksanaan.nama as namaPelaksanaan,case when tdetailpelaksanaan.dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);        
        $this->datatables->join('tpelaksanaan', 'tpelaksanaan.ID = tdetailpelaksanaan.idPelaksanaan');
        $this->datatables->join('tkebijakan', 'tpelaksanaan.idKebijakan = tkebijakan.ID');     
        $this->datatables->where('idTahunPelaksanaan', $idTahunPelaksanaan);            
        $this->datatables->where('idProdi', $idProdi);         
        $this->datatables->add_column('action', '<a data-toggle="modal" href="[link]" onclick="unggahBerkas($1)" class="btn btn-primary" title="Unggah Dokumen"><i class="fa fa-cloud-upload " aria-hidden="true"></i></a>' ." " . '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>'." " . '<button type="button" class="btn btn-danger" onclick="deleteFile($1)" title="Hapus Dokumen"><i class="fa fa-window-close " aria-hidden="true" ></button>', 'ID');
        return $this->datatables->generate();
    }

    // function tambahdokumen($id)
    // {
    //     $idUser = $this->idUser;
    //     $idTahunPelaksanaan = $this->dataTahunPelaksanaan->ID;

    //     try 
    //     {
    //         $this->db->trans_begin();    

    //         $sqlQuery = "INSERT into tdetailpelaksanaan(idPelaksanaan, idUser,idTahunPelaksanaan) (SELECT idPelaksanaan, idUser,idTahunPelaksanaan FROM tdetailpelaksanaan where ID=$id)";
    //         $this->db->query($sqlQuery);  

    //         if ($this->db->trans_status() === FALSE)
    //         {
    //             $this->db->trans_rollback();
    //             return 'Data gagal direload, silakan refresh halaman!';
    //         }
    //         else
    //         {
    //             $this->db->trans_commit();
    //             return "";
    //         }
    //     } 
    //     catch (Exception $exception) 
    //     {
    //         $this->db->trans_rollback();
    //         return 'Terjadi gangguan data, silakan refresh halaman';
    //     }         
    // }

    function generateData($idTahunPelaksanaan)
    {
        $idUser = $this->idUser;
        $idProdi =  $this->idProdi;

        try 
        {
            $this->db->trans_begin();              

            $sqlQuery = "SELECT tpelaksanaan.ID FROM tpelaksanaan inner join tkebijakan on tpelaksanaan.idKebijakan=tkebijakan.ID and tkebijakan.idTahunPelaksanaan=$idTahunPelaksanaan";
            $listIdPelaksanaan = $this->db->query($sqlQuery)->result();     

            foreach ($listIdPelaksanaan as $idTemp) 
            {
                $sqlQuery = "SELECT count(ID) as total from tdetailpelaksanaan where idPelaksanaan=$idTemp->ID and idUser=$idUser and idProdi=$idProdi";
                $idDetailPelaksanaanTemp =  $this->db->query($sqlQuery)->row()->total;   

                if($idDetailPelaksanaanTemp == '0')
                {
                    $sqlQuery = "INSERT into tdetailpelaksanaan(idPelaksanaan, idUser,idProdi) values($idTemp->ID,$idUser,$idProdi)";
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