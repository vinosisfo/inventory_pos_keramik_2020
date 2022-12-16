<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=List Stok.xls");
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
            <tr>
                <th >No</th>
                <th >Kode Barang</th>
                <th >Nama Barang</th>
                <th >Stok</th>
            </tr>
            <?php
            $no         = 0;
            $urut       = 0;
            $tipe2      = "";
            $total_stok = 0;
            foreach ($list->result() as $data) {
                $no++; 
                $urut           += 1;
                $tipe1           = $data->Jenis;
                @$total_all_stok += $data->Qty_Stok;
                if($tipe2<>$tipe1){
                    if($urut>1){ ?>
                        <tr>
                            <td colspan="3" style="text-align: right;">Total</td>
                            <td style="text-align: right;"><?php echo number_format($total_stok)?></td>
                        </tr>
                    <?php }
                    $tipe2      = $tipe1;
                    $urut       = 1;
                    $total_stok = 0;
                    ?>
                    <tr>
                        <td colspan="4"><b><?php echo $data->Jenis ?></b></td>
                    </tr>
                <?php }
                    $total_stok += $data->Qty_Stok;
                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $data->Kodebarang ?></td>
                    <td><?php echo $data->NamaBarang ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Qty_Stok) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3" style="text-align: right;">Total</td>
                <td style="text-align: right;"><?php echo number_format($total_stok)?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Total Keseluruhan</td>
                <td style="text-align: right;"><?php echo number_format($total_all_stok)?></td>
            </tr>
        </table>
    </div>
</div>