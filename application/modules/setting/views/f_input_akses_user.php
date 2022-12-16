<form id="form_user" autocomplete="off">
    <table class="customers_ts">
        <tr>
            <td>Nama Akses</td>
            <td>:</td>
            <td>
                <input type="text" name="nama_akses" id="nama_akses" style="width : 150px; max-width: 350px;" maxlength="200">
            </td>
        </tr>
        
        <tr>
            <td colspan="2" style="text-align: right;">
                <button type="button" id="btn_simpan" class="btn btn-primary btn-sm" onclick="simpan_data(this)">Simpan Data</button>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </td>
        </tr>
    </table>
</form>

<script>
    function simpan_data(){
        nama_akses = $("#nama_akses").val();
        if(nama_akses==""){
            error_msg("Nama Akses Tidak Boleh Kosong");
            $("#nama_akses").focus()
            return false;
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('setting/akses_user/simpan_data') ?>",
            type    : "POST",
            data    : $("#form_user").serialize(),
            dataType: "json",
            success : function(data)
            {
                if(data.pesan=="ok"){
                    succes_msg("Data Berhasil Disimpan");
                    $("#modal_master").modal("hide");
                    table.ajax.reload();
                } else{
                    error_msg("Akses User Sudah Ada");
                }
            },
            error : function(data)
            {
                error_msg("Error Hubungi Developer");
                $("#btn_simpan").prop("disabled",false);
                return false;
            }
        })
    }
</script>