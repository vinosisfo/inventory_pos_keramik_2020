<form id="form_data_akses" autocomplete="off">
    <?php $head = $head->row(); ?>
    <table class="customers_ts">
        <tr>
            <td>Kode Pegawai</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->kode_pegawai ?>" type="text" name="kode_pegawai" id="kode_pegawai" style="width : 150px; max-width: 350px;" readonly>
            </td>
        </tr>
        <tr>
            <td>Username</td>
            <td>:</td>
            <td><?php echo ($head->username) ?></td>
        </tr>
    </table>
    <br>
    <div class="table-responsive">
        <table class="customers" class="table-responsive">
            <tr>
                <th>No</th>
                <th></th>
                <th>Nama Akses</th>
            </tr>
            <?php
            $no =0;
            foreach ($list->result() as $data) {
                $no++;
                $cek_set     = (strlen($data->kode_pegawai) > 0 ) ? "checked" : "";
                $id_akses_set = (strlen($data->kode_pegawai) > 0 ) ? ($data->id_akses) : "";
                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td>
                        <input type="checkbox" name="pilih_menu[]" id="pilih_menu_<?php echo $no ?>" <?php echo $cek_set ?> onclick="set_value('<?php echo $no ?>')"/>
                        <label for="pilih_menu_<?php echo $no ?>"></label>
                        <input readonly type="hidden" name="id_akses_set[]" id="id_akses_set_<?php echo $no ?>" value="<?php echo $id_akses_set ?>" readonly>
                        <input readonly type="hidden" name="id_akses[]" id="id_akses_<?php echo $no ?>" value="<?php echo $data->id_akses ?>" readonly>

                    </td>
                    <td><?php echo $data->nama_akses ?></td>
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
            $("#id_akses_set_"+urut).val($("#id_akses_"+urut).val());
        } else {
            $("#id_akses_set_"+urut).val("");
        }
    }

    
    function simpan_data_akses(){

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('setting/user/simpan_akses_user') ?>",
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