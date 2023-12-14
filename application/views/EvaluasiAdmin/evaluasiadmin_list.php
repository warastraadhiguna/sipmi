<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Evaluasi Admin</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <legend>Evaluasi Program Studi dan Unit</legend>

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
            <?php if ($isSubmitted==1) {?>
            <div class="row">
                <div class="col-md-4">
                    <?php
                        echo anchor(site_url('EvaluasiAdmin/hapusSubmit/'. $idFakultas . "_". $idProdi . "_" . $idTahunPelaksanaan), 'Hapus Submit', 'class="btn btn-warning"');
                ?>
                </div>
            </div>
            <?php }?>
            <hr />
            <label for="varchar">File Master Evaluasi Program Studi dan Unit</label>

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

                                <input type="text" class="form-control" name="fileEvaluasi" id="fileEvaluasi"
                                    placeholder="Nama File S1" value="<?php echo $fileEvaluasi; ?>" disabled />
                                <?php if ($fileEvaluasi) {?>
                                <button type="button" class="btn btn-link" onclick="openDokumen('S1')">Unduh</button>
                                <button type="button" class="btn btn-danger" onclick="deleteFile('S1')">Hapus</button>
                                <?php } else {?>
                                <?php echo form_open_multipart('EvaluasiAdmin/upload'); ?>
                                <input type="file" name="dokumen" width="100px">
                                <input type="hidden" name="jenisMaster" value="S1" />
                                <input type="hidden" name="idFakultasMaster" value="<?php echo $idFakultas; ?>" />
                                <input type="hidden" name="idProdiMaster" value="<?php echo $idProdi; ?>" />
                                <input type="hidden" name="idTahunPelaksanaanMaster"
                                    value="<?php echo $idTahunPelaksanaan; ?>" /> <button type="submit"
                                    class="btn btn-primary">Unggah</button>
                                <?php echo form_close();
                                }?>
                            </div>
                            <div class="tab-pane" id="S2">
                                <input type="text" class="form-control" name="fileEvaluasiS2" id="fileEvaluasiS2"
                                    placeholder="Nama File S2" value="<?php echo $fileEvaluasiS2; ?>" disabled />
                                <?php if ($fileEvaluasiS2) {?>
                                <button type="button" class="btn btn-link" onclick="openDokumen('S2')">Unduh</button>
                                <button type="button" class="btn btn-danger" onclick="deleteFile('S2')">Hapus</button>
                                <?php } else {?>
                                <?php echo form_open_multipart('EvaluasiAdmin/upload'); ?>
                                <input type="file" name="dokumen" width="100px">
                                <input type="hidden" name="jenisMaster" value="S2" />
                                <input type="hidden" name="idFakultasMaster" value="<?php echo $idFakultas; ?>" />
                                <input type="hidden" name="idProdiMaster" value="<?php echo $idProdi; ?>" />
                                <input type="hidden" name="idTahunPelaksanaanMaster"
                                    value="<?php echo $idTahunPelaksanaan; ?>" /> <button type="submit"
                                    class="btn btn-primary">Unggah</button>
                                <?php echo form_close();
                                }?>

                            </div>
                            <div class="tab-pane" id="S3">
                                <input type="text" class="form-control" name="fileEvaluasiS3" id="fileEvaluasiS3"
                                    placeholder="Nama File S3" value="<?php echo $fileEvaluasiS3; ?>" disabled />
                                <?php if ($fileEvaluasiS3) {?>
                                <button type="button" class="btn btn-link" onclick="openDokumen('S3')">Unduh</button>
                                <button type="button" class="btn btn-danger" onclick="deleteFile('S3')">Hapus</button>
                                <?php } else {?>
                                <?php echo form_open_multipart('EvaluasiAdmin/upload'); ?>
                                <input type="file" name="dokumen" width="100px">
                                <input type="hidden" name="jenisMaster" value="S3" />
                                <input type="hidden" name="idFakultasMaster" value="<?php echo $idFakultas; ?>" />
                                <input type="hidden" name="idProdiMaster" value="<?php echo $idProdi; ?>" />
                                <input type="hidden" name="idTahunPelaksanaanMaster"
                                    value="<?php echo $idTahunPelaksanaan; ?>" /> <button type="submit"
                                    class="btn btn-primary">Unggah</button>
                                <?php echo form_close();
                                }?>
                            </div>
                            <div class="tab-pane" id="D3">
                                <input type="text" class="form-control" name="fileEvaluasiD3" id="fileEvaluasiD3"
                                    placeholder="Nama File D3" value="<?php echo $fileEvaluasiD3; ?>" disabled />
                                <?php if ($fileEvaluasiD3) {?>
                                <button type="button" class="btn btn-link" onclick="openDokumen('D3')">Unduh</button>
                                <button type="button" class="btn btn-danger" onclick="deleteFile('D3')">Hapus</button>
                                <?php } else {?>
                                <?php echo form_open_multipart('EvaluasiAdmin/upload'); ?>
                                <input type="file" name="dokumen" width="100px">
                                <input type="hidden" name="jenisMaster" value="D3" />
                                <input type="hidden" name="idFakultasMaster" value="<?php echo $idFakultas; ?>" />
                                <input type="hidden" name="idProdiMaster" value="<?php echo $idProdi; ?>" />
                                <input type="hidden" name="idTahunPelaksanaanMaster"
                                    value="<?php echo $idTahunPelaksanaan; ?>" /> <button type="submit"
                                    class="btn btn-primary">Unggah</button>
                                <?php echo form_close();
                                }?>
                            </div>
                            <div class="tab-pane" id="D4">
                                <input type="text" class="form-control" name="fileEvaluasiD4" id="fileEvaluasiD4"
                                    placeholder="Nama File D4" value="<?php echo $fileEvaluasiD4; ?>" disabled />
                                <?php if ($fileEvaluasiD4) {?>
                                <button type="button" class="btn btn-link" onclick="openDokumen('D4')">Unduh</button>
                                <button type="button" class="btn btn-danger" onclick="deleteFile('D4')">Hapus</button>
                                <?php } else {?>
                                <?php echo form_open_multipart('EvaluasiAdmin/upload'); ?>
                                <input type="file" name="dokumen" width="100px">
                                <input type="hidden" name="jenisMaster" value="D4" />
                                <input type="hidden" name="idFakultasMaster" value="<?php echo $idFakultas; ?>" />
                                <input type="hidden" name="idProdiMaster" value="<?php echo $idProdi; ?>" />
                                <input type="hidden" name="idTahunPelaksanaanMaster"
                                    value="<?php echo $idTahunPelaksanaan; ?>" /> <button type="submit"
                                    class="btn btn-primary">Unggah</button>
                                <?php echo form_close();
                                }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr />

            <div class="box-body">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-4 text-left">
                        <?php
                        echo anchor(site_url('EvaluasiAdmin/ConvertExcel/'. $idFakultas . "_". $idProdi . "_" . $idTahunPelaksanaan), 'Convert Excel', 'class="btn btn-primary"');
        ?>
                        <?php
            echo anchor(site_url('EvaluasiAdmin/DeleteEvaluasi/'. $idFakultas . "_". $idProdi . "_" . $idTahunPelaksanaan), 'Hapus Evaluasi', 'class="btn btn-danger" onclick="return confirm(\'Yakin mau menghapus data ini?\')"');
        ?>
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
                            <th>Nilai Auditor</th>
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
                            "url": "EvaluasiAdmin/json/",
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
                                "data": "namaKebijakan"
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
                    window.open(window.location.pathname + '?idFakultas=' + idFakultas + '&idProdi=' + idProdi +
                        '&idTahunPelaksanaan=' + idTahunPelaksanaan, '_self');
                });

                $('select[name="idTahunPelaksanaan"]').on('change', function() {
                    var idFakultas = $('select[name="idFakultas"]').val();
                    var idProdi = $('select[name="idProdi"]').val();
                    var idTahunPelaksanaan = $('select[name="idTahunPelaksanaan"]').val();
                    window.open(window.location.pathname + '?idFakultas=' + idFakultas + '&idProdi=' + idProdi +
                        '&idTahunPelaksanaan=' + idTahunPelaksanaan, '_self');
                });

                function convertExcel() {
                    var myFormData = {
                        idProdi: $('select[name="idProdi"]').val(),
                        idTahunPelaksanaan: $('select[name="idTahunPelaksanaan"]').val()
                    };

                    $.ajax({
                        type: 'POST',
                        url: "<?php echo site_url('EvaluasiAdmin/ConvertExcel'); ?>",
                        data: myFormData,
                        success: function(data) {
                            location.reload();
                            if (data == "") {
                                location.reload();
                            } else {
                                alert(data);
                            }
                        }
                    });
                }

                function openDokumen(jenisMaster) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo site_url('EvaluasiAdmin/downloadEmptyFile/' . $idTahunPelaksanaan. '/'); ?>" +
                            jenisMaster,
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

                function deleteFile(jenisMaster) {
                    var confirmation = confirm("Anda yakin menghapus dokumen ini?");
                    if (confirmation) {
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo site_url('EvaluasiAdmin/deleteDokumen/'  . $idTahunPelaksanaan. '/'); ?>" +
                                jenisMaster,
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
                </script>