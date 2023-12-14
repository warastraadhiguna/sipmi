<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TahunPelaksanaan_model extends MY_Model
{
    public $table = 'ttahunpelaksanaan';
    public $id = 'ID';
    public $order = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function json()
    {
        $this->datatables->select("ttahunpelaksanaan.ID, tahun, keterangan, case when isDefault=1 then 'Default' else '-' end as isDefault, isActive");
        $this->datatables->from($this->table);
        $this->datatables->add_column('action', anchor(site_url('TahunPelaksanaan/delete/$1'), '<button type="button" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash" aria-hidden="true"  ></i></button>', 'onclick="javascript: return confirm(\'Anda yakin menghapus data ini?\')"')." ".anchor(site_url('TahunPelaksanaan/setDefault/$1'), '<button type="button" class="btn btn-primary" title="Set Default Pelaksanaan"><i class="fa fa-edit" aria-hidden="true"  ></i></button>', 'onclick="javascript: return confirm(\'Anda yakin menjadikan tahun ini sebagai default?\')"')." ".anchor(site_url('TahunPelaksanaan/setStatus/$1'), '<button type="button" class="btn btn-warning" title="Set Status"><i class="fa fa-pencil-square" aria-hidden="true"  ></i></button>', 'onclick="javascript: return confirm(\'Anda yakin menonaktifkan tahun ini?\')"')." ".anchor(site_url('TahunPelaksanaan/copyData/$1'), '<button type="button" class="btn btn-info" title="Copy Data"><i class="fa fa-clone" aria-hidden="true"  ></i></button>', 'onclick="javascript: return confirm(\'Anda yakin mengopy data pada tahun default ke tahun ini?\')"'), 'ID');
        return $this->datatables->generate();
    }

    public function copyData($id)
    {
        try {
            $this->db->trans_begin();

            $sqlQuery = "select ID from ttahunpelaksanaan where isDefault=1";
            $idDefault = $this->db->query($sqlQuery)->row()->ID;
            
            $sqlQuery = "delete from tinfo where idTahunPelaksanaan=$id";
            $this->db->query($sqlQuery);

            $sqlQuery = "delete from tkebijakan where idTahunPelaksanaan=$id";
            $this->db->query($sqlQuery);

            $sqlQuery = "delete from tdetaildokumenlain where idTahunPelaksanaan=$id";
            $this->db->query($sqlQuery);

            $sqlQuery = "delete from tdetaildokumenlaindosen where idTahunPelaksanaan=$id";
            $this->db->query($sqlQuery);

            $sqlQuery = "select ID,panduanPengisian,dokumenEvaluasi,dokumenEvaluasiS2,dokumenEvaluasiS3,dokumenEvaluasiD3,dokumenEvaluasiD4 from tinfo where idTahunPelaksanaan=$idDefault";
            $dataInfoDefault = $this->db->query($sqlQuery)->row();

            if ($dataInfoDefault) {
                $sqlQuery = "insert into tinfo(panduanPengisian,dokumenEvaluasi,dokumenEvaluasiS2,dokumenEvaluasiS3,dokumenEvaluasiD3,dokumenEvaluasiD4, idTahunPelaksanaan) 
                select panduanPengisian,dokumenEvaluasi,dokumenEvaluasiS2,dokumenEvaluasiS3,dokumenEvaluasiD3,dokumenEvaluasiD4,'".$id."' from tinfo where idTahunPelaksanaan=$idDefault";
                $this->db->query($sqlQuery);
                $idInfoBaru = $this->db->insert_id();
                
                $sqlQuery = "insert into tpanduanpengisian(idInfo,keterangan,dokumen) SELECT '".$idInfoBaru."',keterangan, dokumen FROM tpanduanpengisian where idInfo=" . $dataInfoDefault->ID;

                $this->db->query($sqlQuery);
            }

            $sqlQuery = "SELECT ID,kode,nama,dokumen,excel,excelAuditor,excelRekomendasiAuditor, excelEvaluasiDiriAuditor, excelTemuanAuditor, excelIdentifikasiRisikoAuditor FROM tkebijakan  where idTahunPelaksanaan=$idDefault";
            $dataKebijakan = $this->db->query($sqlQuery)->result();

            foreach ($dataKebijakan as $row) {
                $sqlQuery = "insert into tkebijakan(kode,nama,dokumen,excel,excelAuditor,excelRekomendasiAuditor, excelEvaluasiDiriAuditor, excelTemuanAuditor, excelIdentifikasiRisikoAuditor,idTahunPelaksanaan) values ('".$row->kode."','".$row->nama."','".$row->dokumen."','".$row->excel."','".$row->excelAuditor."','".$row->excelRekomendasiAuditor."','".$row->excelEvaluasiDiriAuditor."','".$row->excelTemuanAuditor."','".$row->excelIdentifikasiRisikoAuditor."',".$id.")";
                $this->db->query($sqlQuery);
                $idKebijakanBaru = $this->db->insert_id();

                $sqlQuery = "SELECT ID, nama FROM tpelaksanaan where idKebijakan=" . $row->ID;
                $dataPelaksanaan = $this->db->query($sqlQuery)->result();
                
                foreach ($dataPelaksanaan as $row2) {
                    $sqlQuery = "insert into tpelaksanaan(nama,idKebijakan) values ('".$row2->nama."',".$idKebijakanBaru.")";
                    $this->db->query($sqlQuery);
                    $idPelaksanaanBaru = $this->db->insert_id();

                    $sqlQuery = "INSERT into tdetailpelaksanaan(idPelaksanaan, idUser, idProdi, dokumen) SELECT '". $idPelaksanaanBaru ."', idUser, idProdi, dokumen FROM tdetailpelaksanaan where idPelaksanaan=" . $row2->ID;

                    $this->db->query($sqlQuery);
                }
            }
            
            $sqlQuery = "INSERT into tdetaildokumenlain(kode, nama, idUser, idProdi,idTahunPelaksanaan,dokumen) SELECT kode, nama, idUser, idProdi,'". $id ."',dokumen FROM tdetaildokumenlain where idTahunPelaksanaan=" . $idDefault;
            $this->db->query($sqlQuery);

            $sqlQuery = "INSERT into tdetaildokumenlaindosen(idUser,idUserDosen,idJenisDokumenLain,idTahunPelaksanaan,kode,nama,dokumen ) SELECT idUser,idUserDosen,idJenisDokumenLain,'". $id ."',kode,nama,dokumen  FROM tdetaildokumenlaindosen where idTahunPelaksanaan=" . $idDefault;
            $this->db->query($sqlQuery);
            
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return $sqlQuery ;
            } else {
                $this->db->trans_commit();
                return "";
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return 'Terjadi gangguan data.';
        }
    }

    public function setDefault($id)
    {
        try {
            $this->db->trans_begin();

            $sqlQuery = "update ttahunpelaksanaan set isDefault=0";
            $this->db->query($sqlQuery);

            $sqlQuery = "update ttahunpelaksanaan set isDefault=1 where ID=$id";
            $this->db->query($sqlQuery);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return 'Data gagal diubah.';
            } else {
                $this->db->trans_commit();
                return "";
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return 'Terjadi gangguan data.';
        }
    }

    public function setStatus($id)
    {
        try {
            $this->db->trans_begin();

            $sqlQuery = "update ttahunpelaksanaan set isActive = case when isActive='Aktif' then 'Tidak Aktif' else 'Aktif' end where ID=$id";
            $this->db->query($sqlQuery);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return 'Data gagal diubah.';
            } else {
                $this->db->trans_commit();
                return "";
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return 'Terjadi gangguan data.';
        }
    }
}