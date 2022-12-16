<section class="content">
    <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
            <button class="btn btn-rounded btn-primary btn-sm">Data Akses User</button>
            <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Tambah Akses User</button>
            </div>
            <div class="body">
            <div class="table-responsive">
                <!-- <form id="form_cari" autocomplete="off"> -->
                <table class="customers_ts" style="width : 400px;">
                    <tr>
                    <td style="max-width: 150px;">Kode / User</td>
                    <td style="width: 1px;">:</td>
                    <td style="max-width : 150px;">
                        <input type="text" name="kode_user" id="kode_user" style="max-width : 150px; min-width : 100px;">
                    </td>
                    <td style="width: 100px;">
                        <button type="button" class="btn btn-primary btn-sm" id='btn-cari'>Cari</button>
                    </td>
                    </tr>
                </table>
                <!-- </form> -->
                <table class="table table-striped table-bordered" id="user" style="table-layout : auto;">
                <thead>
                    <tr>
                    <th>No</th>
                    <th></th>
                    <th></th>
                    <th>Nama Akses</th> 
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
    $.post("<?php echo base_url('setting/akses_user/get_form_input')?>",{format : "input_data"}, function(data){
        $(".judul").html("Input Data Akses User");
        $("#form_data").html(data);
        $("#modal_master").modal("show");
    }) 
    }

    function  edit_data(kode){
    $.post("<?php echo base_url('setting/akses_user/get_form_edit')?>",{format : "edit_data",kode : kode}, function(data){
        $(".judul").html("Edit Data Akses User");
        $("#form_data").html(data);
        $("#modal_master").modal("show");
    }) 
    }

    function detail_data(kode)
    {
        $.post("<?php echo base_url('setting/akses_user/get_detail_data')?>",{format : "detail_data",kode : kode}, function(data){
            $(".judul").html("Detail Akses User");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        });
    
    }

    var table;
    table = $('#user').DataTable({ 
    "processing"  : true,
    "serverSide"  : true,
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order"       : [],
    "ajax"        : {
        "url" : "<?php echo base_url('setting/akses_user/get_data')?>",
        "type": "POST",
        "data": function ( data ) {
                data.kode_user = $('#kode_user').val();
                
            }
    },
    "columnDefs": [
        { "width": "3%", "targets": 0},
        { "width": "5%", "targets": 1},
        { "width": "5%", "targets": 2},
        { "width": "50%", "targets": 3},
        { "width": "7%", "targets": 4},
    ],
    });

    $('#btn-cari').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-cari')[0].reset();
        table.ajax.reload();  //just reload table
    });

    $("#user_filter").css("display","none");

</script>

