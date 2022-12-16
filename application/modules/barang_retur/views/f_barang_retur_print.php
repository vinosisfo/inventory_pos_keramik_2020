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

<div class="table-responsive">
    <div id="printarea">
        <div style="margin: 15px;">
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
                    <td><?php echo date("d F Y",strtotime(date("Y-m-d"))) ?></td>

                    <td style="text-align: right; width : 200px;"><b><?php echo $head->NomorBarangKeluar ?></b></td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td>:</td>
                    <td><?php echo $head->NamaCustomer ?></td>

                    <td style="text-align: right; width : 200px;"><b><?php echo ($head->Jenis=="MASUK") ? "Non Reject" : "Reject" ?></b></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?php echo $head->Alamat ?></td>
                </tr>
            </table>
            <hr>
            <table class="customers" id="data_list" style="width : 100%;">
                <thead>
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th style="width: 170px;">Kode Barang</th>
                        <th style="width: 300px;">Nama Barang</th>
                        <th style="width: 100px;">Qty Keluar</th>
                        <th style="width: 100px;">Qty Retur</th>
                    </tr>
                </thead>
                <?php 
                $no         = 0;
                $jumlah_row = $list->num_rows();
                foreach ($list->result() as $data) {
                    $no++;
                    @$total_qty_retur += $data->QTY_SUDAH_RETUR;
                    @$total_qty       += $data->Qty;
                    ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo $data->KodeBarang ?></td>
                        <td><?php echo $data->NamaBarang ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->QTY_SUDAH_RETUR) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                    <td style="text-align: right;"><?php echo number_format($total_qty_retur)?></td>
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