<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Evaluasi Prodi</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <legend>Dokumen Evaluasi Program Studi dan Unit</legend>
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#S1" data-toggle="tab">S1</a>
                            </li>
                            <li><a href="#S2" data-toggle="tab">S2</a>
                            </li>
                        </ul>

                        <div class="tab-content ">
                            <div class="tab-pane active" id="S1">
                                <button type="button" class="btn btn-link" data-dismiss="modal"
                                    onclick="donwloadFileMaster(<?php echo $idTahunPelaksanaan; ?>,'S1')">UNDUH FORMULIR
                                    EVALUASI KETERCAPAIAN
                                    STANDAR S1 (Excel kosong) </button>
                            </div>
                            <div class="tab-pane" id="S2">
                                <button type="button" class="btn btn-link" data-dismiss="modal"
                                    onclick="donwloadFileMaster(<?php echo $idTahunPelaksanaan; ?>,'S2')">UNDUH FORMULIR
                                    EVALUASI KETERCAPAIAN
                                    STANDAR S2 (Excel kosong) </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </br>
            </br>

            <label for="varchar">Nama File</label>
            <input type="text" class="form-control" name="fileEvaluasi" id="fileEvaluasi" placeholder="Nama File"
                value="<?php echo $fileEvaluasi; ?>" disabled />
            <?php if ($fileEvaluasi) {?>
            <button type="button" class="btn btn-link" onclick="openDokumen(<?php echo $idEvaluasi; ?>)">Unduh</button>
            <?php if (!$isSubmitted) {?>
            <button type="button" class="btn btn-danger" onclick="deleteFile(<?php echo $idEvaluasi; ?>)">Hapus</button>
            <button type="button" class="btn btn-warning"
                onclick="submitData(<?php echo $idEvaluasi; ?>)">Submit</button>
            <?php }?>
            <?php } else {?>
            <?php echo form_open_multipart('EvaluasiProdi/upload'); ?>
            <input type="hidden" name="idEvaluasi" value="<?php echo $idEvaluasi; ?>" />
            <input type="file" name="dokumen" width="100px">

            <div class="row">
                <!-- panel-footer -->
                <div class="col-xs-6 text-left">
                    <button type="submit" class="btn btn-primary">Unggah Data Baru</button>
                    <?php echo form_close();?>
                    <?php
                        // Button untuk membuat data baru
                        echo anchor(site_url('EvaluasiProdi/unggahdatalama/' . $idEvaluasi), 'Unggah Data Lama', 'class="btn btn-warning"');
                ?>
                </div>
            </div>
            <?php }?>

            <div class="box">
                <div class="box-body">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-8">
                            <h2 style="margin-top:0px">Evaluasi Program Studi dan Unit</h2>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-4 text-center">
                        </div>
                        <div class="col-md-4 text-center">
                            <?= showFlashMessage() ?>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped" id="mytable">
                        <thead>
                            <tr>
                                <th width="80px">No</th>
                                <th>Kode Standar</th>
                                <th>Nama Standar</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>

                    </table>

                    <!-- Memanggil jQuery -->
                    <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
                    <!-- Memanggil jQuery data tables -->
                    <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
                    <!-- Memanggil Bootstrap data tables -->
                    <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>

                    <!-- JavaScript yang berfungsi untuk menampilkan data dari tabel program studi dengan AJAX -->
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
                            return {
                                "iStart": oSettings._iDisplayStart,
                                "iEnd": oSettings.fnDisplayEnd(),
                                "iLength": oSettings._iDisplayLength,
                                "iTotal": oSettings.fnRecordsTotal(),
                                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings
                                    ._iDisplayLength)
                            };
                        };

                        var t = $("#mytable").dataTable({
                            initComplete: function() {
                                var api = this.api();
                                $('#mytable_filter input')
                                    .off('.DT')
                                    .on('keyup.DT', function(e) {
                                        if (e.keyCode == 13) {
                                            api.search(this.value).draw();
                                        }
                                    });
                            },
                            oLanguage: {
                                sProcessing: "loading...",
                                sEmptyTable: "Data belum disubmit auditor"
                            },
                            processing: true,
                            serverSide: true,
                            scrollX: true,
                            ajax: {
                                "url": "EvaluasiProdi/json",
                                "data": function(data) {
                                    data.idTahunPelaksanaan = <?php echo $idTahunPelaksanaan;?>;
                                },
                                "defaultContent": "",
                                "type": "POST"
                            },
                            columns: [{
                                    "data": "ID",
                                    "orderable": false
                                },
                                {
                                    "data": "kode"
                                },
                                {
                                    "data": "nama"
                                },
                                {
                                    "data": "nilaiAuditor"
                                }
                            ],
                            order: [
                                [1, 'asc']
                            ],
                            lengthMenu: [
                                [10, 25, 50, 100, -1],
                                [10, 25, 50, 100, "All"]
                            ],
                            pageLength: -1,
                            rowCallback: function(row, data, iDisplayIndex) {
                                var info = this.fnPagingInfo();
                                var page = info.iPage;
                                var length = info.iLength;
                                var index = page * length + (iDisplayIndex + 1);
                                $('td:eq(0)', row).html(index);
                            }
                        });
                    });
                    </script>

                    <script type="text/javascript">
                    function openDokumen(ID) {
                        var myFormData = {
                            ID: ID
                        };

                        $.ajax({
                            type: 'POST',
                            url: "<?php echo site_url('EvaluasiProdi/getUrlDokumen'); ?>",
                            data: myFormData,
                            success: function(data) {
                                if (data == "") {
                                    alert("Dokumen tidak dapat dibuka!!");
                                } else {
                                    url = data.replace("/", "").replace(/["']/g, "");
                                    window.open(url);
                                }
                            }
                        });
                    }

                    function deleteFile(ID) {
                        var confirmation = confirm("Anda yakin menghapus dokumen ini?");
                        if (confirmation) {
                            var myFormData = {
                                ID: ID
                            };

                            $.ajax({
                                type: 'POST',
                                url: "<?php echo site_url('EvaluasiProdi/deleteDokumen'); ?>",
                                data: myFormData,
                                success: function(data) {
                                    if (data == "") {
                                        alert("File berhasil dihapus!!");
                                        location.reload();
                                    } else {
                                        alert(data);
                                    }
                                }
                            });
                        }
                    }

                    function submitData(ID) {
                        var confirmation = confirm(
                            "Anda yakin untuk submit data (jika sudah disubmit, maka data tidak dapat diubah)?");
                        if (confirmation) {
                            var myFormData = {
                                ID: ID
                            };

                            $.ajax({
                                type: 'POST',
                                url: "<?php echo site_url('EvaluasiProdi/submitData'); ?>",
                                data: myFormData,
                                success: function(data) {
                                    if (data == "") {
                                        alert("Data berhasil disubmit!!");
                                        location.reload();
                                    } else {
                                        alert(data);
                                    }
                                }
                            });
                        }
                    }

                    function prosesDokumen(ID) {
                        var confirmation = confirm("Anda yakin memproses dokumen ini?");
                        if (confirmation) {
                            var myFormData = {
                                ID: ID
                            };

                            $.ajax({
                                type: 'POST',
                                url: "<?php echo site_url('EvaluasiProdi/prosesDokumen'); ?>",
                                data: myFormData,
                                success: function(data) {
                                    if (data == "") {
                                        alert("Dokumen berhasil diproses!!");
                                        location.reload();
                                    } else {
                                        alert(data);
                                    }
                                }
                            });
                        }
                    }

                    function donwloadFileMaster(idTahunPelaksanaan, jenisMaster) {
                        var myFormData = {
                            idTahunPelaksanaan: idTahunPelaksanaan,
                            jenisMaster: jenisMaster
                        };

                        $.ajax({
                            type: 'POST',
                            url: "<?php echo site_url('EvaluasiProdi/downloadEmptyFile'); ?>",
                            data: myFormData,
                            success: function(data) {
                                if (data == "") {
                                    alert("Dokumen tidak dapat dibuka!!");
                                } else {
                                    url = data.replace("/", "").replace(/["']/g, "");
                                    window.open(url);
                                }
                            }
                        });
                    }
                    </script>