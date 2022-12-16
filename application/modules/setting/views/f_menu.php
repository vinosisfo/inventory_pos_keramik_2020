<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    <button class="btn btn-rounded btn-primary btn-sm">Data Menu</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="customers_ts" style="max-width : 700px;">
                                <tr>
                                    <td style="max-width: 150px;">Nama Menu</td>
                                    <td style="width: 1px;">:</td>
                                    <td style="max-width : 150px;">
                                        <input type="text" name="nama_menu" id="nama_menu" style="max-width : 150px; min-width : 100px;">
                                    </td>

                                    <td>JENIS</td>
                                    <td>:</td>
                                    <td>
                                        <select name="jenis_menu" id="jenis_menu" style="width : 150px; max-width: 350px;" onchange="set_jenis_menu(this)">
                                            <option value="">PILIH</option>
                                            <option value="MAIN MENU">MAIN MENU</option>
                                            <option value="SUB MENU 1">SUB MENU 1</option>
                                            <option value="GROUP MENU">GROUP MENU</option>
                                        </select>
                                    </td>

                                    <td style="width: 100px;">
                                        <button type="button" class="btn btn-primary btn-sm" id='btn-cari'>Cari</button>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-striped table-bordered" id="data_table" style="max-width : 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th></th>
                                        <th>Nama Menu</th> 
                                        <th>Path Menu</th>
                                        <th>Jenis</th>
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
    

    function  edit_data(kode){
        $.post("<?php echo base_url('setting/menu/get_form_edit')?>",{format : "edit_data",kode : kode}, function(data){
            $(".judul").html("Edit Data Menu");
            $("#form_data").html(data);
            $("#modal_master").modal("show");
        }) 
    }

    function hapus_data(kode)
    {
    if(confirm("Yakin Data Akan Dihapus ?")){
        $.post("<?php echo base_url('setting/user/hapus_data')?>",{format : "edit_data",kode : kode}, function(data){
        table.ajax.reload()
        info_msg("Data Berhasil Dihapus")
        }) 
    }
    }

    var table;
    table = $('#data_table').DataTable({ 
    "processing"  : true,
    "serverSide"  : true,
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order"       : [],
    "ajax"        : {
        "url" : "<?php echo base_url('setting/menu/get_data')?>",
        "type": "POST",
        "data": function ( data ) {
                data.nama_menu  = $('#nama_menu').val();
                data.jenis_menu = $('#jenis_menu').val();
                
            }
    },
    "columnDefs": [
        { "width": "3%", "targets": 0},
        { "width": "5%", "targets": 1},
        { "width": "14%", "targets": 2},
        { "width": "50%", "targets": 3},
        { "width": "15%", "targets": 4},
        { "width": "3%", "targets": 5},
    ],
    });

    $('#btn-cari').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-cari')[0].reset();
        table.ajax.reload();  //just reload table
    });

    $("#data_table_filter").css("display","none");

</script>

