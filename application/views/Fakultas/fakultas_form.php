<section class="content-header">
      <h1>
        <?php echo $namaSistem;?> 
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Fakultas</a></li>
        <li class="active"><?php echo $button ?> Fakultas</li>
      </ol>
    </section>
    <!-- Main content -->

      <!-- Default box -->
      <div class="box">        
        <div class="box-body">
		
			<!-- Form input atau edit Fakultas -->
			<h2 style="margin-top:0px">Fakultas <?php echo $button ?></h2>
			<form action="<?php echo $action; ?>" method="post">
				<?= isset($ID) ? form_hidden('ID', $ID) : '' ?>
				<div class="form-group">
					<label for="varchar">Nama <?php echo form_error('nama') ?></label>
					<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Fakultas" value="<?php echo $nama; ?>" />
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
			
				<button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
				<a href="<?php echo site_url('Fakultas') ?>" class="btn btn-default">Cancel</a>
			</form>
    