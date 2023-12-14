<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Penetapan</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <div class="box">
        <div class="box-body">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-4">
                    <h2 style="margin-top:0px">Penetapan</h2>
                </div>
                <div class="col-md-4 text-center">
                    <?= showFlashMessage() ?>
                </div>
                <div class="col-md-4 text-right">
                    <?php echo anchor(site_url('PenetapanAdmin/create/'. $idTahunPelaksanaan), '<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;Create', 'class="btn btn-primary"'); ?>

                    <button class="btn btn-success" data-toggle="modal" data-target="#asesmen"><i
                            class="fa fa-sort-numeric-asc"></i>Asesmen Kecukupan</button>
                </div>
            </div>

            <label for="varchar">Tahun</label>
            <div class="form-group row">
                <div class="col-md-2">
                    <?php
                        echo comboboxdatatables('idTahunPelaksanaan', 'ttahunpelaksanaan', 'tahun', 'ID', $idTahunPelaksanaan, '', 'tahun');
                    ?>
                </div>
            </div>
            <hr />
            <table class="table table-bordered table-striped" id="mytable">
                <thead>
                    <tr>
                        <th width="80px">No</th>
                        <th>Kode Dokumen</th>
                        <th>Nama Dokumen</th>
                        <th>Excel Auditor</th>
                        <th>Excel Rekomendasi</th>
                        <th>Excel Temuan</th>
                        <th>Excel Evaluasi Diri</th>
                        <th>Excel Identifikasi Risiko</th>
                        <th>Status</th>
                        <th width="300px">Action</th>
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
                            <?php echo form_open_multipart('PenetapanAdmin/upload'); ?>
                            <input type="hidden" name="IdKebijakan">

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
                            <h4 class="modal-title text-center">Edit Excel Asesmen Kecukupan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="callout callout-primary">
                                <form action="<?php echo $action; ?>" method="post">
                                    <div class="form-group">
                                        <label for="varchar">Excel Nilai<?php echo form_error('nilai') ?></label>
                                        <input type="text" class="form-control" name="nilai" id="nilai"
                                            placeholder="sheet-kolom-baris"
                                            value="<?php echo $asesmen_kecukupan->nilai; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="varchar">Excel
                                            Peringkat<?php echo form_error('peringkat') ?></label>
                                        <input type="text" class="form-control" name="peringkat" id="peringkat"
                                            placeholder="sheet-kolom-baris"
                                            value="<?php echo $asesmen_kecukupan->peringkat; ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="varchar">Excel Syarat Perlu
                                            Terakreditasi<?php echo form_error('syarat_perlu_terakreditasi') ?></label>
                                        <input type="text" class="form-control" name="syarat_perlu_terakreditasi"
                                            id="syarat_perlu_terakreditasi" placeholder="sheet-kolom-baris"
                                            value="<?php echo $asesmen_kecukupan->syarat_perlu_terakreditasi; ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="varchar">Excel Syarat Perlu Peringkat Unggul
                                            Auditor<?php echo form_error('syarat_perlu_peringkat_unggul') ?></label>
                                        <input type="text" class="form-control" name="syarat_perlu_peringkat_unggul"
                                            id="syarat_perlu_peringkat_unggul" placeholder="sheet-kolom-baris"
                                            value="<?php echo $asesmen_kecukupan->syarat_perlu_peringkat_unggul; ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="varchar">Excel Syarat Perlu Peringkat Baik Sekali
                                            Auditor<?php echo form_error('syarat_perlu_peringkat_baik_sekali') ?></label>
                                        <input type="text" class="form-control"
                                            name="syarat_perlu_peringkat_baik_sekali"
                                            id="syarat_perlu_peringkat_baik_sekali" placeholder="sheet-kolom-baris"
                                            value="<?php echo $asesmen_kecukupan->syarat_perlu_peringkat_baik_sekali; ?>" />
                                    </div>

                                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                </form>

                            </div>
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
                        "url": "PenetapanAdmin/json/" + <?php echo $idTahunPelaksanaan;?>,
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
                            "data": "excelAuditor",
                            "searchable": false
                        },
                        {
                            "data": "excelRekomendasiAuditor",
                            "searchable": false
                        },
                        {
                            "data": "excelTemuanAuditor",
                            "searchable": false
                        },
                        {
                            "data": "excelEvaluasiDiriAuditor",
                            "searchable": false
                        },
                        {
                            "data": "excelIdentifikasiRisikoAuditor",
                            "searchable": false
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
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    pageLength: -1,
                    order: [
                        [1, 'asc']
                    ],
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
            $('select[name="idTahunPelaksanaan"]').on('change', function() {
                var value = $('select[name="idTahunPelaksanaan"]').val();
                window.open(window.location.pathname + '?idTahunPelaksanaan=' + value, '_self');
            });

            function unggahBerkas(ID) {
                $('#ModalUpload').modal({
                    backdrop: 'static',
                    show: true
                });

                $("input[name='IdKebijakan']").val(ID);
            }

            function openDokumen(ID) {
                var myFormData = {
                    ID: ID
                };

                $.ajax({
                    type: 'POST',
                    url: "<?php echo site_url('PenetapanAdmin/getUrlDokumen'); ?>",
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
                        url: "<?php echo site_url('PenetapanAdmin/deleteDokumen'); ?>",
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