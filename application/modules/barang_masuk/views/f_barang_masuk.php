<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    <button class="btn btn-rounded btn-primary btn-sm">Data Barang Masuk</button>
                    <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Tambah Data</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <form autocomplete="off" action="<?php echo base_url('barang_masuk/c_barang_masuk/export_list')?>" method="post">
                                <table class="customers_ts" style="width : 500px;">
                                    <tr>
                                        <td style="max-width: 150px;">Supplier</td>
                                        <td style="width: 1px;">:</td>
                                        <td style="max-width : 150px;">
                                            <input type="text" name="supplier_src" id="supplier_src" style="max-width : 150px; min-width : 100px;">
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
                                        <td>
                                        <input type="text" name="date2" id="date2" class="tanggal" value="<?php echo date("Y-m-d") ?>" readonly style="width: 80px;">
                                        </td>
                                        <td style="width: 100px;">
                                            <button type="button" class="btn btn-primary btn-sm" id='btn-cari'>Cari</button>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm" id='btn-export'>Export</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <table class="customers" id="data_list" style="width : 150%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th></th>
                                        <th></th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Alamat</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Sub Total</th>
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
        $.post("<?php echo base_url('barang_masuk/c_barang_masuk/get_form_input')?>",{format : "input_data"}, function(data){
            $(".judul").html("Input Data Barang Masuk");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        }) 
    }

    function  edit_data(kode){
        $.post("<?php echo base_url('barang_masuk/c_barang_masuk/get_form_edit')?>",{format : "edit_data",kode : kode}, function(data){
            $(".judul").html("Edit Data Barang Masuk");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        }) 
    }

    function  print_data(kode){
        $.post("<?php echo base_url('barang_masuk/c_barang_masuk/get_form_print')?>",{format : "print_data",kode : kode}, function(data){
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
        "url" : "<?php echo base_url('barang_masuk/c_barang_masuk/get_data')?>",
        "type": "POST",
        "data": function ( data ) {
                data.supplier = $('#supplier_src').val();
                data.barang   = $('#barang_src').val();
                data.date1    = $('#date1').val();
                data.date2    = $('#date2').val();
                
            }
        },
        "columnDefs": [
            { "width": "2%", "targets": 0},
            { "width": "2%", "targets": 1,"className": "text-center",},
            { "width": "2%", "targets": 2,"className": "text-center",},
            { "width": "10%", "targets": 3},
            { "width": "5%", "targets": 4},
            { "width": "15%", "targets": 5},
            { "width": "15%", "targets": 6},
            { "width": "10%", "targets": 7},
            { "width": "25%", "targets": 8},
            { "width": "5%", "targets": 9, "className": "text-right",},
            { "width": "5%", "targets": 10 ,"className": "text-right",},
            { "width": "7%", "targets": 11 ,"className": "text-right",},
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

