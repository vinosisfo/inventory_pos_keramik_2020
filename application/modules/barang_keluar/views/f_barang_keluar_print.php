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
            <p><b><?php echo $head->NomorBarangKeluar ?></b></p>
            <table class="customers_ts">
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><?php echo date("d F Y",strtotime($head->Tanggal)) ?></td>

                    <td style="min-width: 150px;">&nbsp;</td>

                    <td>Jenis Jual</td>
                    <td>:</td>
                    <td><?php echo $head->Jenis_Jual ?></td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td>:</td>
                    <td><?php echo $head->NamaCustomer ?></td>

                    <td style="min-width: 150px;">&nbsp;</td>
                    <td>Ongkir</td>
                    <td>:</td>
                    <td><?php echo $head->nama_ongkir ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?php echo $head->Alamat ?></td>

                    <td style="min-width: 150px;">&nbsp;</td>
                    <td>Min Jumlah Order (dus)</td>
                    <td>:</td>
                    <td style="text-align: right;"><?php echo number_format($head->jumlah_min_order) ?></td>
                </tr>
                <tr>
                    <td>Jenis Keramik</td>
                    <td>:</td>
                    <td><?php echo ($head->Jenis=="MASUK") ? "NON REJECT" : "REJECT" ?></td>

                    <td style="min-width: 150px;">&nbsp;</td>
                    <td>Harga Ongkir</td>
                    <td>:</td>
                    <td style="text-align: right;"><?php echo number_format($head->harga_ongkir) ?></td>
                </tr>
            </table>
            <hr>
            <table class="customers" id="data_list" style="width : 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Diskon %</th>
                        <th>Harga Diskon</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <?php 
                $no         = 0;
                $jumlah_row = $list->num_rows();
                foreach ($list->result() as $data) {
                    $no++; 
                    @$sub_total        = $data->Qty*$data->Harga_Diskon;
                    @$total_harga     += $data->Harga_Diskon;
                    @$total_qty       += $data->Qty;
                    @$total_sub_total += $sub_total;
                    ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo $data->KodeBarang ?></td>
                        <td><?php echo $data->NamaBarang ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Harga_Jual) ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Diskon) ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Harga_Diskon) ?></td>
                        <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
                        <td style="text-align: right;"><?php echo number_format($sub_total) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;">Total</td>
                        <td style="text-align: right;"><span id="total_qty"><?php echo number_format($total_harga) ?></span></td>
                        <td style="text-align: right;"><?php echo number_format($total_qty)?></td>
                        <td style="text-align: right;"><?php echo number_format($total_sub_total)?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Ongkir</td>
                        <td colspan="2"></td>
                        <td style="text-align: right;"><?php echo number_format($head->Total_Ongkir) ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Keseluruhan</td>
                        <td colspan="2"></td>
                        <td style="text-align: right;"><?php echo number_format($total_sub_total+$head->Total_Ongkir) ?></td>
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