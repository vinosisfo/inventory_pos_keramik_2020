<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <table class="customers_ts">
            <tr>
                <td>Nama Ongkir</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama_ongkir" id="nama_ongkir" style="width : 200px; max-width: 200px;" maxlength="100">
                </td>
            </tr>
            <tr>
                <td>Jumlah Min Order</td>
                <td>:</td>
                <td>
                    <input type="text" name="min_order" id="min_order" style="width : 200px; max-width : 250px;" maxlength="4" onkeypress="return hanyaAngka(event)">
                </td>
            </tr>
            <tr>
                <td>Harga Ongkir</td>
                <td>:</td>
                <td>
                <input type="text" name="harga_ongkir" id="harga_ongkir" style="width : 70px; max-width : 250px; text-align : right;" maxlength="15" onkeyup="num_only(this);">
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
            url     : "<?php echo base_url('ongkir/c_ongkir/simpan_data') ?>",
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