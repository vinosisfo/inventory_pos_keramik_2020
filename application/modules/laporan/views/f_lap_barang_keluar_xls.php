<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Barang Keluar.xls");
?>
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
                    <th>Customer</th>
                    <th>Harga Jual</th>
                    <th>Diskon</th>
                    <th>Harga Diskon</th>
                    <th>Qty</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <?php
            $no              = 0;
            $urut            = 0;
            $tipe2           = "";
            $total_qty       = 0;
            $total_sub_total = 0;
            foreach ($list->result() as $data) {
                $no++; 
                $urut       += 1;
                $tipe1       = $data->Jenis.$data->NamaBarang;
                @$sub_total  = $data->Qty*$data->Harga_Diskon;

                @$total_qty_all       += $data->Qty;
                @$total_sub_total_all += $sub_total;

                if($tipe2<>$tipe1){
                    if($urut>1){ ?>
                        <tr>
                            <td colspan="5" style="text-align: right;">Total</td>
                            <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                            <td style="text-align: right;"><?php echo number_format($total_sub_total)?></td>
                        </tr>
                    <?php }
                    $tipe2           = $tipe1;
                    $total_qty       = 0;
                    $total_sub_total = 0;
                    ?>
                    <tr>
                        <td colspan="6"><b><?php echo $data->Jenis ?> - <?php echo $data->KodeBarang.' '.$data->NamaBarang ?></b></td>
                    </tr>
                <?php }
                    $total_qty       += $data->Qty;
                    $total_sub_total += $sub_total;
                ?>
                <tr>
                    <td><?php echo $urut ?></td>
                    <td><?php echo $data->NamaCustomer ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Jual) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Diskon) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Diskon) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
                    <td style="text-align: right;"><?php echo number_format($sub_total) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="5" style="text-align: right;">Total</td>
                <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                <td style="text-align: right;"><?php echo number_format($total_sub_total)?></td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: right;">Total Keseluruhan</td>
                <td style="text-align: right;"><?php echo number_format($total_qty_all)?></td>
                <td style="text-align: right;"><?php echo number_format($total_sub_total_all)?></td>
            </tr>
        </table>
    </div>
</div>