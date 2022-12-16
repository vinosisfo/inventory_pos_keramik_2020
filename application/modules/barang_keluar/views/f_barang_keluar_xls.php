<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=barang_keluar.xls");
?>
<table class="customers" id="data_list" style="width : 180%;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Alamat</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Diskon</th>
            <th>Harga Diskon</th>
            <th>Qty</th>
            <th>Sub Total</th>
        </tr>
    </thead>
    <?php 
    $no =0;
    foreach ($list->result() as $data) {
        $no++; 
        @$sub_total = $data->Qty*$data->Harga_Diskon;
        ?>
        <tr>
            <td><?php echo $no ?></td>
            <td><?php echo $data->NomorBarangKeluar ?></td>
            <td><?php echo $data->Tanggal ?></td>
            <td><?php echo $data->NamaCustomer ?></td>
            <td><?php echo $data->Alamat ?></td>
            <td><?php echo $data->KodeBarang ?></td>
            <td><?php echo $data->NamaBarang ?></td>
            <td style="text-align: right;"><?php echo number_format($data->Harga_Jual) ?></td>
            <td style="text-align: right;"><?php echo number_format($data->Diskon) ?></td>
            <td style="text-align: right;"><?php echo number_format($data->Harga_Diskon) ?></td>
            <td style="text-align: right;"><?php echo number_format($data->Qty) ?></td>
            <td style="text-align: right;"><?php echo number_format($sub_total) ?></td>
        </tr>
    <?php } ?>
</table>