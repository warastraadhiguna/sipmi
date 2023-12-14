<section class="content-header">
      <h1>
        <?php echo $namaSistem;?> 
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Program Studi</a></li>
        <li class="active"><?php echo $button ?> Program Studi</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">        
        <div class="box-body">
		
			<!-- Form input atau edit Prodi-->
			<legend><?php echo $button ?> Program Studi</legend>	
			<form action="<?php echo $action; ?>" method="post">
				<div class="form-group">
					<label for="int">Fakultas <?php echo form_error('idFakultas') ?></label>
					<?php 
						echo combobox('idFakultas','tfakultas','nama','ID', $idFakultas, " ID <> 1 ");
					?>            
				</div>				
				<div class="form-group">
					<label for="varchar">Nama Prodi <?php echo form_error('nama') ?></label>
					<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Prodi" value="<?php echo $nama; ?>" />
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
				<a href="<?php echo site_url('prodi') ?>" class="btn btn-default">Cancel</a>
			</form>
			<!--// Form Prodi-->