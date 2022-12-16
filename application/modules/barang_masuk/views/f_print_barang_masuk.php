<style>
    @page  
    { 
        size: auto;   /* auto is the initial value */
        /* this affects the margin in the printer settings */ 
        margin: 25mm 25mm 25mm 25mm !important;  
    } 

    body  
    { 
        /* this affects the margin on the content before sending to printer */ 
        margin: 15px !important;  
    } 
</style>
<div id="all_print_page">
    <div id="printarea">
        <div style="margin: 15px !important;">
            <?php $head = $list->row() ?>
            <table class="customers_ts">
                <tr>
                    <td>Mustika Jaya Abadi</td>
                </tr>
                <tr>
                    <td>Jl. Raya Kukun daon Desa Sukatani-rajeg. Kab. tangerang</td>
                </tr>
            </table>
            <hr>
            <table class="customers_ts">
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><?php echo date("d F Y",strtotime($head->Tanggal)) ?></td>

                    <td style="text-align: right; width : 200px;"><b><?php echo $head->NomorBarangMasuk ?></b></td>
                </tr>
                <tr>
                    <td>Supplier</td>
                    <td>:</td>
                    <td><?php echo $head->Nama_supplier ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?php echo $head->Alamat ?></td>
                </tr>
            </table>
            <hr>
            <table class="customers" style="width: 100%;">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                </tr>
                <?php
                $no=0;
                foreach ($list->result() as $data) {
                    $no++; 
                    @$sub_total        = $data->Qty*$data->Harga;
                    @$total_qty       += $data->Qty;
                    @$total_harga     += $data->Harga;
                    @$total_sub_total += $sub_total;
                    
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
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Total</td>
                        <td style="text-align: right;"><?php echo number_format($total_qty) ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_harga) ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_sub_total) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div>
        <input type="button" onclick="printDiv('printarea')" value="Print" class="btn btn-primary bnt-xs" />
        <button type="button" class="btn btn-danger btn-sm" onclick="close_modal(this)">Close</button>
    </div>
</div>

<script>
    function printDiv(divName) {
        w=window.open();
        w.document.write($('#printarea').html());
        w.print();
        w.close();
    }

    function close_modal()
    {
        $("#modal_master").modal("hide")
    }
</script>