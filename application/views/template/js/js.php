
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery/jquery.min.js')?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap/js/bootstrap.js')?>"></script>

    <!-- Select Plugin Js -->
    <!-- <script src="<?php //echo base_url('assets/adminbsb/plugins/bootstrap-select/js/bootstrap-select.js')?>"></script> -->

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-slimscroll/jquery.slimscroll.js')?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/node-waves/waves.js')?>"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/jquery.dataTables.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/jszip.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/pdfmake.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/vfs_fonts.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/buttons.print.min.js')?>"></script>

     <!-- <script src="<?php //echo base_url('assets/adminbsb/plugins/jquery-datatable/responsive.bootstrap.min.js')?>"></script> -->

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/adminbsb/js/admin.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/js/pages/tables/jquery-datatable.js')?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/adminbsb/js/demo.js')?>"></script>

    <!-- vue js + axios -->
    <script src="<?php echo base_url('assets/adminbsb/js/vue/vue.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/js/axios/axios.min.js')?>"></script>

    <!-- select 2 -->
    <script src="<?php echo base_url('assets/adminbsb/select2/dist/js/select2.full.min.js')?>"></script>

    <!-- datepicker -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap-datepicker/bootstrap-datepicker.js')?>"></script>
    
    <script src="<?php echo base_url('assets/izitoast/dist/js/iziToast.min.js')?>"></script>

    <script>
        function hanyaAngka(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
        }
    </script>

    <script>
      $(function () {
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true,
        });

        $(".datepicker").datepicker({
          autoclose  : true,
          format     : 'yyyy-mm-dd',
          changeMonth: true,
          changeYear : true
        });


        // $(".js-select2").select2({
        // placeholder: "Pick states",
        // theme: "material"
        // });
      });
     $(".select2").select2({
          allowClear:true,
          placeholder: '--Pilih--',
          required : true
        });

    </script>

</body>