<script src="<?php echo base_url('assets/adminbsb/plugins/jquery/jquery.min.js')?>"></script>

<!-- Bootstrap Core Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap/js/bootstrap.js')?>"></script>

<!-- Select Plugin Js -->
<script src="<?php //echo base_url('assets/adminbsb/plugins/bootstrap-select/js/bootstrap-select.js')?>"></script>

<!-- Slimscroll Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-slimscroll/jquery.slimscroll.js')?>"></script>

<!-- Bootstrap Colorpicker Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')?>"></script>

<!-- Dropzone Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/dropzone/dropzone.js')?>"></script>

<!-- Input Mask Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-inputmask/jquery.inputmask.bundle.js')?>"></script>

<!-- Multi Select Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/multi-select/js/jquery.multi-select.js')?>"></script>

<!-- Jquery Spinner Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-spinner/js/jquery.spinner.js')?>"></script>

<!-- Bootstrap Tags Input Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')?>"></script>

<!-- noUISlider Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/nouislider/nouislider.js')?>"></script>

<!-- Waves Effect Plugin Js -->
<script src="<?php echo base_url('assets/adminbsb/plugins/node-waves/waves.js')?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/adminbsb/js/admin.js')?>"></script>

<!-- Demo Js -->
<!-- <script src="<?php //echo base_url('assets/adminbsb/js/demo.js')?>"></script> -->

<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/jszip.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/pdfmake.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/vfs_fonts.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/jquery-datatable/extensions/export/buttons.print.min.js')?>"></script>
<script src="<?php echo base_url('assets/izitoast/dist/js/iziToast.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/select2/dist/js/select2.full.min.js')?>"></script>
<!-- datepicker -->
<script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap-datepicker/bootstrap-datepicker.js')?>"></script>

<script type="text/javascript">
	function hanyaAngka(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
		return true;
	}

	function error_msg(pesan){
		iziToast.error({
			title   : 'Error !!',
			message : pesan,
			position: 'topCenter'
		});
	}

	function hapus_msg(pesan){
		iziToast.error({
			title   : 'Berhasil !!',
			message : pesan,
			position: 'topCenter'
		});
	}

	function succes_msg(pesan)
	{
		iziToast.success({
			title   : 'Berhasil!',
			message : pesan,
			position: 'topCenter'
		});
	}

	function info_msg(pesan)
	{
		iziToast.info({
			title   : 'info!',
			message : pesan,
			position: 'topCenter'
		});
	}
	

	function foots()
	{
		$("#foot").html("@MJK &copy; 2020")
		$("#copy").html("@MJK &copy; 2020")
	}
	foots();

	function format_number(number, prefix, thousand_separator, decimal_separator)
    {
		var thousand_separator = thousand_separator || ',',
		decimal_separator = decimal_separator || '.',
		regex   = new RegExp('[^' + decimal_separator + '\\d]', 'g'),
		number_string = number.replace(regex, '').toString(),
		split   = number_string.split(decimal_separator),
		rest    = split[0].length % 3,
		result    = split[0].substr(0, rest),
		thousands = split[0].substr(rest).match(/\d{3}/g);
		
		if (thousands) {
		separator = rest ? thousand_separator : '';
		result += separator + thousands.join(thousand_separator);
		}
		result = split[1] != undefined ? result + decimal_separator + split[1] : result;
		return prefix == undefined ? result : (result ?  result  + prefix: '');
    };

    function hanya_angka(data,nomor)
    {
		var targeth = event.target || event.srcElement;
		var idh = targeth.id;
		var idsh = "#"+idh;
		var get_uruth = nomor;

		var isi = data.value;
		var isi2 = $(this);
		let hasil = format_number(isi);
		$(data).val(hasil);
		console.log(hasil)
	}

	function cek_huruf(baris)
	{
		isi = $("#nama_"+baris).val();
		var letters = /^[a-zA-Z . , - ]+$/;
		if(isi.match(letters))
		{
			return true;
		}
		else
		{
			pesan = 'Nama Hanya Bisa Diisi Huruf';
			error_msg(pesan);
			$("#nama_"+baris).val("");
		}
	}

	$(function () {
		$(".select2").select2({
			allowClear : true,
			placeholder: '--Pilih--',
			required   : true
		});

		$(".tanggal").datepicker({
			autoclose     : true,
			format        : 'yyyy-mm-dd',
			changeMonth   : true,
			changeYear    : true,
			orientation   : "top",
			endDate       : '+0d',
			autoclose     : true,
			todayHighlight: true,
			toggleActive  : true,
			
		});

		$('#example').DataTable({
			"paging"      : true,
			"lengthChange": true,
			"searching"   : true,
			"ordering"    : true,
			"info"        : true,
			"autoWidth"   : true,
		});
	});

</script>