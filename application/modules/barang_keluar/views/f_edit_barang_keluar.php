<script>
    function set_sub_total(urut)
    {
        harga    = $("#harga_diskon_"+urut).val();
        qty      = $("#Qty_"+urut).val();
        qty_stok = $("#Qty_stok_"+urut).val();
        $.ajax({
            url     : "<?php echo base_url('barang_keluar/c_barang_keluar/set_sub_total')?>",
            type    : "POST",
            data    : $("#form_data_input").serialize()+"&harga_baris="+harga+"&qty_baris="+qty+"&qty_stok_baris="+qty_stok,
            dataType: "json",
            success : function(data)
            {
                if(data.status_qty=="lebih"){
                    error_msg("Qty Tidak Boleh Lebih Dari Stok");
                    $("#Qty_"+urut).focus()
                    $("#Qty_"+urut).val("")
                    set_sub_total('1');
                    return false;
                } else {
                    $("#sub_total_"+urut).html(data.sub_total);
                    $("#total_qty").html(data.total_qty);
                    $("#total_harga").html(data.total_harga);
                    $("#total_sub_total").val(data.total_sub_total);
                }
            }
        })
    }
</script>
<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <?php $head = $list->row(); ?>
        <table class="customers_ts">
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>
                    <input type="hidden" name="nomor" id="nomor" value="<?php echo $head->NomorBarangKeluar ?>" readonly>
                    <input type="text" name="tanggal" id="tanggal" class="tanggal" value="<?php echo $head->Tanggal ?>" readonly style="width: 80px;">
                </td>
            </tr>
            <tr>
                <td>Customer</td>
                <td>:</td>
                <td style="width : 300px;">
                    <select style="width: 99%;" name="customer" id="customer" class="select2">
                        <option value="<?php echo $head->id_customer ?>"><?php echo $head->NamaCustomer ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>
                    <select name="jenis_stok" id="jenis_stok">
                        <?php if($head->Jenis=="MASUK"){ ?>
                        <option value="MASUK">Non Reject</option>
                        <?php } else { ?>
                        <option value="REJECT">Reject</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <br>
        <div><button type="button" class="btn btn-danger btn-xs" onclick="add_row(this)">+ Baris</button></div>
        
        <table class="customers" style="width: 1100px;">
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 20px;"></th>
                <th style="width: 130px;">Kode Barang</th>
                <th style="width: 300px;">Nama Barang</th>
                <th style="width: 100px;">Harga</th>
                <th style="width: 100px;">Diskon %</th>
                <th style="width: 100px;">Harga Diskon</th>
                <th style="width: 100px;">Stok</th>
                <th style="width: 100px;">Qty</th>
                <th style="width: 120px;">Sub Total</th>
            </tr>
            <tbody id="row_body">
                <?php 
                $no         = 0;
                $jumlah_row = $list->num_rows();
                foreach ($list->result() as $data) {
                    $no++; ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs" id="btn_hapus_<?php echo $no ?>" onclick="hapus_row('<?php echo $data->NomorBarangKeluar ?>','<?php echo $data->KodeBarang ?>','<?php echo $data->Jenis ?>','<?php echo $jumlah_row ?>','<?php echo $data->Qty ?>')">Hapus</button>
                        </td>
                        <td>
                            <select class="select2" name="kode_barang[]" id="kode_barang_<?php echo $no ?>" style="width: 99%;" onchange="get_barang(this,'<?php echo $no ?>','kode')">
                                <option value="<?php echo $data->KodeBarang ?>"><?php echo $data->KodeBarang ?></option>
                            </select>  
                        </td>

                        <td>
                            <select class="select2" name="nama_barang[]" id="nama_barang_<?php echo $no ?>" style="width: 99%;" onchange="get_barang(this,'<?php echo $no ?>','nama')">
                                <option value="<?php echo $data->KodeBarang ?>"><?php echo $data->NamaBarang ?></option>
                            </select>
                        </td>
                        <td>
                            <input value="<?php echo $data->Harga_Terakhir ?>" type="hidden" name="harga_terakhir[]" id="harga_terakhir_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                            <input value="<?php echo $data->Keuntungan_Persen ?>" type="hidden" name="untung_persen[]" id="untung_persen_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                            <input value="<?php echo number_format($data->Harga_Jual) ?>" type="text" name="harga_barang[]" id="harga_barang_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->Diskon) ?>" type="text" name="diskon[]" id="diskon_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>)" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->Harga_Diskon) ?>" type="text" name="harga_diskon[]" id="harga_diskon_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->qty_stok_set) ?>" type="text" name="Qty_stok[]" id="Qty_stok_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo $data->Qty ?>" type="hidden" name="qty_lama[]" id="qty_lama_<?php echo $no ?>" readonly>
                            <input value="<?php echo number_format($data->Qty) ?>" type="text" name="Qty[]" id="Qty_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>');">
                        </td>
                        
                        <td style="text-align: right;">
                            <span id="sub_total_<?php echo $no ?>"></span>
                            <script>
                                set_sub_total('<?php echo $no ?>');
                            </script>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <td colspan="8" style="text-align: right;">Total</td>
                <td style="text-align: right;"><span id="total_qty"></span></td>
                <td><input type="text" readonly style="border: none; text-align : right;" id="total_sub_total"></td>
            </tfoot>
        </table>
    </div>
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
                        '<select class="select2" name="kode_barang[]" id="kode_barang_'+i+'" style="width: 99%;" onchange="get_barang(this,'+i+',\''+'kode'+'\')" onclick="get_barang_kode('+i+')">'+
                            '<option value="">Pilih</option>'+
                        '</select>'+  
                    '</td>'+

                    '<td>'+
                        '<select class="select2" name="nama_barang[]" id="nama_barang_'+i+'" style="width: 99%;" onchange="get_barang(this,'+i+',\''+'nama'+'\')" onclick="get_barang_nama('+i+')">'+
                            '<option value="">Pilih</option>'+
                        '</select>'+  
                    '</td>'+
                    '<td>'+
                        '<input type="hidden" name="harga_terakhir[]" id="harga_terakhir_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                        '<input type="hidden" name="untung_persen[]" id="untung_persen_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                        '<input type="text" name="harga_barang[]" id="harga_barang_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="diskon[]" id="diskon_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="harga_diskon[]" id="harga_diskon_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="Qty_stok[]" id="Qty_stok_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="hidden" name="qty_lama[]" id="qty_lama_'+i+'" readonly>'+
                        '<input type="text" name="Qty[]" id="Qty_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+');">'+
                    '</td>'+
                    
                    '<td style="text-align: right;">'+
                        '<span id="sub_total_'+i+'"></span>'+
                    '</td>'+
                '</tr>';
        $("#row_body").append(row);

        get_barang_kode(i);
        get_barang_nama(i);
    }
    // add_row();
    $("#row_body").on('click', '.hapus_row', function(){
        $(this).parent().parent().remove();
        console.log(this.id)
        set_sub_total('1');
    });

    function hapus_row(nomor,kodebarang,jenis,jumlah_row,qty)
    {
        pesan = (jumlah_row=="1") ? " Semua Data transaksi Nomor '"+nomor+"' Akan Dihapus ?" : " Yakin Data Akan Dihapus ?";
        if(confirm(pesan)){
            $.post('<?php echo base_url('barang_keluar/c_barang_keluar/hapus_row') ?>',{nomor : nomor , kodebarang : kodebarang,jenis : jenis,jumlah_row : jumlah_row,qty : qty}, function(data){
                if(data.pesan=="ok"){
                    if(jumlah_row=="1"){
                        table.ajax.reload();
                        $("#modal_master").modal("hide");
                    } else {
                        edit_data(nomor)
                    }
                }
            }, "json")
        }
    }

    function get_barang_kode(urut)
    {
        var arr = $('select[name="kode_barang[]"]').map(function () {
                    return this.value;
                }).get();

        $('#kode_barang_'+urut).select2({
            placeholder      : 'Pilih Barang',
            dropdownAutoWidth: true,
            ajax             : {
                url     : "<?php echo base_url('barang_keluar/c_barang_keluar/get_barang_kode')?>",
                dataType: 'json',
                type    : "post",
                delay   : 100,
                data    : function (params) {
                    jenis_stok = $("#jenis_stok").val();
                    return JSON.stringify({
                        term       : params.term,
                        kode_barang: arr,
                        jenis      : jenis_stok,
                    });
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function get_barang_nama(urut)
    {
        var arr = $('select[name="nama_barang[]"]').map(function () {
                    return this.value;
                }).get();

        $('#nama_barang_'+urut).select2({
            placeholder      : 'Pilih Barang',
            dropdownAutoWidth: true,
            ajax             : {
                url     : "<?php echo base_url('barang_keluar/c_barang_keluar/get_barang_nama')?>",
                dataType: 'json',
                type    : "post",
                delay   : 100,
                data    : function (params) {
                    jenis_stok = $("#jenis_stok").val();
                    return JSON.stringify({
                        term       : params.term,
                        kode_barang: arr,
                        jenis      : jenis_stok,
                    });
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function get_barang(data,urut,jenis)
    {
        kode_barang = $("#kode_barang_"+urut).val()
        nama_barang = $("#nama_barang_"+urut).val()
        jenis_stok  = $("#jenis_stok").val()

        kode_barang_set = (jenis=="kode") ? (kode_barang) : (nama_barang);
        $.post('<?php echo base_url('barang_keluar/c_barang_keluar/get_detail_barang') ?>',{kode_barang : kode_barang_set, jenis : jenis,jenis_stok : jenis_stok},function(data){
            if(jenis=="kode"){
                $("#nama_barang_"+urut).html(data.barang)
            } else {
                $("#kode_barang_"+urut).html(data.barang)
            }
            $("#harga_barang_"+urut).val(data.harga_jual)
            $("#diskon_"+urut).val(data.diskon)
            $("#harga_diskon_"+urut).val(data.harga_diskon)
            $("#Qty_stok_"+urut).val(data.qty_stok)
            $("#harga_terakhir_"+urut).val(data.harga_terakhir)
            $("#untung_persen_"+urut).val(data.untung_persen)
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

    

    function num_only(data)
    {
        var isi = data.value;
        var isi2 = $(this);
        let hasil = format_number(isi);
        $(data).val(hasil);
        console.log(hasil);
    }

    

    function simpan_data(){
        customer        = $("#customer").val();
        jenis_stok      = $("#jenis_stok").val();
        total_sub_total = $("#total_sub_total").val();
        
        if(customer==""){
            error_msg("customer Tidak Boleh Kosong");
            $("#customer").focus()
            return false;
        }
        if(jenis_stok==""){
            error_msg("Jenis Stok Tidak Boleh Kosong");
            $("#jenis_stok").focus()
            return false;
        }

        if(total_sub_total==""){
            error_msg("Isi Minimal 1 Barang");
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
            url     : "<?php echo base_url('barang_keluar/c_barang_keluar/update_data') ?>",
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