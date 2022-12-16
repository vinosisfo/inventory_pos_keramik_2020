<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Laporan Transaksi</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive"> 
                            <form id="form_laporan_barang" autocomplete="off" method="post" action="<?php echo base_url('laporan/c_laporan_transaksi/get_view_export/export')?>">
                                <table class="customers_ts" id="data_list" style="width : 150%;" onchange="set_format(this)">
                                    <thead>
                                        <tr>
                                            <td style="width : 100px;">Format</td>
                                            <td style="width: 1px;">:</td>
                                            <td>
                                                <select name="format" id="format" style="width : 200px;">
                                                    <option value="">PILIH</option>
                                                    <option value="barang_masuk">Barang Masuk</option>
                                                    <option value="barang_keluar">Barang Keluar</option>
                                                    <option value="barang_retur">Barang Retur</option>
                                                    <option value="rekap_transaksi">Rekap Transaksi</option>
                                                    <option value="laba">Laba</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" name="date1" id="date1" class="tanggal" value="<?php echo date("Y-m-01")?>" readonly style="width: 80px;">
                                                <input type="text" name="date2" id="date2" class="tanggal" value="<?php echo date("Y-m-d")?>" readonly style="width: 80px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Barang</td>
                                            <td>:</td>
                                            <td>
                                                <select name="barang" id="barang" style="width: 200px;" class="select2">
                                                    <option value="">ALL</option>
                                                    <?php foreach ($barang->result() as $brg) { ?>
                                                        <option value="<?php echo $brg->Kodebarang ?>"><?php echo $brg->NamaBarang ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="jenis_tr">
                                            <td>Jenis</td>
                                            <td>:</td>
                                            <td>
                                                <select name="jenis_stok" id="jenis_stok" style="width: 200px;">
                                                    <option value="">PILIH</option>
                                                    <option value="MASUK">Non Reject</option>
                                                    <option value="REJECT">Reject</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <table class="customers_ts">
                                    <tr>
                                        <td style="text-align: right;">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="view_data(this)">View</button>
                                        </td>
                                        <td class="button_opsi" hidden>
                                            <img src="<?php echo base_url('assets/adminbsb/images/print.jpg')?>" title="Print" onclick="printDiv('printarea')" class="img-responsive" style="width: 40px;">
                                        </td>
                                        <td class="button_opsi" hidden>
                                            <button type="submit" class="btn btn-success btn-sm">Export</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <div id="data_laporan"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function printDiv(divName) {
        w=window.open();
        w.document.write($('#printarea').html());
        w.print();
        w.close();
    }

    function set_format()
    {
        format = $("#format").val();
        $("#data_laporan").hide();
        $(".button_opsi").prop("hidden",true)
        // $(".jenis_tr").prop("hidden",true)
        // if(format=="barang_retur"){
        //     $(".jenis_tr").prop("hidden",false)
        // }
    }
    function view_data()
    {
        format = $("#format").val();
        if(format=="")
        {
            error_msg("Format Tidak Boleh Kosong");
            $("#format").focus()
            return false;
        }
        $.post('<?php echo base_url('laporan/c_laporan_transaksi/get_view')?>',$("#form_laporan_barang").serialize(),function(data){
            $("#data_laporan").show();
            $("#data_laporan").html(data)
            $(".button_opsi").prop("hidden",false)
        })
    }

</script>

