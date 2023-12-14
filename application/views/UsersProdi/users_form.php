<section class="content-header">
    <h1>
        <?php echo $namaSistem;?>
        <small><?php echo $divisi;?> <?php echo $lembaga;?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Users</a></li>
        <li class="active"><?php echo $button ?> Users</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-body">

            <!-- Form input atau edit Users -->
            <h2 style="margin-top:0px">User Dosen <?= $this->session->userdata['prodi']; ?> <?php echo $button ?></h2>
            <form action="<?php echo $action; ?>" method="post">
                <?= isset($ID) ? form_hidden('ID', $ID) : '' ?>
                <div class="form-group">
                    <label for="varchar">Nama <?php echo form_error('nama') ?></label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Dosen"
                        value="<?php echo $nama; ?>" />
                </div>
                <div class="form-group">
                    <label for="varchar">Username <?php echo form_error('username') ?></label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                        value="<?php echo $username; ?>" />
                </div>
                <?php if ($button != 'Update') {?>
                <div class="form-group">
                    <label for="varchar">Password <?php echo form_error('password') ?></label>
                    <input type="text" class="form-control" name="password" id="password" placeholder="Password" />
                </div>
                <?php }?>

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
                <a href="<?php echo site_url('UsersProdi') ?>" class="btn btn-default">Cancel</a>
            </form>