<form id="form_data_input" autocomplete="off">
    <div class="table-responsive">
        <table class="customers_ts">
            <tr>
                <td>Nama Barang</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama_barang" id="nama_barang" style="min-width : 150px; max-width: 300px;" maxlength="100">
                </td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>:</td>
                <td>
                    <textarea name="deskripsi" id="deskripsi" maxlength="200" style="min-width: 150px; max-width : 250px; resize : none;"></textarea>
                </td>
            </tr>
            <tr>
                <td>Keuntungan %</td>
                <td>:</td>
                <td>
                    <input type="text" name="untung" id="untung" style="width : 70px; max-width : 250px; text-align : right;" maxlength="5" onkeyup="num_only(this)">
                </td>
            </tr>

            <tr>
                <td>Diskon % (Pemakai)</td>
                <td>:</td>
                <td>
                    <input type="text" name="diskon" id="diskon" style="width : 70px; max-width : 250px; text-align : right;" maxlength="5" onkeyup="num_only(this);cek_diskon(this);">
                </td>
            </tr>

            <tr>
                <td>Diskon % (Penjual)</td>
                <td>:</td>
                <td>
                    <input type="text" name="diskon_jual" id="diskon_jual" style="width : 70px; max-width : 250px; text-align : right;" maxlength="5" onkeyup="num_only(this);cek_diskon(this)">
                </td>
            </tr>
            <tr>
                <td>Diskon Reject %</td>
                <td>:</td>
                <td>
                    <input type="text" name="diskon_reject" id="diskon_reject" style="width : 70px; max-width : 250px; text-align : right;" maxlength="5" onkeyup="num_only(this);cek_diskon(this)">
                </td>
            </tr>
            <tr>
                <td>Foto</td>
                <td>:</td>
                <td>
                    <input type="file" name="foto" id="foto" accept="image/*" onchange="loadFile(event,this.id)">
                    <img id="img_foto" onclick="view_gambar(this)" onerror="this.src='<?php echo base_url('assets/adminbsb/images/image-not-found.jpg')?>'" class="img-responsive" style="width: 150px; height : auto;">
                </td>
            </tr>
            <tr>
                <td colspan="3"><b><span id="pesan" style="color: red;">&nbsp;</span></b></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">
                    <button type="submit" id="btn_simpan" class="btn btn-primary btn-sm" >Simpan Data</button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </td>
            </tr>
        </table>
    </div>
</form>



<script>
    function cek_diskon()
    {

    }
    
    var loadFile = function(event,id) {
        var reader = new FileReader();
        reader.onload = function(){
            var output     = document.getElementById("img_foto");
                output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    function num_only(data)
    {
        var isi   = data.value;
        var isi2  = $(this);
        let hasil = format_number(isi);
        $(data).val(hasil);
        console.log(hasil);
    }

    $('#form_data_input').submit(function(e){
        e.preventDefault(); 
        nama_barang = $("#nama_barang").val();
        deskripsi   = $("#deskripsi").val();
        untung      = $("#untung").val();
        if(nama_barang==""){
            error_msg("Nama Barang Tidak Boleh Kosong");
            $("#nama_barang").focus()
            return false;
        }

        if(deskripsi==""){
            error_msg("Deskripsi Tidak Boleh Kosong");
            $("#deskripsi").focus()
            return false;
        }

        if((untung=="") || (parseFloat(untung) < 1)){
            if(confirm("Keuntunan % : 0 , Lanjutkan ? ")){
                
            } else {
                $("#untung").focus();
                return false;
            }
        }

        $("#btn_simpan").prop("disabled",true);
        $.ajax({
            url        : '<?php echo base_url('barang/c_barang/simpan_data') ?>',
            type       : "POST",
            data       : new FormData(this),
            dataType   : "json",
            processData: false,
            contentType: false,
            cache      : false,
            async      : true,
            success    : function(data)
            {
                if(data.pesan==="ok")
                {
                    succes_msg("Data Berhasil Disimpan");
                    $("#modal_master").modal("hide");
                    table.ajax.reload();
                } else {
                    error_msg("Nama Barang "+nama_barang+ " Sudah Ada, Proses Tidak Dapat DIlanjukan");
                    $("#nama_barang").focus()
                    $("#btn_simpan").prop("disabled",false);
                    return false;
                }
            },
            error : function(data)
            {
                error_msg("Error Hubungi Developer");
                $("#btn_simpan").prop("disabled",false);
                return false;
            }
        })
    })

    // function simpan_data(){
    //     nama_barang = $("#nama_barang").val();
    //     deskripsi   = $("#deskripsi").val();
    //     if(nama_barang==""){
    //         error_msg("Nama Barang Tidak Boleh Kosong");
    //         $("#nama_barang").focus()
    //         return false;
    //     }

    //     if(deskripsi==""){
    //         error_msg("Deskripsi Tidak Boleh Kosong");
    //         $("#deskripsi").focus()
    //         return false;
    //     }

    //     $("#btn_simpan").prop("disabled",true);
    //     $.ajax({
    //         url     : "<?php //echo base_url('barang/c_barang/simpan_data') ?>",
    //         type    : "POST",
    //         data    : $("#form_data_input").serialize(),
    //         dataType: "json",
    //         success : function(data)
    //         {
    //             succes_msg("Data Berhasil Disimpan");
    //             $("#modal_master").modal("hide");
    //             table.ajax.reload();
    //         },
    //         error : function(data)
    //         {
    //             error_msg("Error Hubungi Developer");
    //             $("#btn_simpan").prop("disabled",false);
    //             return false;
    //         }
    //     })
    // }
</script>