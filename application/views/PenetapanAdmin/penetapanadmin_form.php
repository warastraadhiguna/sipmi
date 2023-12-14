<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Penetapan</a></li>
        <li class="active"><?php echo $button ?> Penetapan</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">

            <legend><?php echo $button ?> Penetapan</legend>
            <form action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="varchar">Kode Dokumen <?php echo form_error('kode') ?></label>

                    <input type="text" class="form-control" name="kode" id="kode" placeholder="Kode Dokumen"
                        value="<?php echo $kode; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Nama Dokumen <?php echo form_error('nama') ?></label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Dokumen"
                        value="<?php echo $nama; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Excel Auditor<?php echo form_error('excelAuditor') ?></label>
                    <input type="text" class="form-control" name="excelAuditor" id="excelAuditor"
                        placeholder="sheet-kolom-baris" value="<?php echo $excelAuditor; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Excel Rekomendasi
                        Auditor<?php echo form_error('excelRekomendasiAuditor') ?></label>
                    <input type="text" class="form-control" name="excelRekomendasiAuditor" id="excelRekomendasiAuditor"
                        placeholder="sheet-kolom-baris" value="<?php echo $excelRekomendasiAuditor; ?>" />
                </div>

                <div class="form-group">
                    <label for="varchar">Excel Temuan Auditor<?php echo form_error('excelTemuanAuditor') ?></label>
                    <input type="text" class="form-control" name="excelTemuanAuditor" id="excelTemuanAuditor"
                        placeholder="sheet-kolom-baris" value="<?php echo $excelTemuanAuditor; ?>" />
                </div>

                <div class="form-group">
                    <label for="varchar">Excel Evaluasi Diri
                        Auditor<?php echo form_error('excelEvaluasiDiriAuditor') ?></label>
                    <input type="text" class="form-control" name="excelEvaluasiDiriAuditor" id="excelRekomendasiAuditor"
                        placeholder="sheet-kolom-baris" value="<?php echo $excelEvaluasiDiriAuditor; ?>" />
                </div>

                <div class="form-group">
                    <label for="varchar">Excel Identifikasi Risiko
                        Auditor<?php echo form_error('excelIdentifikasiRisikoAuditor') ?></label>
                    <input type="text" class="form-control" name="excelIdentifikasiRisikoAuditor"
                        id="excelIdentifikasiRisikoAuditor" placeholder="sheet-kolom-baris"
                        value="<?php echo $excelIdentifikasiRisikoAuditor; ?>" />
                </div>

                <input type="hidden" name="ID" value="<?php echo $ID; ?>" />
                <input type="hidden" name="idTahunPelaksanaan" value="<?php echo $idTahunPelaksanaan; ?>" />
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('PenetapanAdmin') ?>" class="btn btn-default">Cancel</a>
            </form>