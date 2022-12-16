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
</style>
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
                <th rowspan="2">No</th>
                <th rowspan="2">Kode Barang</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Harga Beli</th>
                <th rowspan="2">Keuntungan %</th>
                <th rowspan="2">Harga Jual</th>
                <th rowspan="2">Diskon %</th>
                <th rowspan="2">Diskon Reject %</th>
                <th colspan="2" style="text-align: center;">Harga Jual</th>
            </tr>
            <tr>
                <th>Non Reject</th>
                <th>Reject</th>
            </tr>
            <?php
            $no=0;
            foreach ($list->result() as $data) {
                $no++; 
                $diskon_set              = ($data->Diskon>0) ? ($data->Diskon) : 0;
                $diskon_reject_set       = ($data->Diskon_Reject>0) ? ($data->Diskon_Reject) : 1;
                $harga_diskon_non_reject = $data->Harga_Jual-(($data->Harga_Jual*$diskon_set)/100);
                $harga_diskon_reject     = $data->Harga_Jual-(($data->Harga_Jual*($diskon_reject_set+$diskon_set))/100);

                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $data->Kodebarang ?></td>
                    <td><?php echo $data->NamaBarang ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Terakhir) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Keuntungan_Persen) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Harga_Jual) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Diskon) ?></td>
                    <td style="text-align: right;"><?php echo number_format($data->Diskon_Reject) ?></td>
                    <td style="text-align: right;"><?php echo number_format($harga_diskon_non_reject) ?></td>
                    <td style="text-align: right;"><?php echo number_format($harga_diskon_reject) ?></td>
                    
                </tr>
            <?php } ?>
        </table>
    </div>
</div>