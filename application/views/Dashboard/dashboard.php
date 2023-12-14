<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">

            <!-- Data Dashboard -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-12">
                    <h2 style="margin-top:0px">Dashboard Peringkat Evaluasi <?php echo $tahun;?></h2>
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
                        <th>Fakultas</th>
                        <th>Program Studi</th>
                        <th width="80px" class="text-center">Nilai</th>
                        <th width="80px" class="text-center">Jumlah Dosen</th>
                        <th width="80px" class="text-center">Jumlah Dokumen Dosen</th>
                    </tr>
                </thead>

            </table>
            <!-- Memanggil jQuery -->
            <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
            <!-- Memanggil jQuery data tables -->
            <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
            <!-- Memanggil Bootstrap data tables -->
            <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>

            <!-- JavaScript yang berfungsi untuk menampilkan data dari tabel tahun akademik dengan AJAX -->
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
                        "url": "Dashboard/json",
                        "type": "POST"
                    },
                    columns: [{
                            "data": "ID",
                            "searchable": false,
                        },
                        {
                            "data": "namaFakultas"
                        },
                        {
                            "data": "namaProdi",
                        },
                        {
                            "data": "rerata",
                            "searchable": false,
                            "class": "text-right"
                        },
                        {
                            "data": "jumlahDosen",
                            "searchable": false,
                            "class": "text-right"
                        },
                        {
                            "data": "jumlahDokumen",
                            "searchable": false,
                            "class": "text-right"
                        },
                    ],
                    order: [
                        [3, 'desc']
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