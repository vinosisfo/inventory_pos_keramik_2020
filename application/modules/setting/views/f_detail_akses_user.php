<form id="form_data_akses" autocomplete="off">
    <?php $head = $head->row(); ?>
    <table class="customers_ts">
        <tr>
            <td>Nama Akses</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->id_akses ?>" type="hidden" name="id_akses" id="id_akses" style="width : 150px; max-width: 350px;" readonly>
                <input readonly value="<?php echo strtoupper($head->nama_akses) ?>" type="text" name="nama_akses" id="nama_akses" style="width : 150px; max-width: 350px;" maxlength="200">
            </td>
        </tr>
    </table>
    <br>
    <div class="table-responsive">
        <table class="customers" class="table-responsive">
            <tr>
                <th>No</th>
                <th></th>
                <th>Nama Menu</th>
                <th>Group Menu</th>
            </tr>
            <?php
            $no =0;
            foreach ($list->result() as $data) {
                $no++;
                $cek_set     = (strlen($data->id_akses) > 0 ) ? "checked" : "";
                $id_menu_set = (strlen($data->id_akses) > 0 ) ? ($data->id_menu) : "";
                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td>
                        <input type="checkbox" name="pilih_menu[]" id="pilih_menu_<?php echo $no ?>" <?php echo $cek_set ?> onclick="set_value('<?php echo $no ?>')"/>
                        <label for="pilih_menu_<?php echo $no ?>"></label>
                        <input readonly type="hidden" name="id_menu_set[]" id="id_menu_set_<?php echo $no ?>" value="<?php echo $id_menu_set ?>" readonly>
                        <input readonly type="hidden" name="id_menu[]" id="id_menu_<?php echo $no ?>" value="<?php echo $data->id_menu ?>" readonly>

                    </td>
                    <td><?php echo $data->nama_menu ?></td>
                    <td><?php echo $data->head_menu ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <table class="customers_ts">
        <tr>
            <td colspan="2" style="text-align: right;">
                <button type="button" id="btn_simpan" class="btn btn-primary btn-sm" onclick="simpan_data_akses(this)">Simpan Data</button>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </td>
        </tr>
    </table>
</form>

<script>
    function set_value(urut){
        if($("#pilih_menu_"+urut).is(':checked')){
            $("#id_menu_set_"+urut).val($("#id_menu_"+urut).val());
        } else {
            $("#id_menu_set_"+urut).val("");
        }
    }

    
    function simpan_data_akses(){

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('setting/akses_user/simpan_akses_user') ?>",
            type    : "POST",
            data    : $("#form_data_akses").serialize(),
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