<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>.:: <?php echo $namaSistem  . ' ' .  $divisi  . ' ' .  $lembaga; ?> ::.</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
        href="<?php echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/Ionicons/css/ionicons.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/AdminLTE.min.css') ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/skins/_all-skins.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" href="<?=base_url()?>assets/images/logo.png" type="image/ico">
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="<?php echo base_url('index.php/Dashboard') ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b><?php echo $divisi;?></b> <?php echo $lembaga;?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b><?php echo $namaSistem;?></b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url('assets/img/user2-160x160.jpg') ?>" class="user-image"
                                    alt="User Image">
                                <span class="hidden-xs">
                                    <?php
          // Menampilkan nama username
          echo $username;
    ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url('assets/img/user2-160x160.jpg') ?>" class="img-circle"
                                        alt="User Image">
                                    <p>
                                        <?php
        // Menampilkan nama username
      echo $username;
    ?> -
                                        <?php
    // Menampilkan web administrator
    echo $wa;
    ?>
                                        <small>
                                            <?php
            // Menampilkan nama universitas
            //echo $univ;
    ?>
                                            LPM UPGRIS
                                        </small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            <a href="#"><?php
                                          echo ucfirst($level)  . ' ' . $this->session->userdata['prodi']; ?></a>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?php if ($level == "admin") { ?>
                                        <a href="users" class="btn btn-default btn-flat">Profile</a>
                                        <?php } ?>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?= site_url('Login/logout'); ?>" class="btn btn-default btn-flat">Sign
                                            out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- =============================================== -->
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo base_url('assets/img/user2-160x160.jpg') ?>" class="img-circle"
                            alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $username; ?></p>
                        </p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENU</li>
                    <?php
            // Data main menu
      $main_menu = $this->db->get_where('tmenu', array('main' => 0,'isActive' => 1,'level' => $this->session->userdata['level']));
    foreach ($main_menu->result() as $main) {
        // Query untuk mencari data sub menu
        $sub_menu = $this->db->get_where('tmenu', array('main' => $main->ID,'isActive' => 1,'level' => $this->session->userdata['level']));
        // Memeriksa apakah ada sub menu, jika ada sub menu tampilkan
        if ($sub_menu->num_rows() > 0) {
            if ($main->ID > 0) {
                // Main menu yang memiliki sub menu
                echo "<li class='treeview'>" . anchor($main->link, '<i class="' . $main->icon . '"></i>' . $main->nama .
                '<span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>');
                // Menampilkan data sub menu
                echo "<ul class='treeview-menu'>";
                foreach ($sub_menu->result() as $sub) {
                    echo "<li>" . anchor($sub->link, '<i class="' . $sub->icon . '"></i>' . $sub->nama) . "</li>";
                }
                echo"</ul>
               </li>";
            }
        }
        // Jika tidak memiliki sub menu maka tampilkan data main menu
        else {
            if ($main->ID > 0) {
                // Data main menu tanpa sub menu
                echo "<li>" . anchor($main->link, '<i class="' . $main->icon . '"></i>' . $main->nama) . "</li>";
            }
        }
    }
    ?>

                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->