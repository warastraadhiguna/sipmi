<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EvaluasiAdmin extends AdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('EvaluasiAdmin_model');
        $this->load->model('Prodi_model');
        
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper('download');

        $this->subDirectory = $this->EvaluasiAdmin_model->getDefaultTahunPelaksanaan()->tahun . '/evaluasi/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $idTahunPelaksanaan = $this->EvaluasiAdmin_model->getIdTahunPelaksanaan();
        $idFakultas = $this->EvaluasiAdmin_model->getRealIdFakultas();
        $idProdi = $this->EvaluasiAdmin_model->getRealIdProdi();

        $isSubmitted = 0;
        if ($idProdi && $idTahunPelaksanaan) {
            $dataEvaluasi = $this->EvaluasiAdmin_model->get_where_table("idProdi=$idProdi and idTahunPelaksanaan=$idTahunPelaksanaan", "tevaluasi");

            if (sizeof($dataEvaluasi) > 0) {
                $isSubmitted = $dataEvaluasi->isSubmitted;
            }
        }
        
        $row = $this->EvaluasiAdmin_model->get_system_info($idTahunPelaksanaan);
        $dataTambahan = array(
            'idFakultas'       => $idFakultas,
            'idProdi'       => $idProdi,
            'idTahunPelaksanaan' => $idTahunPelaksanaan,
            'isSubmitted' => $isSubmitted,
            'fileEvaluasi' =>  $row->dokumenEvaluasi,
            'fileEvaluasiS2' =>  $row->dokumenEvaluasiS2,
            'fileEvaluasiS3' =>  $row->dokumenEvaluasiS3,
            'fileEvaluasiD3' =>  $row->dokumenEvaluasiD3,
            'fileEvaluasiD4' =>  $row->dokumenEvaluasiD4,
        );

        $this->load->view('header_list', $data);
        $this->load->view('EvaluasiAdmin/evaluasiadmin_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->EvaluasiAdmin_model->json();
    }

    public function hapusSubmit($ID)
    {
        $idSplit = explode("_", $ID);
        $idFakultas = $idSplit[0];
        $idProdi =  $idSplit[1];
        $idTahunPelaksanaan = $idSplit[2];

        $data = array(
            'isSubmitted' => 0,
        );

        $row = $this->Prodi_model->get_by_id($idProdi);

        
        if ($this->EvaluasiAdmin_model->update_where_table("tevaluasi", "idProdi=$idProdi and idTahunPelaksanaan=$idTahunPelaksanaan", $data)) {
            flashMessage('success', 'Data sudah diubah!!');
            $this->EvaluasiAdmin_model->addEvent(30, $this->idUser, $this->serialize_data($row));

            redirect(site_url('EvaluasiAdmin'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        } else {
            flashMessage('error', 'Data gagal diubah!!');
            redirect(site_url('EvaluasiAdmin'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        }
    }

    public function ConvertExcel($ID)
    {
        $idSplit = explode("_", $ID);
        $idFakultas = $idSplit[0];
        $idProdi =  $idSplit[1];
        $idTahunPelaksanaan = $idSplit[2];

        $table_columns = $this->EvaluasiAdmin_model->getDataEvaluasi($idProdi, $idTahunPelaksanaan);
        if (sizeof($table_columns) == 0) {
            flashMessage('error', 'Data tidak ada!!');
            redirect(site_url('EvaluasiAdmin'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        }

        require(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php');
        require(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

        $object = new PHPExcel();
        $object->getProperties()->setCreator('LPM')
                ->setLastModifiedBy('LPM')
                ->setTitle("Data Evaluasi")
                ->setSubject("Prodi")
                ->setDescription("Laporan Semua Data Evaluasi by Prodi")
                ->setKeywords("Data Evaluasi");

        $object->setActiveSheetIndex(0);

        $object->getActiveSheet()->setCellValue("A1", "NO");
        $object->getActiveSheet()->setCellValue("B1", "Kode Standar");
        $object->getActiveSheet()->setCellValue("C1", "Nama Standar");
        $object->getActiveSheet()->setCellValue("D1", "Evaluasi Diri");
        $object->getActiveSheet()->setCellValue("E1", "Identifikasi Risiko");
        $object->getActiveSheet()->setCellValue("F1", "Nilai Auditor");
        $object->getActiveSheet()->setCellValue("G1", "Temuan Auditor");
        $object->getActiveSheet()->setCellValue("H1", "Rekomendasi Auditor");
        $baris = 2;
        $no = 1;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValue("A" . $baris, $no++);
            $object->getActiveSheet()->setCellValue("B" . $baris, $field->kode);
            $object->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);

            $object->getActiveSheet()->setCellValue("C" . $baris, $field->namaKebijakan);
            $object->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);

            $object->getActiveSheet()->setCellValue("D" . $baris, $field->evaluasiDiriAuditor);
            $object->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);

            $object->getActiveSheet()->setCellValue("E" . $baris, $field->identifikasiRisikoAuditor);
            $object->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);

            $object->getActiveSheet()->setCellValue("F" . $baris, $field->nilaiAuditor);
            $object->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);

            $object->getActiveSheet()->setCellValue("G" . $baris, $field->temuanAuditor);
            $object->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);

            $object->getActiveSheet()->setCellValue("H" . $baris, $field->rekomendasiAuditor);
            $object->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);

            $baris++;
        }


        $namaProdi = $this->EvaluasiAdmin_model->get_by_id_table($idProdi, "tprodi_unit")->nama;
        $tahunPelaksanaan = $this->EvaluasiAdmin_model->get_by_id_table($idTahunPelaksanaan, "ttahunpelaksanaan")->tahun;
        $filename = "Data Evaluasi $namaProdi $tahunPelaksanaan";
        $object->getActiveSheet()->setTitle("Evaluasi " . $tahunPelaksanaan);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        //ob_end_clean();
        $object_writer->save('php://output');

        exit;
    }

    public function DeleteEvaluasi($ID)
    {
        $idSplit = explode("_", $ID);
        $idFakultas = $idSplit[0];
        $idProdi =  $idSplit[1];
        $idTahunPelaksanaan = $idSplit[2];

        $row = $this->EvaluasiAdmin_model->get_single_evaluasi($idProdi, $idTahunPelaksanaan);

        if ($this->EvaluasiAdmin_model->delete($row->ID)) {
            $this->EvaluasiAdmin_model->addEvent(31, $this->idUser, $this->serialize_data($row));

            flashMessage('success', 'Data berhasil dihapus!!');
        } else {
            flashMessage('error', 'Data gagal dihapus!!');
        }
        redirect(site_url('EvaluasiAdmin'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
    }

    public function upload()
    {
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaanMaster', true);
        $idFakultas = $this->input->post('idFakultasMaster', true);
        $idProdi = $this->input->post('idProdiMaster', true);
        $jenisMaster= $this->input->post('jenisMaster', true);
        
        $rowInfo = $this->EvaluasiAdmin_model->get_by_id_table_detail($idTahunPelaksanaan, "idTahunPelaksanaan", "tinfo");
        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $this->subDirectory = $this->EvaluasiAdmin_model->get_by_id_table($idTahunPelaksanaan, "ttahunpelaksanaan")->tahun . '/evaluasi/';

        $fileName =  'EvaluasiMaster' . $jenisMaster;
        $longFileName = $this->subDirectory. $fileName . '.' .  $ext;
        $longFilePath = $this->uploadingDirectory . $longFileName;
        $longFilePathWithoutFile = $this->uploadingDirectory . $this->subDirectory;

        if (file_exists($longFilePath)) {
            unlink($longFilePath);
        }

        $config['upload_path'] = $longFilePathWithoutFile ;
        $config['allowed_types'] = 'xlsx|xls';
        $config['file_name']     =   $fileName ;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('dokumen')) {
            flashMessage('error', 'Dokumen tidak dapat disimpan!');
            redirect(site_url('EvaluasiAdmin'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        } else {
            $jenisMaster  = $jenisMaster == "S1" ?  "" : $jenisMaster;
            $data =array(
                        'dokumenEvaluasi' . $jenisMaster => $longFileName,
                    );

            if ($this->EvaluasiAdmin_model->update_by_table("tinfo", $rowInfo->ID, $data)) {
                $this->EvaluasiAdmin_model->addEvent(32, $this->idUser, $this->serialize_data($data));

                flashMessage('success', 'Dokumen disimpan.');
            } else {
                flashMessage('error', 'Dokumen gagal disimpan!!');
            }

            redirect(site_url('EvaluasiAdmin'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        }
    }

    public function downloadEmptyFile($idTahunPelaksanaan, $jenisMaster)
    {
        $row = $this->EvaluasiAdmin_model->get_system_info($idTahunPelaksanaan);
        $jenisMaster = str_replace(".html", "", $jenisMaster);
        $dokumen = $jenisMaster == "S1" ? $row->dokumenEvaluasi
        : ($jenisMaster == "S2"? $row->dokumenEvaluasiS2 :
        (
            $jenisMaster == "S3"? $row->dokumenEvaluasiS3 :
            (
                $jenisMaster == "D3"?  $row->dokumenEvaluasiD3 :
                $row->dokumenEvaluasiD4
            )
        ));

        if ($dokumen) {
            $path = base_url() . $this->uploadingDirectory . $dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen($idTahunPelaksanaan, $jenisMaster)
    {
        $row = $this->EvaluasiAdmin_model->get_system_info($idTahunPelaksanaan);
        $jenisMaster = str_replace(".html", "", $jenisMaster);
        $namaDokumen = $jenisMaster == "S1" ? $row->dokumenEvaluasi
        : ($jenisMaster == "S2"? $row->dokumenEvaluasiS2 :
        (
            $jenisMaster == "S3"? $row->dokumenEvaluasiS3 :
            (
                $jenisMaster == "D3"?  $row->dokumenEvaluasiD3 :
                $row->dokumenEvaluasiD4
            )
        ));

        $data = array(
            'dokumenEvaluasi' . ($jenisMaster == "S1" ?  "" : $jenisMaster) => '',
        );

        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->EvaluasiAdmin_model->update_by_table("tinfo", $row->ID, $data)) {
                $namaPathSeharusnya = $this->EvaluasiAdmin_model->get_by_id_table($idTahunPelaksanaan, "ttahunpelaksanaan")->tahun. '/evaluasi/';
    
                if (strpos($namaDokumen, $namaPathSeharusnya) !== false) {
                    $longFilePath = $this->uploadingDirectory . $namaDokumen;

                    if (file_exists($longFilePath)) {
                        unlink($longFilePath);
                    }
                }

                $this->EvaluasiAdmin_model->addEvent(33, $this->idUser, 'dokumenEvaluasi' . $jenisMaster);

                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }
}