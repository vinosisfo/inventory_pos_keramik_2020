<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Barang Masuk.xls");
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
                    <td><?php echo $no ?></td>
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