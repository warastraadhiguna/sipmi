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
            <h2 style="margin-top:0px">Users <?php echo $button ?></h2>
            <form action="<?php echo $action; ?>" method="post">
                <?= isset($ID) ? form_hidden('ID', $ID) : '' ?>
                <div class="form-group">
                    <label for="varchar">Nama <?php echo form_error('nama') ?></label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama User"
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
                    <label for="enum">Level <?php echo form_error('level') ?></label>
                    <select name="level" id="level" class="form-control select2" style="width: 100%;"
                        onchange="checkLevel()">
                        <?php
                            if ($level == 'admin') {
                                ?>
                        <option value="admin" selected>Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } elseif ($level == 'prodi') {
                                ?>
                        <option value="admin">Admin</option>
                        <option value="prodi" selected>Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } elseif ($level == 'dosen') {
                                ?>
                        <option value="admin">Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen" selected>Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } elseif ($level == 'pimpinan') {
                                ?>
                        <option value="admin">Admin</option>
                        <option value="prodi">Prodi dan Unit</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan" selected>Pimpinan</option>
                        <option value="auditor">Auditor</option>
                        <?php
                            } else {
                                ?>
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

                <div class="form-group" id='prodi_unit_view'>
                    <label for="int">Program Studi dan Unit <?php echo form_error('idProdi') ?></label>
                    <?php
                        echo combobox('idProdi', 'tprodi_unit', 'nama', 'ID', $idProdi, '', 'idFakultas');
                    ?>
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
                <a href="<?php echo site_url('Users') ?>" class="btn btn-default">Cancel</a>
            </form>

            <script type="text/javascript">
            window.onload = function() {
                checkLevel();
            };

            function checkLevel() {
                var e = document.getElementById("level");
                var value = e.options[e.selectedIndex].text;

                var x = document.getElementById("prodi_unit_view");
                if (value === "Prodi dan Unit" || value === "Dosen") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
            </script>