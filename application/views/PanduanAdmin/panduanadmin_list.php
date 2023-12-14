<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Panduan</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <legend>Panduan Pengisian</legend>

            <label for="varchar">Tahun</label>
            <div class="form-group row">
                <div class="col-md-2">
                    <?php
                        echo comboboxdatatables('idTahunPelaksanaan', 'ttahunpelaksanaan', 'tahun', 'ID', $idTahunPelaksanaan, '', 'tahun');
                    ?>
                </div>
            </div>
            <hr />
            <form action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="varchar">Text Panduan Pengisian<?php echo form_error('panduanPengisian') ?></label>
                    <textarea rows="5" class="form-control" name="panduanPengisian" id="panduanPengisian"
                        placeholder="Text Panduan" /><?php echo $panduanPengisian; ?></textarea>
                </div>
                <input type="hidden" name="idInfo" value="<?php echo $idInfo; ?>" />
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
            </form>
            </br>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-4">
                        <h2 style="margin-top:0px">Dokumen Panduan</h2>
                    </div>
                    <div class="col-md-4 text-center">
                        <?= showFlashMessage() ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <?php echo anchor(site_url('PanduanAdmin/create/' . $idInfo), '<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;Create', 'class="btn btn-primary"'); ?>
                    </div>
                </div>
                <table class="table table-bordered table-striped" id="mytable">
                    <thead>
                        <tr>
                            <th width="80px">No</th>
                            <th>Keterangan</th>
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
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Unggah Dokumen</h4>
                            </div>
                            <div id="modalBody" class="modal-body">
                                <?php echo form_open_multipart('PanduanAdmin/upload'); ?>
                                <input type="hidden" name="idpanduanadmin">
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
                            sProcessing: "loading..."
                        },
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            "url": "PanduanAdmin/json/" + <?php echo $idInfo;?>,
                            "type": "POST"
                        },
                        columns: [{
                                "data": "ID",
                                "orderable": false
                            },
                            {
                                "data": "keterangan"
                            },
                            {
                                "data": "dokumen"
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

                    $("input[name='idpanduanadmin']").val(ID);
                }

                function openDokumen(ID) {
                    var myFormData = {
                        ID: ID
                    };

                    $.ajax({
                        type: 'POST',
                        url: "<?php echo site_url('PanduanAdmin/getUrlDokumen'); ?>",
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
                            url: "<?php echo site_url('PanduanAdmin/deleteDokumen'); ?>",
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