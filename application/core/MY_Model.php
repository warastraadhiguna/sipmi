<?php
class MY_Model extends CI_Model
{
    protected $table = '';
    protected $order = '';

    public function __construct()
    {
        parent::__construct();

        // Set nama tabel secara otomatis jika tidak dideklarasikan
        // variabel $table di child class.
        if (!$this->table) {
            $this->table = strtolower(str_replace(
                'Model',
                '',
                get_class($this)
            ));
        }
    }

    //path , sheet#x#y

    public function getDataFromExcelArray($path, $excelAsesmen, $arrayCell)
    {
        require(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php');
    
        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load($path);
        $resultArray = array();

        $nilai = 0;
        if ($excelAsesmen->nilai) {
            $detailCells = explode("-", $excelAsesmen->nilai);
            $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

            $nilai = $sheet->getCellByColumnAndRow(
                $this->getRowNumberFromAlpabeths($detailCells[1]),
                $detailCells[2]
            )->getCalculatedValue();
        }

        $peringkat = "";
        if ($excelAsesmen->peringkat) {
            $detailCells = explode("-", $excelAsesmen->peringkat);
            $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

            $peringkat = $sheet->getCellByColumnAndRow(
                $this->getRowNumberFromAlpabeths($detailCells[1]),
                $detailCells[2]
            )->getCalculatedValue();
        }

        $syarat_perlu_terakreditasi = "";
        if ($excelAsesmen->syarat_perlu_terakreditasi) {
            $detailCells = explode("-", $excelAsesmen->syarat_perlu_terakreditasi);
            $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

            $syarat_perlu_terakreditasi = $sheet->getCellByColumnAndRow(
                $this->getRowNumberFromAlpabeths($detailCells[1]),
                $detailCells[2]
            )->getCalculatedValue();
        }

        $syarat_perlu_peringkat_unggul = "";
        if ($excelAsesmen->syarat_perlu_peringkat_unggul) {
            $detailCells = explode("-", $excelAsesmen->syarat_perlu_peringkat_unggul);
            $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

            $syarat_perlu_peringkat_unggul = $sheet->getCellByColumnAndRow(
                $this->getRowNumberFromAlpabeths($detailCells[1]),
                $detailCells[2]
            )->getCalculatedValue();
        }

        $syarat_perlu_peringkat_baik_sekali = "";
        if ($excelAsesmen->syarat_perlu_peringkat_baik_sekali) {
            $detailCells = explode("-", $excelAsesmen->syarat_perlu_peringkat_baik_sekali);
            $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

            $syarat_perlu_peringkat_baik_sekali = $sheet->getCellByColumnAndRow(
                $this->getRowNumberFromAlpabeths($detailCells[1]),
                $detailCells[2]
            )->getCalculatedValue();
        }
        ////////////////////////////////////////////////////////
        array_push(
            $resultArray,
            array(
                                    $nilai,
                                    $peringkat,
                                    $syarat_perlu_terakreditasi,
                                    $syarat_perlu_peringkat_unggul,
                                    $syarat_perlu_peringkat_baik_sekali,
                                    ''
                    )
        );
        
        foreach ($arrayCell as $singleArray) {
            $detailCells = explode("-", $singleArray[1]);
            $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

            $result =  $sheet->getCellByColumnAndRow(
                $this->getRowNumberFromAlpabeths($detailCells[1]),
                $detailCells[2]
            )->getCalculatedValue();

            ////////////////////////////////////////////////////////
            $resultRekomendasi = "";
            if ($singleArray[2]) {
                $detailCells = explode("-", $singleArray[2]);
                $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

                $resultRekomendasi = $sheet->getCellByColumnAndRow(
                    $this->getRowNumberFromAlpabeths($detailCells[1]),
                    $detailCells[2]
                )->getCalculatedValue();
            }

            ////////////////////////////////////////////////////////
            $resultTemuanAuditor = "";

            if ($singleArray[3]) {
                $detailCells = explode("-", $singleArray[3]);
                $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

                $resultTemuanAuditor = $sheet->getCellByColumnAndRow(
                    $this->getRowNumberFromAlpabeths($detailCells[1]),
                    $detailCells[2]
                )->getCalculatedValue();
            }

            ////////////////////////////////////////////////////////
            $resultEvaluasiDiriAuditor = "";

            if ($singleArray[4]) {
                $detailCells = explode("-", $singleArray[4]);
                $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

                $resultEvaluasiDiriAuditor =  $sheet->getCellByColumnAndRow(
                    $this->getRowNumberFromAlpabeths($detailCells[1]),
                    $detailCells[2]
                )->getCalculatedValue();
            }

            ////////////////////////////////////////////////////////
            $resultIdentifikasiRisikoAuditor = "";

            if ($singleArray[5]) {
                $detailCells = explode("-", $singleArray[5]);
                $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

                $resultIdentifikasiRisikoAuditor= $sheet->getCellByColumnAndRow(
                    $this->getRowNumberFromAlpabeths($detailCells[1]),
                    $detailCells[2]
                )->getCalculatedValue();
            }
            ////////////////////////////////////////////////////////

            array_push(
                $resultArray,
                array(
                        $singleArray[0],
                        $result,
                        $resultRekomendasi,
                        $resultTemuanAuditor,
                        $resultEvaluasiDiriAuditor,
                        $resultIdentifikasiRisikoAuditor
                    )
            );
        }

        return $resultArray;
    }

    public function getDataFromExcel($path, $detailCell)
    {
        require(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php');
        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load($path);

        $detailCells = explode("-", $detailCell);
        $sheet =$loadexcel->setActiveSheetIndex($detailCells[0] - 1);

        return $sheet->getCellByColumnAndRow($this->getRowNumberFromAlpabeths($detailCells[1]), $detailCells[2])->getOldCalculatedValue();
    }

    public function getRowNumberFromAlpabeths($alphabeths)
    {
        $alphabeths = strtoupper($alphabeths);
        if (strlen($alphabeths) === 1) {
            return $this->getRowNumberFromAlpabeth($alphabeths);
        } elseif (strlen($alphabeths) === 2) {
            return (26 * ($this->getRowNumberFromAlpabeth($alphabeths[0]) + 1)) + $this->getRowNumberFromAlpabeth($alphabeths[1]);
        } elseif (strlen($alphabeths) === 3) {
            return (676 * ($this->getRowNumberFromAlpabeth($alphabeths[0])+1)) + (26 * ($this->getRowNumberFromAlpabeth($alphabeths[1]) + 1)) + $this->getRowNumberFromAlpabeth($alphabeths[1]);
        }
    }

    public function getRowNumberFromAlpabeth($alphabeth)
    {
        if (strtoupper($alphabeth) == 'A') {
            return 0;
        } elseif (strtoupper($alphabeth) == 'B') {
            return 1;
        } elseif (strtoupper($alphabeth) == 'C') {
            return 2;
        } elseif (strtoupper($alphabeth) == 'D') {
            return 3;
        } elseif (strtoupper($alphabeth) == 'E') {
            return 4;
        } elseif (strtoupper($alphabeth) == 'F') {
            return 5;
        } elseif (strtoupper($alphabeth) == 'G') {
            return 6;
        } elseif (strtoupper($alphabeth) == 'H') {
            return 7;
        } elseif (strtoupper($alphabeth) == 'I') {
            return 8;
        } elseif (strtoupper($alphabeth) == 'J') {
            return 9;
        } elseif (strtoupper($alphabeth) == 'K') {
            return 10;
        } elseif (strtoupper($alphabeth) == 'L') {
            return 11;
        } elseif (strtoupper($alphabeth) == 'M') {
            return 12;
        } elseif (strtoupper($alphabeth) == 'N') {
            return 13;
        } elseif (strtoupper($alphabeth) == 'O') {
            return 14;
        } elseif (strtoupper($alphabeth) == 'P') {
            return 15;
        } elseif (strtoupper($alphabeth) == 'Q') {
            return 16;
        } elseif (strtoupper($alphabeth) == 'R') {
            return 17;
        } elseif (strtoupper($alphabeth) == 'S') {
            return 18;
        } elseif (strtoupper($alphabeth) == 'T') {
            return 19;
        } elseif (strtoupper($alphabeth) == 'U') {
            return 20;
        } elseif (strtoupper($alphabeth) == 'V') {
            return 21;
        } elseif (strtoupper($alphabeth) == 'W') {
            return 22;
        } elseif (strtoupper($alphabeth) == 'X') {
            return 23;
        } elseif (strtoupper($alphabeth) == 'Y') {
            return 24;
        } elseif (strtoupper($alphabeth) == 'Z') {
            return 25;
        }
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('ID', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_by_table($table, $id, $data)
    {
        $this->db->where('ID', $id);
        return $this->db->update($table, $data);
    }

    public function update_where_table($table, $where, $data)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }


    public function delete($id)
    {
        $this->db->where('ID', $id);
        $result = $this->db->delete($this->table);

        return $result;
    }

    public function get_all($orderColumn)
    {
        $this->db->order_by($orderColumn, $this->order);
        return $this->db->get($this->table)->result();
    }

    public function get_all_table($table, $where = '')
    {
        if ($where) {
            $this->db->where($where);
        }

        return $this->db->get($table)->result();
    }

    public function get_by_id($id)
    {
        $this->db->where('ID', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id_table($id, $table)
    {
        $this->db->where('ID', $id);
        return $this->db->get($table)->row();
    }

    public function get_by_id_table_detail($id, $column, $table)
    {
        $this->db->where($column, $id);
        return $this->db->get($table)->row();
    }

    public function get_where_table($where, $table)
    {
        $this->db->where($where);
        return $this->db->get($table)->row();
    }
    
    public function get_system_info($idTahunPelaksanaan)
    {
        $this->db->where("idTahunPelaksanaan", $idTahunPelaksanaan);
        return $this->db->get('tinfo')->row();
    }

    public function getDefaultTahunPelaksanaan()
    {
        $sqlQuery = "SELECT * from ttahunpelaksanaan where isDefault=1";
        return $this->db->query($sqlQuery)->row();
    }

    public function getIdTahunPelaksanaan()
    {
        $idTahunPelaksanaan = $this->input->get('idTahunPelaksanaan');
        return !$idTahunPelaksanaan ? $this->getDefaultTahunPelaksanaan()->ID : $idTahunPelaksanaan;
    }

    public function getIdFakultas()
    {
        $idFakultas = $this->input->get('idFakultas');
        return !$idFakultas ? $this->get_all_table("tfakultas")[0]->ID : $idFakultas;
    }

    public function getIdProdi($where= '')
    {
        $idProdi = $this->input->get('idProdi');
        return !$idProdi ? $this->get_all_table("tprodi_unit", $where)[0]->ID : $idProdi;
    }

    public function getRealIdFakultas()
    {
        return $this->input->get('idFakultas');
    }

    public function getRealIdProdi()
    {
        return $this->input->get('idProdi');
    }

    public function getInfoSistem()
    {
        return $this->db->get("tinfosistem")->row();
    }

    public function addEvent($idJenisEvent, $idUser, $keterangan)
    {
        $sqlQuery = "insert into tevent(idJenisEvent,idUser,keterangan) values" .
        "($idJenisEvent, $idUser, '$keterangan')";
        return $this->db->query($sqlQuery);
    }

    public function getTanggalAwal()
    {
        $tanggalAwal = $this->input->get('tanggalAwal');
        return !$tanggalAwal ? date("d-m-Y") : $tanggalAwal;
    }

    public function getTanggalAkhir()
    {
        $tanggalAkhir = $this->input->get('tanggalAkhir');
        return !$tanggalAkhir ? date("d-m-Y") : $tanggalAkhir;
    }

    public function getReverseDate($string)
    {
        $dates = explode("-", $string);
        return $dates[2] .'/'. $dates[1] . '/' . $dates[0];
    }
}