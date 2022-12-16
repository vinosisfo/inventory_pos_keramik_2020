<form id="form_data_input" autocomplete="off">
    <?php $head = $list->row(); ?>
    <div class="table-responsive">
        <table class="customers_ts">
            <tr>
                <td>Nama Supplier</td>
                <td>:</td>
                <td>
                    <input type="hidden" readonly name="id_supplier" id="id_supplier" value="<?php echo $head->id_supplier ?>">
                    <input value="<?php echo $head->Nama_supplier ?>" type="text" name="nama_supplier" id="nama_supplier" style="width : 200px; max-width: 200px;" maxlength="100">
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <textarea name="alamat" id="alamat" maxlength="500" style="width: 200px; max-width : 250px; resize : none;"><?php echo $head->Alamat ?></textarea>
                </td>
            </tr>
            <tr>
                <td>No Telp</td>
                <td>:</td>
                <td>
                    <input value="<?php echo $head->Notlp ?>" type="text" name="no_tlp" id="no_tlp" style="width : 200px; max-width : 250px;" maxlength="13" onkeypress="return hanyaAngka(event)">
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>
                    <input value="<?php echo $head->Email ?>" type="text" name="email" id="email" style="width : 200px; max-width : 250px;" maxlength="100" >
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
    </div>
</form>

<script>
    function simpan_data(){
        nama_supplier = $("#nama_supplier").val();
        alamat        = $("#alamat").val();
        if(nama_supplier==""){
            error_msg("Nama Supplier Tidak Boleh Kosong");
            $("#nama_supplier").focus()
            return false;
        }

        if(alamat==""){
            error_msg("Alamat Tidak Boleh Kosong");
            $("#alamat").focus()
            return false;
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('supplier/c_supplier/update_data') ?>",
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