<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Evaluasi Auditor</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <legend>Dokumen Evaluasi Auditor</legend>

            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-6">
                    <label for="varchar">Fakultas</label>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <?php
                                echo combobox('idFakultas', 'tfakultas', 'nama', 'ID', $idFakultas, '', 'nama');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="varchar">Prodi</label>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <?php
                                echo combobox('idProdi', 'tprodi_unit', 'nama', 'ID', $idProdi, 'idFakultas=' . $idFakultas, 'idFakultas');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="varchar">Tahun</label>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <?php
                                echo comboboxdatatables('idTahunPelaksanaan', 'ttahunpelaksanaan', 'tahun', 'ID', $idTahunPelaksanaan, '', 'tahun');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr />

            <div id="dataInput">
                <?php if (!$fileEvaluasi) {?>
                <button type="button" class="btn btn-link" data-dismiss="modal"
                    onclick="donwloadFileMaster(<?php echo $idEvaluasi; ?>)">UNDUH FORMULIR EVALUASI KETERCAPAIAN
                    STANDAR</button>
                </br>

                <?php } ?>

                <label for="varchar">Nama File</label>
                <input type="text" class="form-control" name="fileEvaluasi" id="fileEvaluasi" placeholder="Nama File"
                    value="<?php echo $fileEvaluasi; ?>" disabled />
                <?php if ($fileEvaluasi) {?>
                <button type="button" class="btn btn-primary" data-dismiss="modal"
                    onclick="prosesDokumen(<?php echo $idEvaluasi; ?>)">Proses</button>
                <button type="button" class="btn btn-link" data-dismiss="modal"
                    onclick="openDokumen(<?php echo $idEvaluasi; ?>)">Unduh</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="deleteFile(<?php echo $idEvaluasi; ?>)">Hapus</button>
                <?php } else {?>

                <?php echo form_open_multipart('EvaluasiAuditor/upload'); ?>
                <input type="hidden" name="idEvaluasi" value="<?php echo $idEvaluasi; ?>" />
                <input type="hidden" name="idFakultasUpload" value="<?php echo $idFakultas; ?>" />
                <input type="hidden" name="idProdiUpload" value="<?php echo $idProdi; ?>" />
                <input type="hidden" name="idTahunPelaksanaanUpload" value="<?php echo $idTahunPelaksanaan; ?>" />
                <input type="file" name="dokumen" width="100px">
                <button type="submit" class="btn btn-primary">Unggah</button>

                <?php echo form_close();} ?>
            </div>

            <div class="box">
                <div class="box-body">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-8">
                            <h2 style="margin-top:0px">Evaluasi Auditor</h2>
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
                        var idEvaluasi = '<?php echo $idEvaluasi;?>';
                        if (idEvaluasi == '')
                            document.getElementById("dataInput").style.display = 'none';

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
                                "url": "EvaluasiAuditor/json",
                                "data": function(data) {
                                    data.idProdi = $("select[name=idProdi]").val();
                                    data.idTahunPelaksanaan = $("select[name=idTahunPelaksanaan]")
                                        .val();
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
                    $('select[name="idFakultas"]').on('change', function() {
                        var idFakultas = $('select[name="idFakultas"]').val();
                        var idTahunPelaksanaan = $('select[name="idTahunPelaksanaan"]').val();
                        window.open(window.location.pathname + '?idFakultas=' + idFakultas +
                            '&idTahunPelaksanaan=' + idTahunPelaksanaan, '_self');
                    });

                    $('select[name="idProdi"]').on('change', function() {
                        var idFakultas = $('select[name="idFakultas"]').val();
                        var idProdi = $('select[name="idProdi"]').val();
                        var idTahunPelaksanaan = $('select[name="idTahunPelaksanaan"]').val();
                        window.open(window.location.pathname + '?idFakultas=' + idFakultas + '&idProdi=' +
                            idProdi + '&idTahunPelaksanaan=' + idTahunPelaksanaan, '_self');
                    });

                    $('select[name="idTahunPelaksanaan"]').on('change', function() {
                        var idFakultas = $('select[name="idFakultas"]').val();
                        var idProdi = $('select[name="idProdi"]').val();
                        var idTahunPelaksanaan = $('select[name="idTahunPelaksanaan"]').val();
                        window.open(window.location.pathname + '?idFakultas=' + idFakultas + '&idProdi=' +
                            idProdi + '&idTahunPelaksanaan=' + idTahunPelaksanaan, '_self');
                    });

                    function openDokumen(ID) {
                        var myFormData = {
                            ID: ID
                        };

                        $.ajax({
                            type: 'POST',
                            url: "<?php echo site_url('EvaluasiAuditor/getUrlDokumen'); ?>",
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

                    function donwloadFileMaster(ID) {
                        var myFormData = {
                            ID: ID
                        };

                        $.ajax({
                            type: 'POST',
                            url: "<?php echo site_url('EvaluasiAuditor/downloadEmptyFile'); ?>",
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
                                url: "<?php echo site_url('EvaluasiAuditor/deleteDokumen'); ?>",
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

                    function prosesDokumen(ID) {
                        var confirmation = confirm("Anda yakin memproses dokumen ini?");
                        if (confirmation) {
                            var myFormData = {
                                ID: ID
                            };

                            $.ajax({
                                type: 'POST',
                                url: "<?php echo site_url('EvaluasiAuditor/prosesDokumen'); ?>",
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
                    </script>