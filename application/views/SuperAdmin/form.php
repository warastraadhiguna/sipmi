<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Menu</a></li>
        <li class="active"><?php echo $button ?> Menu</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            <!-- Form input atau edit Menu -->
            <h2 style="margin-top:0px">Menu <?php echo $button ?></h2>
            <form action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="varchar">ID <?php echo form_error('ID') ?></label>
                    <input type="text" class="form-control" name="ID" id="ID" placeholder="ID"
                        value="<?php echo $ID; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Nama <?php echo form_error('nama') ?></label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama"
                        value="<?php echo $nama; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Link <?php echo form_error('link') ?></label>
                    <input type="text" class="form-control" name="link" id="link" placeholder="Link"
                        value="<?php echo $link; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Icon <?php echo form_error('icon') ?></label>
                    <input type="text" class="form-control" name="icon" id="icon" placeholder="Icon"
                        value="<?php echo $icon; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Main <?php echo form_error('main') ?></label>
                    <input type="text" class="form-control" name="main" id="main" placeholder="Main"
                        value="<?php echo $main; ?>" />
                </div>
                <div class="form-group">
                    <label for="enum">Level <?php echo form_error('level') ?></label>
                    <select name="level" id="level" class="form-control select2" style="width: 100%;">
                        <?php
                            if ($level == 'admin') {
                                ?>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin" selected>Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } elseif ($level == 'superadmin') {
                                ?>
                        <option value="superadmin" selected>Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        elseif ($level == 'prodi') {
                        ?>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="prodi" selected>Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } elseif ($level == 'dosen') {
                                ?>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen" selected>Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } elseif ($level == 'pimpinan') {
                                ?>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan" selected>Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } else {
                                ?>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor" selected>Auditor</option>
                        <?php
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="enum">Status <?php echo form_error('isActive') ?></label>
                    <select name="isActive" class="form-control select2" style="width: 100%;">
                        <?php
                            if ($isActive == 1) {
                                ?>
                        <option value="1" selected>Aktif</option>
                        <option value="0">Tidak</option>
                        <?php
                            } elseif ($isActive == 0) {
                                ?>
                        <option value="1">Aktif</option>
                        <option value="0" selected>Tidak</option>
                        <?php
                            } else {
                                ?>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak</option>
                        <?php
                            }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('SuperAdmin/menu') ?>" class="btn btn-default">Cancel</a>
            </form>