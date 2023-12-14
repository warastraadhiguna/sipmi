<section class="content-header">
      <h1>
        <?php echo $namaSistem;?> 
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">TahunPelaksanaan</a></li>
        <li class="active"><?php echo $button ?> TahunPelaksanaan</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">        
        <div class="box-body">
		
			<!-- Form input atau edit TahunPelaksanaan-->
			<legend><?php echo $button ?> Tahun Pelaksanaan</legend>	
			<form action="<?php echo $action; ?>" method="post">	
				<div class="form-group">
					<label for="varchar">Tahun <?php echo form_error('tahun') ?></label>
					
					<input type="text" class="form-control" name="tahun" id="tahun" placeholder="Tahun" value="<?php echo $tahun; ?>" />
				</div>					
				<div class="form-group">
					<label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
					
					<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
				</div>

		        <div class="form-group">
		          <label for="enum">Status <?php echo form_error('isActive') ?></label>
		          <select name="isActive" id ="isActive" class="form-control select2" style="width: 100%;"  onchange="checkLevel()">    
		            <?php
		              if($isActive == 'Aktif'){
		            ?>
		              <option value="Aktif" selected>Aktif</option>
		              <option value="Tidak Aktif">Tidak Aktif</option>
		            <?php
		              }
		              elseif($isActive == 'Tidak Aktif'){
		            ?>
		              <option value="Aktif">Aktif</option>
		              <option value="Tidak Aktif" selected>Tidak Aktif</option>
		             <?php
		              }
		            ?>
		          </select>            
		        </div>   				
				<input type="hidden" name="ID" value="<?php echo $ID; ?>" /> 
				<button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
				<a href="<?php echo site_url('TahunPelaksanaan') ?>" class="btn btn-default">Cancel</a>
			</form>