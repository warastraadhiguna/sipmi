<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PenetapanAdmin_model extends MY_Model
{
    protected $table = 'tkebijakan';
    protected $order = 'DESC';
    private $idUser;
    
    public function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
    }
    
    public function json($idTahunPelaksanaan)
    {
        $this->datatables->select("ID,kode,nama,case when dokumen='' then 'Kosong' else 'ada' end as dokumen,excel, excelAuditor,excelRekomendasiAuditor,excelEvaluasiDiriAuditor, excelTemuanAuditor,excelIdentifikasiRisikoAuditor");
        $this->datatables->from($this->table);
        $this->datatables->where('idTahunPelaksanaan', $idTahunPelaksanaan);
        $this->datatables->add_column('action', anchor(site_url('PenetapanAdmin/update/$1'), '<button type="button" class="btn btn-warning" title="Edit Data"><i class="fa fa-pencil" aria-hidden="true"></i></button>')." ".anchor(site_url('PenetapanAdmin/delete/$1'), '<button type="button" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash" aria-hidden="true"></i></button>', 'onclick="javascript: return confirm(\'Anda yakin menghapus data ini?\')"')." ".'<a data-toggle="modal" href="[link]" onclick="unggahBerkas($1)" class="btn btn-primary" title="Unggah Dokumen"><i class="fa fa-cloud-upload " aria-hidden="true"></i></a>' ." " . '<button type="button" class="btn btn-link" onclick="openDokumen($1)"><i class="fa fa-eye " aria-hidden="true"  title="Lihat Dokumen"></i></button>'." " . '<button type="button" class="btn btn-danger" onclick="deleteFile($1)" title="Hapus Dokumen"><i class="fa fa-window-close " aria-hidden="true" ></button>', 'ID');
        return $this->datatables->generate();
    }

    public function get_laporan_asesmen_kecukupan($idTahunPelaksanaan)
    {
        $idUser = $this->idUser;

        try {
            $this->db->trans_begin();

            $sqlQuery = "SELECT count(ID) as jumlah FROM tlaporanasesmenkecukupan where idTahunPelaksanaan=$idTahunPelaksanaan";
            $jumlah = $this->db->query($sqlQuery)->row()->jumlah;

            if ($jumlah == '0') {
                $sqlQuery = "INSERT into tlaporanasesmenkecukupan(idTahunPelaksanaan, idUser, nilai, peringkat, syarat_perlu_terakreditasi, syarat_perlu_peringkat_unggul, syarat_perlu_peringkat_baik_sekali) ".
                " values($idTahunPelaksanaan, $idUser, '', '', '', '', '')";
                $this->db->query($sqlQuery);
            }

            $sqlQuery = "SELECT * FROM tlaporanasesmenkecukupan where idTahunPelaksanaan=$idTahunPelaksanaan";
            $result = $this->db->query($sqlQuery)->row();

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return null;
            } else {
                $this->db->trans_commit();
                return $result;
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return null;
        }
    }

    public function update_asesmen($id, $data)
    {
        $this->db->where('ID', $id);
        return $this->db->update("tlaporanasesmenkecukupan", $data);
    }

    public function total_rows($q = null)
    {
        $this->db->like('ID', $q);
        $this->db->where('isDeleted', 0);
        $this->db->or_like('kode', $q);
        $this->db->or_like('nama', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_limit_data($limit, $start = 0, $q = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('isDeleted', 0);
        $this->db->order_by('kode', $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }
}