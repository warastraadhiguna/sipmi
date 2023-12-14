<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EvaluasiAuditor_model extends MY_Model
{
    protected $table = 'tevaluasi';
    protected $order = 'DESC';
    private $idUser;

    public function __construct()
    {
        parent::__construct();
        $this->idUser = $this->session->userdata['ID'];
    }

    public function json()
    {
        $idProdi = $this->input->post('idProdi');
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan');

        $this->datatables->select(" tdetailevaluasi.ID, tkebijakan.kode,tkebijakan.nama,tdetailevaluasi.nilai,tdetailevaluasi.nilaiAuditor ");
        $this->datatables->from("tdetailevaluasi");
        $this->datatables->join("tkebijakan", "tdetailevaluasi.idKebijakan=tkebijakan.ID");
        $this->datatables->join("tevaluasi", "tdetailevaluasi.idEvaluasi=tevaluasi.ID");
        $this->datatables->where("tevaluasi.idProdi", $idProdi);
        $this->datatables->where("tevaluasi.idTahunPelaksanaan", $idTahunPelaksanaan);
        $this->datatables->where("tevaluasi.isSubmitted", 1);
        return $this->datatables->generate();
    }

    public function getSingleEvaluasiData($idProdi, $idTahunPelaksanaan)
    {
        $this->db->select('*');
        $this->db->from('tevaluasi');
        $this->db->where('idTahunPelaksanaan', $idTahunPelaksanaan);
        $this->db->where('idProdi', $idProdi);
        $this->db->where("tevaluasi.isSubmitted", 1);
        $query = $this->db->get();
        return $query->row();
    }

    public function evaluasi($path, $idEvaluasi, $idTahunPelaksanaan)
    {
        $idUser = $this->idUser;
        $error = "";
        try {
            $this->db->trans_begin();

            $sqlQuery = "SELECT nilai, peringkat, syarat_perlu_terakreditasi, syarat_perlu_peringkat_unggul, ".
            " syarat_perlu_peringkat_baik_sekali FROM tlaporanasesmenkecukupan where idTahunPelaksanaan=" . $idTahunPelaksanaan ;
            $asesmen = $this->db->query($sqlQuery)->row();

            $arrayProcessedData =  array();
            $sqlQuery = "select ID, excelAuditor, ifNull(excelRekomendasiAuditor,'') as excelRekomendasiAuditor, ".
            "  excelAuditor,excelRekomendasiAuditor,excelTemuanAuditor,excelEvaluasiDiriAuditor,excelIdentifikasiRisikoAuditor ".
            " from tkebijakan where (excelAuditor is not null and excelAuditor <> '') and idTahunPelaksanaan=" . $idTahunPelaksanaan ;
            $dataProses = $this->db->query($sqlQuery)->result();

            foreach ($dataProses as $singleData) {
                array_push(
                    $arrayProcessedData,
                    array(
                        $singleData->ID,
                        $singleData->excelAuditor,
                        $singleData->excelRekomendasiAuditor,
                        $singleData->excelTemuanAuditor,
                        $singleData->excelEvaluasiDiriAuditor,
                        $singleData->excelIdentifikasiRisikoAuditor
                    )
                );
            }

            $resultArray = $this->getDataFromExcelArray($path, $asesmen, $arrayProcessedData);
            $i = 0;

            $sqlQuery = "delete from tdetailevaluasi where idEvaluasi=$idEvaluasi";
            $this->db->query($sqlQuery);

            $index = 0;
            foreach ($resultArray as $singleArray) {
                if ($index == 0) {
                    $sqlQuery = "update tevaluasi set nilai=". $singleArray[0]  .
                    ", peringkat ='". $singleArray[1]  .
                    "', syarat_perlu_terakreditasi ='". $singleArray[2]  .
                    "', syarat_perlu_peringkat_unggul ='". $singleArray[3]  .
                    "',  syarat_perlu_peringkat_baik_sekali='". $singleArray[4]  .
                    "' where ID=" . $idEvaluasi ;
                    $this->db->query($sqlQuery);
                    $index = 1;
                } else {
                    $sqlQuery = "insert into tdetailevaluasi(idEvaluasi,idKebijakan,nilai,nilaiAuditor,rekomendasiAuditor, ".
                    "temuanAuditor,evaluasiDiriAuditor,identifikasiRisikoAuditor) values(".
                    $idEvaluasi .",".$singleArray[0].", 0, ".$singleArray[1].",'".$singleArray[2]."', ".
                    "'".$singleArray[3]."','".$singleArray[4]."','".$singleArray[5]."')";

                    if ($singleArray[1] == '' && $singleArray[1] != '0') {
                        $error =  $error . $arrayProcessedData[$i][1] . ";";
                    }
                    $i++;

                    $this->db->query($sqlQuery);
                }
            }
            
            $sqlQuery = "update tevaluasi set idUserAuditor = ".  $idUser . " where ID=$idEvaluasi";
            $this->db->query($sqlQuery);

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
    }
}