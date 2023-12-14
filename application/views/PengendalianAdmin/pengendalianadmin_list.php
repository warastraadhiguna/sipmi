<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pengendalian</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-8">
                    <h2 style="margin-top:0px">Pengendalian</h2>
                </div>
            </div>

            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-4 text-center">

                </div>
                <div class="col-md-4 text-center">
                    <?= showFlashMessage() ?>
                </div>
                <div class="col-md-4 text-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#asesmen"><i
                            class="fa fa-sort-numeric-asc"></i>Asesmen Kecukupan</button>
                </div>
            </div>

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

            <table class="table table-bordered table-striped" id="mytable">
                <thead>
                    <tr>
                        <th width="80px">No</th>
                        <th>Kode Standar</th>
                        <th>Indikator AMI</th>
                        <th>Rekomendasi Auditor</th>
                        <th>Temuan Auditor</th>
                        <th>Evaluasi Diri Prodi</th>
                        <th>Tindak Lanjut</th>
                        <th>Action</th>
                    </tr>
                </thead>

            </table>

            <div class="modal fade" id="ModalUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Unggah Dokumen</h4>
                        </div>
                        <div id="modalBody" class="modal-body">
                            <?php echo form_open_multipart('PengendalianAdmin/upload'); ?>
                            <input type="hidden" name="idDetailEvaluasi">

                            <input type="file" name="dokumen" width="100px">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Unggah</button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="asesmen">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center">Laporan Asesmen Kecukupan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                        if ($asesmen_kecukupan) {
                            ?>
                            <div class="callout callout-primary">
                                <div class="form-group">
                                    <label for="varchar">Nilai<?php echo form_error('nilai') ?></label>
                                    <input type="text" class="form-control" name="nilai" id="nilai"
                                        placeholder="sheet-kolom-baris" value="<?php echo $asesmen_kecukupan->nilai; ?>"
                                        readonly />
                                </div>
                                <div class="form-group">
                                    <label for="varchar">Peringkat<?php echo form_error('peringkat') ?></label>
                                    <input type="text" class="form-control" name="peringkat" id="peringkat"
                                        placeholder="sheet-kolom-baris"
                                        value="<?php echo $asesmen_kecukupan->peringkat; ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="varchar">Syarat Perlu
                                        Terakreditasi<?php echo form_error('syarat_perlu_terakreditasi') ?></label>
                                    <input type="text" class="form-control" name="syarat_perlu_terakreditasi"
                                        id="syarat_perlu_terakreditasi" placeholder="sheet-kolom-baris"
                                        value="<?php echo $asesmen_kecukupan->syarat_perlu_terakreditasi; ?>"
                                        readonly />
                                </div>

                                <div class="form-group">
                                    <label for="varchar">Syarat Perlu Peringkat Unggul
                                        Auditor<?php echo form_error('syarat_perlu_peringkat_unggul') ?></label>
                                    <input type="text" class="form-control" name="syarat_perlu_peringkat_unggul"
                                        id="syarat_perlu_peringkat_unggul" placeholder="sheet-kolom-baris"
                                        value="<?php echo $asesmen_kecukupan->syarat_perlu_peringkat_unggul; ?>"
                                        readonly />
                                </div>

                                <div class="form-group">
                                    <label for="varchar">Syarat Perlu Peringkat Baik Sekali
                                        Auditor<?php echo form_error('syarat_perlu_peringkat_baik_sekali') ?></label>
                                    <input type="text" class="form-control" name="syarat_perlu_peringkat_baik_sekali"
                                        id="syarat_perlu_peringkat_baik_sekali" placeholder="sheet-kolom-baris"
                                        value="<?php echo $asesmen_kecukupan->syarat_perlu_peringkat_baik_sekali; ?>"
                                        readonly />
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

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
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
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
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: {
                        "url": "PengendalianAdmin/json/",
                        "data": function(data) {
                            data.idProdi = $("select[name=idProdi]").val();
                            data.idTahunPelaksanaan = $("select[name=idTahunPelaksanaan]").val();
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
                            "data": "rekomendasiAuditor",
                            "orderable": false,
                            "render": function(data) {
                                var result = "";
                                if (data.length <= 30) {
                                    result = data;
                                } else {
                                    //result = data;
                                    result = data.substring(0, 30) +
                                        '<a href="#" onClick="showFullMessage(\'' + data
                                        .replace(/\n/g, " ") +
                                        '\')" >[...] </a> ';
                                }

                                return result;
                            }
                        },
                        {
                            "data": "temuanAuditor",
                            "orderable": false,
                            "render": function(data) {
                                var result = "";
                                if (data.length <= 30) {
                                    result = data;
                                } else {
                                    result = data.substring(0, 30) +
                                        '<a href="#" onClick="showFullMessage(\'' + data
                                        .replace(/\n/g, " ") +
                                        '\')" >[...] </a> ';
                                }

                                return result;
                            }
                        },
                        {
                            "data": "evaluasiDiriAuditor",
                            "orderable": false,
                            "render": function(data) {
                                var result = "";
                                if (data.length <= 30) {
                                    result = data;
                                } else {
                                    result = data.substring(0, 30) +
                                        '<a href="#" onClick="showFullMessage(\'' + data
                                        .replace(/\n/g, " ") +
                                        '\')" >[...] </a> ';
                                }

                                return result;
                            }
                        },
                        {
                            "data": "dokumen",
                            "searchable": false
                        },
                        {
                            "data": "action",
                            "orderable": false,
                            "className": "text-center"
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

            function showFullMessage(message) {
                alert(message);
            }

            $('select[name="idFakultas"]').on('change', function() {
                var idFakultas = $('select[name="idFakultas"]').val();
                var idTahunPelaksanaan = $('select[name="idTahunPelaksanaan"]').val();
                window.open(window.location.pathname + '?idFakultas=' + idFakultas + '&idTahunPelaksanaan=' +
                    idTahunPelaksanaan, '_self');
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

            function unggahBerkas(ID) {
                $('#ModalUpload').modal({
                    backdrop: 'static',
                    show: true
                });

                $("input[name='idDetailEvaluasi']").val(ID);
            }

            function openDokumen(ID) {
                var myFormData = {
                    ID: ID
                };

                $.ajax({
                    type: 'POST',
                    url: "<?php echo site_url('PengendalianAdmin/getUrlDokumen'); ?>",
                    data: myFormData,
                    success: function(data) {
                        if (data == "") {
                            alert("Dokumen tidak dapat dibuka!!");
                        } else {
                            url = data.replace("/", "").replace(/["']/g, "");
                            //alert(url);
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
                        url: "<?php echo site_url('PengendalianAdmin/deleteDokumen'); ?>",
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
            </script>