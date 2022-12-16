<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    <button class="btn btn-rounded btn-primary btn-sm">Data Customer</button>
                    <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Tambah Data</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <form autocomplete="off">
                                <table class="customers_ts" style="width : 500px;">
                                    <tr>
                                        <td style="max-width: 150px;">Nama</td>
                                        <td style="width: 1px;">:</td>
                                        <td style="max-width : 150px;">
                                            <input type="text" name="kode_nama" id="kode_nama" style="max-width : 150px; min-width : 100px;">
                                        </td>
                                        <td>Aktif</td>
                                        <td>:</td>
                                        <td>
                                            <select name="aktif_src" id="aktif_src">
                                                <option value="">ALL</option>
                                                <option value="1">Ya</option>
                                                <option value="2">Tdk</option>
                                            </select>
                                        </td>
                                        <td style="width: 100px;">
                                            <button type="button" class="btn btn-primary btn-sm" id='btn-cari'>Cari</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <table class="customers" id="data_list" style="width : 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th></th>
                                        <th>Nama Customer</th>
                                        <th>Alamat</th>
                                        <th>No Tlp</th>
                                        <th>Email</th>
                                        <th>Aktif</th>
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

<div class="modal fade" id="modal_master"> 
    <div class="modal-dialog">
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
        $.post("<?php echo base_url('customer/c_customer/get_form_input')?>",{format : "input_data"}, function(data){
        $(".judul").html("Input Data Customer");
        $("#form_data").html(data);
        $("#modal_master").modal("show");
        }) 
    }

    function  edit_data(kode){
        $.post("<?php echo base_url('customer/c_customer/get_form_edit')?>",{format : "edit_data",kode : kode}, function(data){
        $(".judul").html("Edit Data Customer");
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
        "url" : "<?php echo base_url('customer/c_customer/get_data')?>",
        "type": "POST",
        "data": function ( data ) {
                data.kode_nama = $('#kode_nama').val();
                data.aktif     = $('#aktif_src').val();
                
            }
        },
        "columnDefs": [
        { "width": "3%", "targets": 0},
        { "width": "5%", "targets": 1},
        { "width": "25%", "targets": 2},
        { "width": "25%", "targets": 3},
        { "width": "15%", "targets": 4},
        { "width": "15%", "targets": 5,},
        { "width": "5%", "targets": 6,},
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

