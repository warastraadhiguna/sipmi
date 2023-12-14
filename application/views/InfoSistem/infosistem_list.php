<section class="content-header">
      <h1>
        <?php echo $namaSistem;?> 
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Info Sistem</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">        
        <div class="box-body">
			<legend>Panduan Pengisian</legend>	   
			<div class="row" style="margin-bottom: 10px">
				<div class="col-md-4">
				</div>
				<div class="col-md-4 text-center">
					<?= showFlashMessage() ?>
				</div>
			</div>
			<form action="<?php echo $action; ?>" method="post">
				<div class="form-group">
					<label for="varchar">Nama Sistem</label>
					<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Sistem" value="<?php echo $nama; ?>" />
				</div>
				<div class="form-group">
					<label for="varchar">Divisi</label>
					<input type="text" class="form-control" name="divisi" id="divisi" placeholder="Divisi" value="<?php echo $divisi; ?>" />
				</div>
				<div class="form-group">
					<label for="varchar">Lembaga</label>
					<input type="text" class="form-control" name="lembaga" id="lembaga" placeholder="Lembaga" value="<?php echo $lembaga; ?>" />
				</div>
				<div class="form-group">
					<label for="varchar">Web Utama</label>
					<input type="text" class="form-control" name="webUtama" id="webUtama" placeholder="Web Utama" value="<?php echo $webUtama; ?>" />
				</div>					
				<button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
			</form>  
		</br>
		</div>
	
	<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>