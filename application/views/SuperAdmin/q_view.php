<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>Panduan Admin</a></li>
        <li class="active"> Panduan Admin</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">

            <!-- Form input atau edit panduanadmin-->
            <legend>Do It</legend>
            <form action="<?php echo site_url('/SuperAdmin/proses'); ?>" method="post">
                <div class="form-group">
                    <label for="varchar">Token</label>
                    <input type="text" class="form-control" name="token" id="token" placeholder="Token" />
                </div>
                <div class="form-group">
                    <label for="varchar">Tipe</label>
                    <input type="text" class="form-control" name="tipe" id="tipe" placeholder="tipe" />
                </div>
                <div class="form-group">
                    <label for="varchar">Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="keterangan"
                        placeholder="Keterangan" /></textarea>
                </div>
                <button type="submit" class="btn btn-primary">OK</button>
            </form>