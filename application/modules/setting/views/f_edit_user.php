<form id="form_user" autocomplete="off">
    <?php $head = $list->row(); ?>
    <table class="customers_ts">
        <tr>
            <td>Kode Pegawai</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->kode_pegawai ?>" type="text" name="kode_pegawai" id="kode_pegawai" readonly style="width : 150px; max-width: 350px;">
            </td>
        </tr>
        <tr>
            <td>Username</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->username ?>" type="text" name="username" id="username" style="width : 150px; max-width: 350px;" maxlength="50">
            </td>
        </tr>
        <tr>
            <td>Password</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->password ?>" type="hidden" readonly name="password_lama" id="password_lma" style="width : 150px; max-width : 250px;" maxlength="50">
                <input placeholder="******" type="password" name="password" id="password" style="width : 150px; max-width : 250px;" maxlength="50">
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
        username = $("#username").val();
        // password = $("#password").val();
        if(username==""){
            error_msg("Username Tidak Boleh Kosong");
            $("#username").focus()
            return false;
        }

        // if(password==""){
        //     error_msg("Password Tidak Boleh Kosong");
        //     $("#password").focus()
        //     return false;
        // }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('setting/user/update_data') ?>",
            type    : "POST",
            data    : $("#form_user").serialize(),
            dataType: "json",
            success : function(data)
            {
                succes_msg("Data Berhasil Diupdate");
                $("#modal_master").modal("hide");
                table.ajax.reload();
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