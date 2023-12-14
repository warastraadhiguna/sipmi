<section class="content-header">
      <h1>
        <?php echo $namaSistem;?> 
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Panduan Admin</a></li>
        <li class="active"><?php echo $button ?> Panduan Admin</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">        
        <div class="box-body">
		
			<!-- Form input atau edit panduanadmin-->
			<legend><?php echo $button ?> Panduan Admin</legend>	
			<form action="<?php echo $action; ?>" method="post">
				<div class="form-group">
					<label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
					<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
				</div>
				<input type="hidden" name="ID" value="<?php echo $ID; ?>" /> 
        <input type="hidden" name="idInfo" value="<?php echo $idInfo; ?>" />          
				<button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
				<a href="<?php echo site_url('PanduanAdmin') ?>" class="btn btn-default">Cancel</a>
			</form>