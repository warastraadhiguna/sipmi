<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Pelaksanaan</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-4">
                    <h2 style="margin-top:0px">Data Pelaksanaan</h2>
                </div>
                <div class="col-md-4 text-center">
                    <?= showFlashMessage() ?>
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
                        <th>Nama Standar</th>
                        <th>Nama Dokumen Bukti</th>
                        <th>Status</th>
                        <th>Action</th>
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
                        "url": "DataPelaksanaan/json",
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
                            "data": "namaPelaksanaan"
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
            </script>

            <script type="text/javascript">
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

            function openDokumen(ID) {
                var myFormData = {
                    ID: ID
                };

                $.ajax({
                    type: 'POST',
                    url: "<?php echo site_url('DataPelaksanaan/getUrlDokumen'); ?>",
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