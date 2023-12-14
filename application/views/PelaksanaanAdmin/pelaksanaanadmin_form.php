<section class="content-header">
      <h1>
        <?php echo $namaSistem;?> 
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">PelaksanaanAdmin</a></li>
        <li class="active"><?php echo $button ?> PelaksanaanAdmin</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">        
        <div class="box-body">
		
			<!-- Form input atau edit PelaksanaanAdmin-->
			<legend><?php echo $button ?> Pelaksanaan Admin</legend>	
			<form action="<?php echo $action; ?>" method="post">
				<div class="form-group">
					<label for="int">Nama Standar <?php echo form_error('idKebijakan') ?></label>
					<?php 
						echo combobox('idKebijakan','tkebijakan','kode','ID', $idKebijakan, ' idTahunPelaksanaan=' . $idTahunPelaksanaan);
					?>            
				</div>				
				<div class="form-group">
					<label for="varchar">Nama Dokumen Bukti<?php echo form_error('nama') ?></label>
					
					<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Pelaksanaan" value="<?php echo $nama; ?>" />
				</div>
				<input type="hidden" name="ID" value="<?php echo $ID; ?>" /> 
        		<input type="hidden" name="idTahunPelaksanaan" value="<?php echo $idTahunPelaksanaan; ?>"/>
				<button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
				<a href="<?php echo site_url('PelaksanaanAdmin') ?>" class="btn btn-default">Cancel</a>
			</form>