<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <table class="customers_ts">
            <tr>
                <td>Nama Customer</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama_customer" id="nama_customer" style="width : 200px; max-width: 200px;" maxlength="100">
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <textarea name="alamat" id="alamat" maxlength="500" style="width: 200px; max-width : 250px; resize : none;"></textarea>
                </td>
            </tr>
            <tr>
                <td>No Telp</td>
                <td>:</td>
                <td>
                    <input type="text" name="no_tlp" id="no_tlp" style="width : 200px; max-width : 250px;" maxlength="13" onkeypress="return hanyaAngka(event)">
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>
                    <input type="text" name="email" id="email" style="width : 200px; max-width : 250px;" maxlength="100" >
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
    </div>
</form>

<script>
    function simpan_data(){
        nama_customer = $("#nama_customer").val();
        alamat        = $("#alamat").val();
        if(nama_customer==""){
            error_msg("Nama Customer Tidak Boleh Kosong");
            $("#nama_customer").focus()
            return false;
        }

        if(alamat==""){
            error_msg("Alamat Tidak Boleh Kosong");
            $("#alamat").focus()
            return false;
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('customer/c_customer/simpan_data') ?>",
            type    : "POST",
            data    : $("#form_data_input").serialize(),
            dataType: "json",
            success : function(data)
            {
                if(data.pesan=="ok"){
                    succes_msg("Data Berhasil Disimpan");
                    $("#modal_master").modal("hide");
                    table.ajax.reload();
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