<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=barang_masuk.xls");
?>
<table class="customers" id="data_list" style="width : 150%;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Alamat</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Sub Total</th>
        </tr>
    </thead>
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
            <td><?php echo $data->NomorBarangMasuk ?></td>
            <td><?php echo $data->Tanggal ?></td>
            <td><?php echo $data->Nama_supplier ?></td>
            <td><?php echo $data->Alamat ?></td>
            <td><?php echo $data->KodeBarang ?></td>
            <td><?php echo $data->NamaBarang ?></td>
            <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
            <td style="text-align: right;"><?php echo number_format($data->Harga) ?></td>
            <td style="text-align: right;"><?php echo number_format($sub_total) ?></td>
        </tr>
    <?php } ?>
</table>