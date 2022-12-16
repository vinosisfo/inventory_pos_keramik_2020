<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Lap Rekap.xls");
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
                    <th>Tanggal</th>
                    <th>Qty Masuk</th>
                    <th>Qty Keluar</th>
                    <th>Qty Retur</th>
                </tr>
            </thead>
            <?php
            $no           = 0;
            $urut         = 0;
            $tipe2        = "";
            $total_masuk  = 0;
            $total_keluar = 0;
            $total_retur  = 0;
            foreach ($list->result() as $data) {
                $no++; 
                $urut  += 1;
                $tipe1  = $data->NamaBarang;

                @$total_qty_masuk  += $data->Qty_Masuk;
                @$total_qty_keluar += $data->Qty_Keluar;
                @$total_qty_retur  += $data->Qty_Retur;

                if($tipe2<>$tipe1){
                    if($urut > 1){ ?>
                        <tr>
                            <td colspan="2" style="text-align: right;">Total</td>
                            <td style="text-align: right;"><?php echo number_format(@$total_masuk)?></td>
                            <td style="text-align: right;"><?php echo number_format(@$total_keluar)?></td>
                            <td style="text-align: right;"><?php echo number_format(@$total_retur)?></td>
                        </tr>
                    <?php }
                    $tipe2        = $tipe1;
                    $urut         = 1;
                    $total_masuk  = 0;
                    $total_keluar = 0;
                    $total_retur  = 0;
                    ?>
                    <tr>
                        <td colspan="5"><b><?php echo $data->KodeBarang ?>-<?php echo $data->NamaBarang ?></b></td>
                    </tr>
                <?php }
                    @$total_masuk  += $data->Qty_Masuk;
                    @$total_keluar += $data->Qty_Keluar;
                    @$total_retur  += $data->Qty_Retur;
                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $data->Tanggal ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty_Masuk) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty_Keluar) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty_Retur) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" style="text-align: right;">Total</td>
                <td style="text-align: right;"><?php echo number_format(@$total_masuk)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_keluar)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_retur)?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">Total Keseluruhan</td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_masuk)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_keluar)?></td>
                <td style="text-align: right;"><?php echo number_format(@$total_qty_retur)?></td>
            </tr>
        </table>
    </div>
</div>