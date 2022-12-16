<?php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=barang_retur.xls");
?>
<table class="customers" id="data_list" style="width : 150%;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Nomor Keluar</th>
            <th>Customer</th>
            <th>Alamat</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty Keluar</th>
            <th>Qty Retur</th>
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
            <td><?php echo $data->NomorRetur ?></td>
            <td><?php echo $data->Tanggal ?></td>
            <td><?php echo $data->NomorBarangKeluar ?></td>
            <td><?php echo $data->NamaCustomer ?></td>
            <td><?php echo $data->Alamat ?></td>
            <td><?php echo $data->KodeBarang ?></td>
            <td><?php echo $data->NamaBarang ?></td>
            <td style="text-align: right;"><?php echo number_format($data->QTY_KELUAR) ?></td>
            <td style="text-align: right;"><?php echo number_format($data->QTY_RETUR) ?></td>
        </tr>
    <?php } ?>
</table>