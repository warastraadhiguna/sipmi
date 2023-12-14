<!doctype html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Detail Users</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Users Read</h2>	
		 <a href="<?php echo site_url('Users/update/'.$username) ?>" class="btn btn-primary">Update</a>
		 <a href="<?php echo site_url('Users') ?>" class="btn btn-warning">Cancel</a>
		 
        <table class="table table-striped table-bordered">
	    <tr><td>Nama</td><td><?php echo $namauser; ?></td></tr>        	
	    <tr><td>Username</td><td><?php echo $username; ?></td></tr>
	    <tr><td>Password</td><td><?php echo $password; ?></td></tr>
	    <tr><td>Level</td><td><?php echo $level; ?></td></tr>
	    <tr><td>Blokir</td><td><?php echo $blokir; ?></td></tr>
	    <tr><td>Id Sessions</td><td><?php echo $id_sessions; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('Users') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>