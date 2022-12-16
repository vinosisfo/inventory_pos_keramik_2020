<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    <button class="btn btn-rounded btn-primary btn-sm">Data Barang Keluar</button>
                    <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Tambah Data</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <form autocomplete="off" action="<?php echo base_url('barang_keluar/c_barang_keluar/export_xls')?>" method="post">
                                <table class="customers_ts" style="width : 500px;">
                                    <tr>
                                        <td style="max-width: 150px;">Customer</td>
                                        <td style="width: 1px;">:</td>
                                        <td style="max-width : 150px;">
                                            <input type="text" name="customer_src" id="customer_src" style="max-width : 150px; min-width : 100px;">
                                        </td>

                                        <td>Barang</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" name="barang_src" id="barang_src" style="max-width : 150px; min-width : 100px;">
                                        </td>
                                        <td>Tanggal</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" name="date1" id="date1" class="tanggal" value="<?php echo date("Y-m-01") ?>" readonly style="width: 80px;">
                                        </td>
                                        <td style="width: 100px;">
                                            <button type="button" class="btn btn-primary btn-sm" id='btn-cari' style="width: 70px;">Cari</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <td>:</td>
                                        <td>
                                            <select name="jenis_stok_src" id="jenis_stok_src">
                                                <option value="">ALL</option>
                                                <option value="MASUK">Non Reject</option>
                                                <option value="REJECT">Reject</option>
                                            </select>
                                        </td>
                                        <td colspan="5"></td>
                                        <td>
                                        <input type="text" name="date2" id="date2" class="tanggal" value="<?php echo date("Y-m-d") ?>" readonly style="width: 80px;">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm" id='btn-export' style="width: 70px;">Export</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <table class="customers" id="data_list" style="width : 180%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th></th>
                                        <th></th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Alamat</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Diskon</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Tgl Input</th>
                                        <th>User Input</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal_master" data-backdrop="static" data-keyboard="false"> 
    <div class="modal-dialog" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title judul"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="form_data"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>

    function add_data()
    {
        $.post("<?php echo base_url('barang_keluar/c_barang_keluar/get_form_input')?>",{format : "input_data"}, function(data){
            $(".judul").html("Input Data Barang Keluar");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        }) 
    }

    function  edit_data(kode){
        $.post("<?php echo base_url('barang_keluar/c_barang_keluar/get_form_edit')?>",{format : "edit_data",kode : kode}, function(data){
            $(".judul").html("Edit Data Barang Keluar");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        }) 
    }

    function  print_data(kode){
        // window.open('<?php echo base_url()?>barang_keluar/c_barang_keluar/get_form_print/'+kode);
        $.post("<?php echo base_url()?>barang_keluar/c_barang_keluar/get_form_print/"+kode,{format : "print",kode : kode}, function(data){
            $(".judul").html("");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        }) 
    }

    var table;
    table = $('#data_list').DataTable({ 
        "processing"  : true,
        "serverSide"  : true,
        'responsive'  : true,
        'ordering'    : false,
        'lengthChange': false,
        "order"       : [],
        "ajax"        : {
        "url" : "<?php echo base_url('barang_keluar/c_barang_keluar/get_data')?>",
        "type": "POST",
        "data": function ( data ) {
                data.customer   = $('#customer_src').val();
                data.barang     = $('#barang_src').val();
                data.jenis_stok = $("#jenis_stok_src").val();
                data.date1      = $('#date1').val();
                data.date2      = $('#date2').val();
                
            }
        },
        "columnDefs": [
            { "width": "2%", "targets": 0},
            { "width": "2%", "targets": 1},
            { "width": "2%", "targets": 2},
            { "width": "8%", "targets": 3},
            { "width": "5%", "targets": 4},
            { "width": "15%", "targets": 5},
            { "width": "15%", "targets": 6},
            { "width": "10%", "targets": 7},
            { "width": "25%", "targets": 8},
            { "width": "5%", "targets": 9, "className": "text-right",},
            { "width": "5%", "targets": 10 ,"className": "text-right",},
            { "width": "5%", "targets": 11 ,"className": "text-right",},
            { "width": "13%", "targets": 12 ,"className": "text-right",},
            { "width": "5%", "targets": 13},
            { "width": "5%", "targets": 14},
        ],
    });

    $('#btn-cari').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-cari')[0].reset();
        table.ajax.reload();  //just reload table
    });

    $("#data_list_filter").css("display","none");

</script>

