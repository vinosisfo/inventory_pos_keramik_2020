<style>
    @page  
    { 
        size: auto;   /* auto is the initial value */
        /* this affects the margin in the printer settings */ 
        margin: 15px !important;  
    } 

    body  
    { 
        /* this affects the margin on the content before sending to printer */ 
        margin: 15px !important;  
    } 
    .tableFixHead2 { overflow-y: auto; height:250px; width : auto; }
</style>
<div class="tableFixHead2">
    <div id="printarea">
        <div style="margin: 15px;">
            <table class="customers_ts">
                <tr>
                    <td>Mustika Jaya Abadi</td>
                </tr>
                <tr>
                    <td>Jl. Raya Kukun daon Desa Sukatani-rajeg. Kab. tangerang</td>
                </tr>
            </table>
            <hr>
            <table class="customers">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Qty Keluar</th>
                        <th>Qty Retur</th>
                    </tr>
                </thead>
                <?php
                $no          = 0;
                $urut        = 0;
                $tipe2       = "";
                $total_qty   = 0;
                $total_retur = 0;
                foreach ($list->result() as $data) {
                    $no++; 
                    $urut  += 1;
                    $tipe1  = $data->NomorBarangKeluar;

                    @$total_qty_all   += $data->Qty;
                    @$total_retur_all += $data->QTY_SUDAH_RETUR;

                    if($tipe2<>$tipe1){
                        if($urut>1){ ?>
                            <tr>
                                <td colspan="5" style="text-align: right;">Total</td>
                                <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                                <td style="text-align: right;"><?php echo number_format($total_retur)?></td>
                            </tr>
                        <?php }
                        $tipe2       = $tipe1;
                        $urut        = 1;
                        $total_qty   = 0;
                        $total_retur = 0;
                        ?>
                        <tr>
                            <td colspan="7"><b><?php echo $data->NomorBarangKeluar ?> - <?php echo $data->NamaCustomer ?></b></td>
                        </tr>
                    <?php }
                        $total_qty   += $data->Qty;
                        $total_retur += $data->QTY_SUDAH_RETUR;
                    ?>
                    <tr>
                        <td><?php echo $urut ?></td>
                        <td><?php echo $data->Tanggal ?></td>
                        <td><?php echo ($data->Jenis=="MASUK") ? "Non Reject" : "Reject" ?></td>
                        <td><?php echo $data->KodeBarang ?></td>
                        <td><?php echo $data->NamaBarang ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->QTY_SUDAH_RETUR) ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5" style="text-align: right;">Total</td>
                    <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_retur)?></td>
                </tr>

                <tr>
                    <td colspan="5" style="text-align: right;">Total Keseluruhan</td>
                    <td style="text-align: right;"><?php echo number_format($total_qty_all)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_retur_all)?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    // var $th = $('.tableFixHead2').find('thead th')
    // $('.tableFixHead2').on('scroll', function() {
    //     $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    // });
</script>