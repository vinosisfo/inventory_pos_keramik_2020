<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <table class="customers_ts">
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>
                    <input type="text" name="tanggal" id="tanggal" class="tanggal" value="<?php echo date("Y-m-d") ?>" readonly style="width: 80px;">
                </td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>:</td>
                <td style="width : 300px;">
                    <select style="width: 99%;" name="supplier" id="supplier" class="select2">
                        <option value="">PILIH</option>
                        <?php foreach ($supplier->result() as $spl) { ?>
                            <option value="<?php echo $spl->id_supplier ?>"><?php echo $spl->Nama_supplier ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <br>
        <div><button type="button" class="btn btn-danger btn-xs" onclick="add_row(this)">+ Baris</button></div>
        
        <table class="customers" style="width: 800px;">
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 20px;"></th>
                <th style="width: 130px;">Kode Barang</th>
                <th style="width: 300px;">Nama Barang</th>
                <th style="width: 100px;">Qty</th>
                <th style="width: 100px;">Harga</th>
                <th style="width: 120px;">Sub Total</th>
            </tr>
            <tbody id="row_body">
                
            </tbody>
            <tfoot>
                <td colspan="4" style="text-align: right;">Total</td>
                <td style="text-align: right;"><span id="total_qty"></span></td>
                <td style="text-align: right;"><span id="total_harga"></span></td>
                <td><input type="text" readonly style="border: none; text-align : right;" id="total_sub_total"></td>
            </tfoot>
        </table>
        <br>
        <table>
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
    function add_row()
    {
        i = $("#row_body tr").length+1;
        row = '<tr>'+
                    '<td>'+i+'</td>'+
                    '<td>'+
                        '<button type="button" class="btn btn-danger btn-xs hapus_row" id="btn_hapus_'+i+'">Hapus</button>'+
                    '</td>'+
                    '<td>'+
                        '<select class="select2" name="kode_barang[]" id="kode_barang_'+i+'" style="width: 99%;" onchange="get_barang(this,'+i+',\''+'kode'+'\')">'+
                            '<option value="">Pilih</option>'+
                            <?php foreach ($barang->result() as $brg) { ?>
                                '<option value="<?php echo $brg->Kodebarang ?>"><?php echo $brg->Kodebarang ?></option>'+
                            <?php } ?>
                        '</select>'+  
                    '</td>'+

                    '<td>'+
                        '<select class="select2" name="nama_barang[]" id="nama_barang_'+i+'" style="width: 99%;" onchange="get_barang(this,'+i+',\''+'nama'+'\')">'+
                            '<option value="">Pilih</option>'+
                            <?php foreach ($barang->result() as $brg) { ?>
                                '<option value="<?php echo $brg->Kodebarang ?>"><?php echo $brg->NamaBarang ?></option>'+
                            <?php } ?>
                        '</select>'+  
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="Qty[]" id="Qty_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')">'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="harga_barang[]" id="harga_barang_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')">'+
                    '</td>'+
                    '<td style="text-align: right;">'+
                        '<span id="sub_total_'+i+'"></span>'+
                    '</td>'+
                '</tr>';
        $("#row_body").append(row);

        $(".select2").select2({
            allowClear       : true,
            placeholder      : 'Pilih',
            required         : true,
            dropdownAutoWidth: true,
        });
    }
    add_row();
    $("#row_body").on('click', '.hapus_row', function(){
        $(this).parent().parent().remove();
        console.log(this.id)
        set_sub_total('1');
    });

    function get_barang(data,urut,jenis)
    {
        kode_barang = $("#kode_barang_"+urut).val()
        nama_barang = $("#nama_barang_"+urut).val()

        kode_barang_set = (jenis=="kode") ? (kode_barang) : (nama_barang);
        $.post('<?php echo base_url('barang_masuk/c_barang_masuk/get_detail_barang') ?>',{kode_barang : kode_barang_set, jenis : jenis},function(data){
            if(jenis=="kode"){
                $("#nama_barang_"+urut).html(data.barang)
            } else {
                $("#kode_barang_"+urut).html(data.barang)
            }
            get_duplikat(urut);
        },"json")
    }

    function get_duplikat(urut)
    {
        $.post('<?php echo base_url('barang_masuk/c_barang_masuk/get_duplikat')?>',$("#form_data_input").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Barang, Barang Tidak Boleh Sama Dalam Satu Transaksi");
                opsi_nama = '<option value="">Pilih</option>'+
                            <?php foreach ($barang->result() as $brg) { ?>
                                '<option value="<?php echo $brg->Kodebarang ?>"><?php echo $brg->NamaBarang ?></option>'+
                            <?php } ?>
                            '';
                opsi_kode = '<option value="">Pilih</option>'+
                            <?php foreach ($barang->result() as $brg) { ?>
                                '<option value="<?php echo $brg->Kodebarang ?>"><?php echo $brg->NamaBarang ?></option>'+
                            <?php } ?>
                            '';
                $("#kode_barang_"+urut).html(opsi_kode)
                $("#nama_barang_"+urut).html(opsi_nama)
                return false;
            }
        },"json");
    }

    function set_sub_total(urut)
    {
        harga = $("#harga_barang_"+urut).val();
        qty   = $("#Qty_"+urut).val();
        $.ajax({
            url     : "<?php echo base_url('barang_masuk/c_barang_masuk/set_sub_total')?>",
            type    : "POST",
            data    : $("#form_data_input").serialize()+"&harga_baris="+harga+"&qty_baris="+qty,
            dataType: "json",
            success : function(data)
            {
                $("#sub_total_"+urut).html(data.sub_total);
                $("#total_qty").html(data.total_qty);
                $("#total_harga").html(data.total_harga);
                $("#total_sub_total").val(data.total_sub_total);
            }
        })
    }

    function num_only(data)
    {
        var isi = data.value;
        var isi2 = $(this);
        let hasil = format_number(isi);
        $(data).val(hasil);
        console.log(hasil);
    }

    $(function () {
        $(".select2").select2({
            allowClear       : true,
            placeholder      : 'Pilih',
            required         : true,
            dropdownAutoWidth: true,
        });
    });

    function simpan_data(){
        supplier = $("#supplier").val();
        if(supplier==""){
            error_msg("Supplier Tidak Boleh Kosong");
            $("#supplier").focus()
            return false;
        }

        v_kode = document.getElementsByName('kode_barang[]');
        for (i=0; i<v_kode.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_kode[i].value == "")
            {
                error_msg("Barang Tidak Boleh Kosong");
                $("#kode_barang_"+nomor).focus()
                $("#nama_barang_"+nomor).focus()
                return false;
            }
        }

        v_qty = document.getElementsByName('Qty[]');
        for (i=0; i<v_qty.length; i++)
        {
            nomor = parseInt(i)+1;
            if ((v_qty[i].value == "") || (parseFloat(v_qty[i].value < 1)))
            {
                error_msg("Qty Tidak Boleh Kosong");
                $("#Qty_"+nomor).focus()
                return false;
            }
        }

        v_harga = document.getElementsByName('harga_barang[]');
        for (i=0; i<v_harga.length; i++)
        {
            nomor = parseInt(i)+1;
            if ((v_harga[i].value == "") || (parseFloat(v_harga[i].value < 1)))
            {
                error_msg("Harga Tidak Boleh Kosong");
                $("#harga_barang_"+nomor).focus()
                return false;
            }
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('barang_masuk/c_barang_masuk/simpan_data') ?>",
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