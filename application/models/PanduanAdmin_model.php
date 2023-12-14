<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PanduanAdmin_model extends MY_Model
{
    protected $table = 'tpanduanpengisian';
    protected $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json($idInfo) {		
        $this->datatables->select("ID,keterangan,case when dokumen='' then 'Kosong' else 'ada' end as dokumen ");
        $this->datatables->from($this->table);     
        $this->datatables->where('idInfo', $idInfo);                  
        $this->datatables->add_column('action', anchor(site_url('PanduanAdmin/update/$1'),'<button type="button" class="btn btn-warning" title="Edit Data"><i class="fa fa-pencil" aria-hidden="true"></i></button>')." ".anchor(site_url('PanduanAdmin/delete/$1'),'<button type="button" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javascript: return confirm(\'Anda yakin menghapus data ini?\')"')." ".'<a data-toggle="modal" href="[link]" onclick="unggahBerkas($1)" class="btn btn-primary" title="Unggah Dokumen"><i class="fa fa-cloud-upload " aria-hidden="true"></i></a>' ." " . '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>'." " . '<button type="button" class="btn btn-danger" onclick="deleteFile($1)" title="Hapus Dokumen"><i class="fa fa-window-close " aria-hidden="true" ></button>', 'ID');
        return $this->datatables->generate();
    }

    function generateData($idTahunPelaksanaan)
    {
        try 
        {
            $this->db->trans_begin();    

            $sqlQuery = "SELECT count(ID) as total from tinfo where idTahunPelaksanaan=$idTahunPelaksanaan";
            $idInfo =  $this->db->query($sqlQuery)->row()->total;   

            if($idInfo == '0')
            {
                $sqlQuery = "INSERT into tinfo(idTahunPelaksanaan) values($idTahunPelaksanaan)";
                $this->db->query($sqlQuery);  
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