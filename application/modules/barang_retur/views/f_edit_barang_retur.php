<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <?php $head = $list->row() ?>
        <table class="customers_ts">
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>
                    <input type="text" name="tanggal" id="tanggal" class="tanggal" value="<?php echo date("Y-m-d") ?>" readonly style="width: 80px;">
                </td>
            </tr>
            <tr>
                <td>No Keluar - Customer</td>
                <td>:</td>
                <td style="width : 400px;">
                    <select style="width: 99%;" name="nomor_keluar" id="nomor_keluar" onchange="get_jenis(this)">
                        <option value="<?php echo $head->NomorBarangKeluar ?>"><?php echo $head->NomorBarangKeluar ?> - (<?php echo $head->NamaCustomer ?>)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>
                    <span id="jenis_keluar"><?php echo ($head->Jenis=="MASUK") ? "Non Reject" : "Reject" ?></span>
                </td>
            </tr>
        </table>
        <br>
        <div><button type="button" class="btn btn-danger btn-xs" onclick="add_row(this)">+ Baris</button></div>
        
        <table class="customers" style="width: 770px;">
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 20px;"></th>
                <th style="width: 170px;">Kode Barang</th>
                <th style="width: 300px;">Nama Barang</th>
                <th style="width: 100px;">Qty Keluar</th>
                <th style="width: 100px;">Sudah Retur</th>
                <th style="width: 100px;">Qty Bebas</th>
                <th style="width: 100px;">Qty</th>
            </tr>
            <tbody id="row_body">
                <?php
                $no =0;
                $jumlah_row = $list->num_rows();
                foreach ($list->result() as $data) {
                    $no++; ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs" id="btn_hapus_<?php echo $no ?>" onclick="hapus_baris('<?php echo $data->NomorBarangKeluar ?>','<?php echo $data->KodeBarang ?>','<?php echo $data->QTY_SUDAH_RETUR ?>','<?php echo $jumlah_row ?>')">Hapus</button>
                        </td>
                        <td>
                            <select name="kode_barang[]" id="kode_barang_<?php echo $no ?>" style="width: 99%;" onchange="get_barang(this,'<?php echo $no ?>','kode');">
                                <option value="<?php echo $data->KodeBarang ?>"><?php echo $data->KodeBarang ?></option>
                            </select>  
                        </td>

                        <td>
                            <select name="nama_barang[]" id="nama_barang_<?php echo $no ?>" style="width: 99%;" onchange="get_barang(this,'<?php echo $no ?>','nama');">
                                <option value="<?php echo $data->KodeBarang ?>"><?php echo $data->NamaBarang ?></option>
                            </select>  
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->Qty) ?>" type="text" name="Qty_awal[]" id="Qty_awal_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->QTY_SUDAH_RETUR) ?>" type="text" name="sudah_retur[]" id="sudah_retur_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->QTY_BEBAS) ?>" type="text" name="Qty_keluar[]" id="Qty_keluar_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>')" class="transparant" readonly>
                        </td>
                        <td>
                            <input value="<?php echo number_format($data->QTY_SUDAH_RETUR) ?>" type="text" name="Qty[]" id="Qty_<?php echo $no ?>" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('<?php echo $no ?>');set_validasi_qty('<?php echo $no ?>')">
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <td colspan="6" style="text-align: right;">Total</td>
                <td><input type="text" readonly style="border: none; text-align : right;" id="total_keluar"></td>
                <td><input type="text" readonly style="border: none; text-align : right;" id="total_retur"></td>
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
    function hapus_baris(nomor,kodebarang,qty,jumlah_data)
    {
        pesan = (jumlah_data=="1") ? " Semua Transaksi Retur Dengan Nomor Keluar '"+nomor+"' Akan DiHapus?" : " Yakin Barang Akan Dihapus ?";
        if(confirm(pesan)){
            $.post('<?php echo base_url('barang_retur/c_barang_retur/hapus_row')?>',{nomor : nomor, kodebarang : kodebarang,jumlah_data : jumlah_data,qty : qty}, function(data){
                if(jumlah_data=="1"){
                    table.ajax.reload();
                    $("#modal_master").modal("hide")
                } else
                {
                    edit_data(nomor)
                }
            }, "json")
        }
    }

    function get_jenis()
    {
        nomor_keluar = $("#nomor_keluar").val();
        $.post('<?php echo base_url('barang_retur/c_barang_retur/get_jenis')?>',{nomor : nomor_keluar},function(data){
            $("#jenis_keluar").html(data.jenis)
        },"json")
    }

    function add_row()
    {
        i = $("#row_body tr").length+1;
        row = '<tr>'+
                    '<td>'+i+'</td>'+
                    '<td>'+
                        '<button type="button" class="btn btn-danger btn-xs hapus_row" id="btn_hapus_'+i+'">Hapus</button>'+
                    '</td>'+
                    '<td>'+
                        '<select name="kode_barang[]" id="kode_barang_'+i+'" style="width: 99%;" onchange="get_barang(this,'+i+',\''+'kode'+'\');" onclick="get_barang_kode('+i+')">'+
                            '<option value="">Pilih</option>'+
                        '</select>'+  
                    '</td>'+

                    '<td>'+
                        '<select name="nama_barang[]" id="nama_barang_'+i+'" style="width: 99%;" onchange="get_barang(this,'+i+',\''+'nama'+'\');" onclick="get_barang_nama('+i+')">'+
                            '<option value="">Pilih</option>'+
                        '</select>'+  
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="Qty_awal[]" id="Qty_awal_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="sudah_retur[]" id="sudah_retur_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="Qty_keluar[]" id="Qty_keluar_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+')" class="transparant" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="Qty[]" id="Qty_'+i+'" style="width: 100px; text-align : right;" onkeyup="num_only(this);set_sub_total('+i+');set_validasi_qty('+i+')">'+
                    '</td>'+
                '</tr>';
        $("#row_body").append(row);

        // get_barang_kode(i);
        // get_barang_nama(i);
    }
    // add_row();
    $("#row_body").on('click', '.hapus_row', function(){
        $(this).parent().parent().remove();
        console.log(this.id)
        set_sub_total('1');
    });

    function get_barang_kode(urut)
    {
        var arr = $('select[name="kode_barang[]"]').map(function () {
                    return this.value;
                }).get();
        // nomor_keluar = $("#nomor_keluar").val();
        $('#kode_barang_'+urut).select2({
            placeholder      : 'Pilih Barang',
            dropdownAutoWidth: true,
            ajax             : {
                url     : "<?php echo base_url('barang_retur/c_barang_retur/get_barang_kode')?>",
                dataType: 'json',
                type    : "post",
                delay   : 100,
                data    : function (params) {
                    nomor_keluar = $("#nomor_keluar").val();
                    return JSON.stringify({
                        term       : params.term,
                        kode_barang: arr,
                        nomor      : nomor_keluar,
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

        // nomor_keluar = $("#nomor_keluar").val();
        $('#nama_barang_'+urut).select2({
            placeholder      : 'Pilih Barang',
            dropdownAutoWidth: true,
            ajax             : {
                url     : "<?php echo base_url('barang_retur/c_barang_retur/get_barang_nama')?>",
                dataType: 'json',
                type    : "post",
                delay   : 100,
                data    : function (params) {
                    nomor_keluar = $("#nomor_keluar").val();
                    return JSON.stringify({
                        term       : params.term,
                        nama_barang: arr,
                        nomor      : nomor_keluar,
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

        kode_barang_set = (jenis=="kode") ? (kode_barang) : (nama_barang);
        $.post('<?php echo base_url('barang_masuk/c_barang_masuk/get_detail_barang') ?>',{kode_barang : kode_barang_set, jenis : jenis},function(data){
            if(jenis=="kode"){
                $("#nama_barang_"+urut).html(data.barang)
            } else {
                $("#kode_barang_"+urut).html(data.barang)
            }
            get_duplikat(urut);
            get_qty(urut);
        },"json")
    }

    function get_qty(urut){
        kode_barang  = $("#kode_barang_"+urut).val();
        nomor_keluar = $("#nomor_keluar").val()
        $.post('<?php echo base_url('barang_retur/c_barang_retur/get_qty')?>',{kode_barang : kode_barang,nomor : nomor_keluar},function(data){
            $("#Qty_awal_"+urut).val(data.qty_awal)
            $("#sudah_retur_"+urut).val(data.qty_retur)
            $("#Qty_keluar_"+urut).val(data.hasil)
        },"json")
    }

    function get_duplikat(urut)
    {
        $.post('<?php echo base_url('barang_masuk/c_barang_masuk/get_duplikat')?>',$("#form_data_input").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Barang, Barang Tidak Boleh Sama Dalam Satu Transaksi");
                opsi_nama = '<option value="">Pilih</option>';
                $("#kode_barang_"+urut).html(opsi_nama)
                $("#nama_barang_"+urut).html(opsi_nama)
                return false;
            }
        },"json");
    }

    function set_sub_total(urut)
    {
        qty_keluar = $("#Qty_keluar_"+urut).val();
        qty        = $("#Qty_"+urut).val();
        $.ajax({
            url     : "<?php echo base_url('barang_retur/c_barang_retur/set_sub_total')?>",
            type    : "POST",
            data    : $("#form_data_input").serialize()+"&qty_keluar="+qty_keluar+"&qty_baris="+qty,
            dataType: "json",
            success : function(data)
            {
                $("#total_keluar").val(data.total_keluar);
                $("#total_retur").val(data.total_retur);
            }
        })
    }

    function set_validasi_qty(urut)
    {
        qty_keluar = $("#Qty_keluar_"+urut).val();
        qty_retur  = $("#Qty_"+urut).val();
        $.post('<?php echo base_url('barang_retur/c_barang_retur/cek_qty')?>',{qty_keluar : qty_keluar, qty_retur : qty_retur}, function(data){
            if(data.hasil=="lebih"){
                error_msg("Qty Retur Tidak Boleh Lebih Dari Qty Keluar");
                $("#Qty_"+urut).val("");
                set_sub_total(urut)
                return false;
            }
        },"json")
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
        nomor_keluar = $("#nomor_keluar").val();
        if(nomor_keluar==""){
            error_msg("Nomor Keluar / Customer Tidak Boleh Kosong");
            $("#nomor_keluar").focus()
            return false;
        }

        total_retur = $("#total_retur").val()
        if(total_retur==""){
            error_msg("Isi Qty Retur Minimal 1 ");
            return false;
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

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url     : "<?php echo base_url('barang_retur/c_barang_retur/update_data') ?>",
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

    $(".tanggal").datepicker({
        autoclose     : true,
        format        : 'yyyy-mm-dd',
        changeMonth   : true,
        changeYear    : true,
        orientation   : "top",
        endDate       : '+0d',
        autoclose     : true,
        todayHighlight: true,
        toggleActive  : true,
        
    });
</script>