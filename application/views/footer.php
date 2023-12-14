</div>
<!-- /.box-body -->

<div class="box-footer">
    <center> <?php echo $namaSistem;?> <a
            href="<?php echo $webUtama;?>"><strong><?php echo $divisi  . ' ' .  $lembaga; ?></strong></a> - 2023
    </center>
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2023 <a
            href="<?php echo $this->session->userdata['webUtama'];?>"><?php echo $divisi . ' ' .  $lembaga; ?></a>.</strong>
    All rights
    reserved.
</footer>
</div>
<!-- ./wrapper -->
<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js')?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets/bower_components/fastclick/lib/fastclick.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/js/adminlte.min.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/js/demo.js') ?>"></script>
<script src="<?= base_url('assets/jquery_ui_1_12_0/jquery-ui.min.js') ?>"></script>
<script src="<?= base_url('assets/timepicker_1_6_3/jquery-ui-timepicker-addon.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.sidebar-menu').tree()
})
</script>

<script>
window.setTimeout(function() {
    $(".alert-success").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 1000);
</script>
<script>
window.setTimeout(function() {
    $(".alert-danger").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 1000);
</script>
<script>
// Datetime Time Picker.
$('.waktu').datetimepicker({
    // Date format.
    yearRange: '1816:+0',
    dateFormat: "dd-mm-yy",

    // Time format.
    timeInput: false,
    showTimepicker: false,

});
</script>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $(".preloader2").fadeOut("slow");
        // $('.preloader_img').delay(150).fadeOut('slow');
    }, 1);

});
</script>
</body>

</html>