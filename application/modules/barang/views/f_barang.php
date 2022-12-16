<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    <button class="btn btn-rounded btn-primary btn-sm">Data Barang</button>
                    <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Tambah Tambah</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <form autocomplete="off">
                                <table class="customers_ts" style="width : 500px;">
                                    <tr>
                                        <td style="max-width: 150px;">Kode / Nama</td>
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
                            <table class="customers" id="data_list" style="width : 150%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2"></th>
                                        <th rowspan="2">Kode Barang</th> 
                                        <th rowspan="2">Nama Barang</th>
                                        <th rowspan="2">Deskripsi</th>
                                        <th colspan="2" style="text-align: center;">Diskon %</th>
                                        <th rowspan="2">Keuntungan %</th>
                                        <th rowspan="2">Diskon Reject %</th>
                                        <th rowspan="2">Harga Terakhir</th>
                                        <th rowspan="2">Foto</th>
                                        <th rowspan="2">Aktif</th>
                                    </tr>
                                    <tr>
                                        <th>Pemakai</th>
                                        <th>Penjual</th>
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

<div class="modal fade" id="imagemodal_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Image preview</h4>
            </div>
            <div class="modal-body">
                <img src="" id="imagepreview_form" class="img-responsive" onerror="this.src='<?php echo base_url('assets/adminbsb/images/image-not-found.jpg')?>'" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="close_modal(this)">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function close_modal()
    {
        $("#imagemodal_form").modal("hide")
    }

    function view_gambar(data)
    {
        id= data.id;
        $('#imagepreview_form').attr('src', $('#'+id).attr('src')); 
        $('#imagemodal_form').modal('show');

    }

    function add_data()
    {
        $.post("<?php echo base_url('barang/c_barang/get_form_input')?>",{format : "input_data"}, function(data){
        $(".judul").html("Input Data barang");
        $("#form_data").html(data);
        $("#modal_master").modal("show");
        }) 
    }

    function  edit_data(kode){
        $.post("<?php echo base_url('barang/c_barang/get_form_edit')?>",{format : "edit_data",kode : kode}, function(data){
        $(".judul").html("Edit Data Barang");
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
        "url" : "<?php echo base_url('barang/c_barang/get_data')?>",
        "type": "POST",
        "data": function ( data ) {
                data.kode_nama = $('#kode_nama').val();
                data.aktif     = $('#aktif_src').val();
                
            }
        },
        "columnDefs": [
        { "width": "3%", "targets": 0},
        { "width": "5%", "targets": 1},
        { "width": "10%", "targets": 2},
        { "width": "25%", "targets": 3},
        { "width": "25%", "targets": 4},
        { "width": "10%", "targets": 5, "className": "text-right",},
        { "width": "10%", "targets": 6, "className": "text-right",},
        { "width": "10%", "targets": 7, "className": "text-right",},
        { "width": "10%", "targets": 8, "className": "text-right",},
        { "width": "10%", "targets": 9, "className": "text-center",},
        { "width": "3%", "targets": 10},
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

