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

            <!-- Data History -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-12">
                    <h2 style="margin-top:0px">History</h2>
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-4 text-center">
                </div>
                <div class="col-md-4 text-center">
                    <?= showFlashMessage() ?>
                </div>
            </div>

            <label for="varchar">Range Tanggal</label>
            <div class="form-group row">
                <div class="col-md-2">
                    <input type="text" name="tanggalAwal" value="<?php echo $tanggalAwal;?>" class="form-control waktu"
                        placeholder="Tanggal Awal" id="tanggalAwal">
                </div>
                <div class="col-md-2">
                    <input type="text" name="tanggalAkhir" value="<?php echo $tanggalAkhir;?>"
                        class="form-control waktu" placeholder="Tanggal Akhir" id="tanggalAkhir">
                </div>
                <?php
                       //  echo anchor(site_url('History/hapus'), 'Hapus', 'class="btn btn-danger" onclick="return confirm(\'Yakin mau menghapus data pada range tanggal ini?\')"');
                    ?>

            </div>

            <table class="table table-bordered table-striped" id="mytable">
                <thead>
                    <tr>
                        <th width="80px">No</th>
                        <th>Jenis</th>
                        <th>User</th>
                        <th>Keterangan</th>
                        <th>Waktu</th>
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
                        "url": "History/json",
                        "data": function(data) {
                            data.tanggalAwal = $("input[name=tanggalAwal]").val();
                            data.tanggalAkhir = $("input[name=tanggalAkhir]").val();
                        },
                        "type": "POST"
                    },
                    columns: [{
                            "data": "ID",
                            "searchable": false,
                        },
                        {
                            "data": "jenisEvent"
                        },
                        {
                            "data": "nama",
                        },
                        {
                            "data": "keterangan",
                            "orderable": false,
                            "searchable": false,
                            "render": function(data) {
                                var result = "";
                                if (data.length <= 50) {
                                    result = data;
                                } else {
                                    coba = 'asdf';
                                    result = data.substring(0, 50) +
                                        '<a href="#" onClick="showFullMessage(\'' + data.trim()
                                        .replace(/\n/g, '') +
                                        '\')" >[...] </a> ';
                                }

                                return result;
                            }
                        },
                        {
                            "data": "waktu_tampil",
                            "searchable": false,
                        },
                    ],
                    order: [
                        [4, 'desc']
                    ],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    pageLength: 10,
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

            $('input[name="tanggalAwal"]').on('change', function() {
                var tanggalAwal = $('input[name="tanggalAwal"]').val();
                var tanggalAkhir = $('input[name="tanggalAkhir"]').val();
                window.open(window.location.pathname + '?tanggalAwal=' + tanggalAwal + '&tanggalAkhir=' +
                    tanggalAkhir, '_self');
            });

            $('input[name="tanggalAkhir"]').on('change', function() {
                var tanggalAwal = $('input[name="tanggalAwal"]').val();
                var tanggalAkhir = $('input[name="tanggalAkhir"]').val();
                window.open(window.location.pathname + '?tanggalAwal=' + tanggalAwal + '&tanggalAkhir=' +
                    tanggalAkhir, '_self');
            });
            </script>