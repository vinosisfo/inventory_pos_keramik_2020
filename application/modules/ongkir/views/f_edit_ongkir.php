<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <?php $head = $list->row(); ?>
        <table class="customers_ts">
            <tr>
                <td>Nama Ongkir</td>
                <td>:</td>
                <td>
                    <input value="<?php echo $head->id_ongkir ?>" type="hidden" name="id_ongkir" readonly>
                    <input value="<?php echo $head->nama_ongkir ?>" type="text" name="nama_ongkir" id="nama_ongkir" style="width : 300px; max-width: 300px;" maxlength="100">
                </td>
            </tr>
            <tr>
                <td>Jumlah Min Order</td>
                <td>:</td>
                <td>
                    <input value="<?php echo (int)$head->jumlah_min_order ?>" type="text" name="min_order" id="min_order" style="width : 70px; max-width : 250px; text-align : right;" maxlength="4" onkeypress="return hanyaAngka(event)">
                </td>
            </tr>
            <tr>
                <td>Harga Ongkir</td>
                <td>:</td>
                <td>
                    <input value="<?php echo number_format($head->harga_ongkir) ?>" type="text" name="harga_ongkir" id="harga_ongkir" style="width : 70px; max-width : 250px; text-align : right;" maxlength="15" onkeyup="num_only(this);">
                </td>
            </tr>

            <tr>
                <td>Aktif</td>
                <td>:</td>
                <td>
                    <select name="aktif" id="aktif" style="width : 70px;">
                        <?php if($head->Aktif==1) { ?>
                            <option value="1">Ya</option>
                            <option value="0">Tdk</option>
                        <?php } else { ?>
                            <option value="Ya">Tdk</option>
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
    function num_only(data)
    {
        var isi   = data.value;
        var isi2  = $(this);
        let hasil = format_number(isi);
        $(data).val(hasil);
        console.log(hasil);
    }

    function simpan_data(){
        nama_ongkir  = $("#nama_ongkir").val();
        min_order    = $("#min_order").val();
        harga_ongkir = $("#harga_ongkir").val();
        if(nama_ongkir==""){
            error_msg("Nama Ongkir Tidak Boleh Kosong");
            $("#nama_ongkir").focus()
            return false;
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('ongkir/c_ongkir/update_data') ?>",
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