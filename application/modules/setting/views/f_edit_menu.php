<form id="form_data_input" autocomplete="off">
    <?php $head = $list->row(); ?>
    <table class="customers_ts">
        <tr>
            <td>JENIS</td>
            <td>:</td>
            <td>
                <input type="hidden" name="id_menu" class="id_menu" value="<?php echo $head->id_menu ?>" readonly>
                <select name="jenis_menu" id="jenis_menu" style="width : 150px; max-width: 350px;">
                    <option value="<?php echo $head->JENIS ?>"><?php echo $head->JENIS ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Nama Menu</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->nama_menu ?>" type="text" name="nama_menu" id="nama_menu" style="width : 150px; max-width: 350px;" maxlength="300">
            </td>
        </tr>
        <tr>
            <td>Aktif</td>
            <td>:</td>
            <td>
                <select name="aktif" id="aktif" style="width : 150px; max-width: 350px;">
                    <?php if($head->Aktif==1){ ?>
                        <option value="1">Ya</option>
                        <option value="0">Tdk</option>
                    <?php } else { ?>
                        <option value="0">Tdk</option>
                        <option value="1">Ya</option>
                    <?php } ?>
                </select>
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
        nama_menu = $("#nama_menu").val();
        if(nama_menu==""){
            error_msg("Nama Menu Tidak Boleh Kosong");
            $("#nama_menu").focus()
            return false;
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('setting/menu/update_data') ?>",
            type    : "POST",
            data    : $("#form_data_input").serialize(),
            dataType: "json",
            success : function(data)
            {
                succes_msg("Data Berhasil Disimpan");
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