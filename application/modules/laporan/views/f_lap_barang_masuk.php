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
    .tableFixHead2 { overflow-y: auto; height: 195px; width : 1200px; }
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
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <?php
                $no              = 0;
                $urut            = 0;
                $tipe2           = "";
                $total_qty       = 0;
                $total_harga     = 0;
                $total_sub_total = 0;
                foreach ($list->result() as $data) {
                    $no++; 
                    $urut       += 1;
                    $tipe1       = $data->Nama_supplier;
                    @$sub_total  = $data->Qty*$data->Harga;

                    @$total_qty_all       += $data->Qty;
                    @$total_harga_all     += $data->Harga;
                    @$total_sub_total_all += $sub_total;

                    if($tipe2<>$tipe1){
                        if($urut>1){ ?>
                            <tr>
                                <td colspan="3" style="text-align: right;">Total</td>
                                <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                                <td style="text-align: right;"><?php echo number_format($total_harga)?></td>
                                <td style="text-align: right;"><?php echo number_format($total_sub_total)?></td>
                            </tr>
                        <?php }
                        $tipe2           = $tipe1;
                        $total_qty       = 0;
                        $total_harga     = 0;
                        $total_sub_total = 0;
                        ?>
                        <tr>
                            <td colspan="6"><b><?php echo $data->Nama_supplier ?></b></td>
                        </tr>
                    <?php }
                        $total_qty       += $data->Qty;
                        $total_harga     += $data->Harga;
                        $total_sub_total += $sub_total;
                    ?>
                    <tr>
                        <td><?php echo $urut ?></td>
                        <td><?php echo $data->KodeBarang ?></td>
                        <td><?php echo $data->NamaBarang ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Harga) ?></td>
                        <td style="text-align: right;"><?php echo number_format($sub_total) ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_harga)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_sub_total)?></td>
                </tr>

                <tr>
                    <td colspan="3" style="text-align: right;">Total Keseluruhan</td>
                    <td style="text-align: right;"><?php echo number_format($total_qty_all)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_harga_all)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_sub_total_all)?></td>
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