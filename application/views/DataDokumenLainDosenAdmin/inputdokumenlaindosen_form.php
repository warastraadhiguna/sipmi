<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Dokumen Lain</a></li>
        <li class="active"><?php echo $button ?> Dokumen Lain</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">

            <!-- Form input atau edit InputDokumenLain-->
            <legend><?php echo $button ?> Dokumen Dosen</legend>
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
                <input type="hidden" name="ID" value="<?php echo $ID; ?>" />
                <input type="hidden" name="idJenisDokumenLain" value="<?php echo $idJenisDokumenLain; ?>" />
                <input type="hidden" name="idTahunPelaksanaan" value="<?php echo $idTahunPelaksanaan; ?>" />
                <input type="hidden" name="idUserDosen" value="<?php echo $idUserDosen; ?>" />
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('InputDokumenLainDosen') ?>" class="btn btn-default">Cancel</a>
            </form>