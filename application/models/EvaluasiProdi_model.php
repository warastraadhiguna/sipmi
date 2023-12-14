<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EvaluasiProdi_model extends MY_Model
{
    protected $table = 'tevaluasi';
    protected $order = 'DESC';
    private $idUser;
    private $idProdi;

    public function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
        $this->idProdi = $this->session->userdata['idProdi'];
    }

    public function json()
    {
        $idProdi = $this->idProdi;
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select(" tdetailevaluasi.ID, tkebijakan.kode,tkebijakan.nama,tdetailevaluasi.nilai,tdetailevaluasi.nilaiAuditor  ");
        $this->datatables->from("tdetailevaluasi");
        $this->datatables->join("tkebijakan", "tdetailevaluasi.idKebijakan=tkebijakan.ID");
        $this->datatables->join("tevaluasi", "tdetailevaluasi.idEvaluasi=tevaluasi.ID");
        $this->datatables->where('tkebijakan.idTahunPelaksanaan', $idTahunPelaksanaan);
        $this->datatables->where('tevaluasi.idProdi', $idProdi);
        return $this->datatables->generate();
    }

    public function GetDataLama($id)
    {
        $idProdi = $this->idProdi;
        try {
            $sqlQuery = "SELECT * FROM tevaluasi where idProdi=$idProdi order by log desc limit 2 ";
            $result = $this->db->query($sqlQuery)->result();

            if (sizeof($result) == 2) {
                return $result[1];
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return  'Data gagal direload, silakan refresh halaman!';
            } else {
                $this->db->trans_commit();
                return null;
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return null;
        }
    }

    public function generateEvaluasi($idTahunPelaksanaan)
    {
        $idUser = $this->idUser;
        $idProdi = $this->idProdi;

        try {
            $this->db->trans_begin();

            $sqlQuery = "SELECT count(ID) as jumlahEvaluasi FROM tevaluasi where idTahunPelaksanaan=$idTahunPelaksanaan and idProdi=$idProdi";
            $jumlahEvaluasi = $this->db->query($sqlQuery)->row()->jumlahEvaluasi;

            if ($jumlahEvaluasi == '0') {
                $sqlQuery = "INSERT into tevaluasi(idTahunPelaksanaan, idProdi, idUser) values($idTahunPelaksanaan,$idProdi ,$idUser)";
                $this->db->query($sqlQuery);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return  'Data gagal direload, silakan refresh halaman!';
            } else {
                $this->db->trans_commit();
                return "";
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return 'Terjadi gangguan data, silakan refresh halaman';
        }
    }

    public function getSingleEvaluasiData($idTahunPelaksanaan)
    {
        $idProdi = $this->idProdi;

        $this->db->select('*');
        $this->db->from('tevaluasi');
        $this->db->where('idTahunPelaksanaan', $idTahunPelaksanaan);
        $this->db->where('idProdi', $idProdi);
        $query = $this->db->get();
        return $query->row();
    }

    /*
public function evaluasi($path, $idEvaluasi, $idTahunPelaksanaan)
    {
        $idProdi = $this->idProdi;
        $error = "";
        try {
            $this->db->trans_begin();

            $arrayProcessedData =  array();
            $sqlQuery = "select ID, excel from tkebijakan where (excel is not null and excel <> '') and idTahunPelaksanaan=" . $idTahunPelaksanaan;
            $dataProses = $this->db->query($sqlQuery)->result();

            foreach ($dataProses as $singleData) {
                array_push($arrayProcessedData, array($singleData->ID,$singleData->excel));
            }

            $resultArray = $this->getDataFromExcelArray($path, $arrayProcessedData);

            $sqlQuery = "delete from tdetailevaluasi where idEvaluasi=$idEvaluasi";
            $this->db->query($sqlQuery);
            $i = 0;
            foreach ($resultArray as $singleArray) {
                $sqlQuery = "insert into tdetailevaluasi(idEvaluasi,idKebijakan,nilai) values ($idEvaluasi,". $singleArray[0] . ", ". $singleArray[1] .")";
                if ($singleArray[1] == '' && $singleArray[1] != '0') {
                    $error =  $error . $arrayProcessedData[$i][1] . ";";
                }
                $i++;

                $this->db->query($sqlQuery);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return 'Data gagal diproses!! Cek pada sheet ' . $error;
            } else {
                $this->db->trans_commit();
                return "";
            }
        } catch (Exception $exception) {
            $this->db->trans_rollback();
            return 'Terjadi gangguan data, silakan refresh halaman';
        }
    }*/
}