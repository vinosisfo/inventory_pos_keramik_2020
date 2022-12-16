<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=laba.xls");
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
                    <th style="width : 80px;">Tanggal</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Keuntungan %</th>
                    <th>Harga Jual</th>
                    <th>Diskon %</th>
                    <th>Harga Diskon</th>
                    <th>Laba</th>
                    <th>Qty Keluar</th>
                    <th>Qty Retur</th>
                    <th>Qty Jual</th>
                    <th>Sub Total Laba</th>
                </tr>
            </thead>
            <?php
            $no              = 0;
            $urut            = 0;
            $tipe2           = "";
            $total_laba      = 0;
            $total_qty       = 0;
            $total_qty_retur = 0;
            $total_qty_jual  = 0;
            $total_sub_total = 0;
            foreach ($list->result() as $data) {
                $no++; 
                $urut       += 1;
                $tipe1       = $data->Jenis;
                $laba_set    = ($data->Laba > 0) ? ($data->Laba) : 1;
                @$sub_total  = ($data->Qty-$data->QTY_RETUR)*$data->Laba;

                @$total_laba_all      += $data->Laba;
                @$total_qty_all       += $data->Qty;
                @$total_qty_retur_all += $data->QTY_RETUR;
                @$total_qty_jual_all  += ($data->Qty-$data->QTY_RETUR);
                @$total_sub_total_all += $sub_total;

                if($tipe2<>$tipe1){
                    if($urut > 1){ ?>
                        <tr>
                            <td colspan="9" style="text-align: right;">Total</td>
                            <td style="text-align: right;"><?php echo number_format(@$total_laba)?></td>
                            <td style="text-align: right;"><?php echo number_format(@$total_qty)?></td>
                            <td style="text-align: right;"><?php echo number_format(@$total_qty_retur)?></td>
                            <td style="text-align: right;"><?php echo number_format(@$total_qty_jual)?></td>
                            <td style="text-align: right;"><?php echo number_format(@$total_sub_total)?></td>
                        </tr>
                    <?php }
                    $tipe2           = $tipe1;
                    $urut            = 1;
                    $total_laba      = 0;
                    $total_qty       = 0;
                    $total_qty_retur = 0;
                    $total_qty_jual  = 0;
                    $total_sub_total = 0;
                    ?>
                    <tr>
                        <td colspan="5"><b><?php echo ($data->Jenis=="MASUK") ? "Non Reject" : "Reject" ?></b></td>
                    </tr>
                <?php }
                    @$total_laba      += $data->Laba;
                    @$total_qty       += $data->Qty;
                    @$total_qty_retur += $data->QTY_RETUR;
                    @$total_qty_jual  += ($data->Qty-$data->QTY_RETUR);
                    @$total_sub_total += $sub_total;
                ?>
                <tr>
                    <td><?php echo $urut ?></td>
                    <td><?php echo $data->Tanggal ?></td>
                    <td><?php echo $data->KodeBarang ?></td>
                    <td><?php echo $data->NamaBarang ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Terakhir) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Keuntungan_Persen) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Jual) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Diskon) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Diskon) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Laba) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->QTY_RETUR) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty-$data->QTY_RETUR) ?></td>
                    <td style="text-align: right;"><?php echo number_format($sub_total) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="9" style="text-align: right;">Total</td>
                <td style="text-align: right;"><?php echo number_format(@$total_laba)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_retur)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_jual)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_sub_total)?></td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">Total Keseluruhan</td>
                <td style="text-align: right;"><?php echo number_format(@$total_laba_all)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_all)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_retur_all)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_jual_all)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_sub_total_all)?></td>
            </tr>
        </table>
    </div>
</div>