<style type="text/css">
p {
    white-space: pre-wrap;
}
</style>

<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Panduan </li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="form-group">
                <h1 style="margin-top:0px">Panduan <?php echo form_error('panduanPengisian') ?></h1>
                <p><?php echo $panduanPengisian; ?></p>
            </div>
            </br>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-4">
                        <h1 style="margin-top:0px">Dokumen</h1>
                    </div>
                    <div class="col-md-4 text-center">
                        <?= showFlashMessage() ?>
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


                <!-- Memanggil jQuery -->
                <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
                <!-- Memanggil jQuery data tables -->
                <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
                <!-- Memanggil Bootstrap data tables -->
                <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>

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
                            "url": "Panduan/json",
                            "type": "POST"
                        },
                        columns: [{
                                "data": "ID",
                                "orderable": false
                            },
                            {
                                "data": "keterangan"
                            }, {
                                "data": "dokumen",
                                "searchable": false,
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
                function unggahBerkas(ID) {
                    $('#ModalUpload').modal({
                        backdrop: 'static',
                        show: true
                    });

                    $("input[name='idPanduan']").val(ID);
                }

                function openDokumen(ID) {
                    var myFormData = {
                        ID: ID
                    };

                    $.ajax({
                        type: 'POST',
                        url: "<?php echo site_url('Panduan/getUrlDokumen'); ?>",
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