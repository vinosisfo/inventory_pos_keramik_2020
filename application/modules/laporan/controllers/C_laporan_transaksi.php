<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_laporan_transaksi extends MX_Controller  {
	
	public function __construct() 
	{
		parent::__construct();
		// $this->load->library("fpdf_general");
        $this->load->model("m_kode"); 
        if(empty($this->session->userdata("kode_pegawai")))
        {
            redirect(base_url('Auth'));
        }
	}

	public function index()
	{	
		$this->load->view('template/css/css_2');
        $this->load->view('template/css/css_table');
        $this->load->view('template/menu_bar/top_bar_2');
        $this->load->view('template/menu_bar/side_bar_2');
        $this->load->view("template/js/js_2");

        $sql = $this->db->query("SELECT DISTINCT A.Kodebarang,A.NamaBarang FROM barang A WHERE A.Aktif=1 ORDER BY A.NamaBarang");
        
        $data["barang"] = $sql;
        $this->load->view("f_format_laporan_transaksi",$data);

    }
    
    function get_view()
    {
        $format = $this->input->post("format");
        if($format=="barang_masuk"){ 
            $this->lap_barang_masuk();
        }
        else if($format=="barang_keluar"){
            $this->lap_barang_keluar();
        } else if($format=="barang_retur"){
            $this->lap_barang_retur();
        } else if($format=="rekap_transaksi"){
            $this->lap_rekap_transaksi();
        } else if($format=="laba"){
            $this->lap_laba();
        }
    }

    function get_view_export()
    {
        $format = $this->input->post("format");
        if($format=="barang_masuk"){ 
            $this->lap_barang_masuk('export');
        }
        else if($format=="barang_keluar"){
            $this->lap_barang_keluar("export");
        } else if($format=="barang_retur"){
            $this->lap_barang_retur("export");
        } else if($format=="rekap_transaksi"){
            $this->lap_rekap_transaksi("export");
        } else if($format=="laba"){
            $this->lap_laba("export");
        }
    }

    function lap_laba($export='')
    {
        $format     = $this->input->post("format");
        $date1      = $this->input->post("date1");
        $date2      = $this->input->post("date2");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["date1"]      = $date1;
        $data["date2"]      = $date2;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND B.KodeBarang='$barang'";
        $where_jenis  = (empty($jenis_stok)) ? "" : " AND A.Jenis='$jenis_stok'";

        $sql = $this->db->query("SELECT A2.*,(A2.Harga_Diskon-A2.Harga_Terakhir) Laba FROM (
                                    SELECT A1.Jenis,A1.Tanggal,A1.KodeBarang,A1.NamaBarang,
                                    SUM(A1.Qty) Qty,SUM(IFNULL(A1.QTY_RETUR,0)) QTY_RETUR,
                                    AVG(A1.Harga_Terakhir) Harga_Terakhir,AVG(A1.Keuntungan_Persen) Keuntungan_Persen,
                                    AVG(A1.Harga_Jual) Harga_Jual,AVG(A1.Diskon) Diskon
                                    ,AVG(A1.Harga_Jual)-((AVG(A1.Harga_Jual)* CASE WHEN AVG(A1.Diskon) > 0 THEN AVG(A1.dISKON) ELSE 0 END)/100) Harga_Diskon
                                    FROM (
                                        SELECT DISTINCT A.NomorBarangKeluar,A.Tanggal,A.Jenis,A.id_customer,D.NamaCustomer,B.KodeBarang,C.NamaBarang,
                                        B.Qty,(SELECT SUM(Y.Qty) FROM retur X INNER JOIN retur_detail Y ON Y.NomorRetur=X.NomorRetur 
                                                        WHERE X.NomorBarangKeluar=A.NomorBarangKeluar AND Y.KodeBarang=B.KodeBarang) QTY_RETUR,
                                        B.Harga_Terakhir,B.Keuntungan_Persen,B.Harga_Jual,B.Diskon,B.Harga_Diskon 
                                        FROM barang_keluar A 
                                        INNER JOIN barang_keluar_detail B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                                        LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                        LEFT JOIN customer D ON D.id_customer=A.id_customer
                                        WHERE 1=1
                                        AND A.Tanggal BETWEEN '$date1' AND '$date2'
                                        $where_barang
                                        $where_jenis
                                    ) A1
                                    GROUP BY A1.Jenis,A1.Tanggal,A1.KodeBarang,A1.NamaBarang
                                ) A2
                                ORDER BY A2.Jenis,A2.NamaBarang,A2.Tanggal");
        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_lap_laba_xls",$data);
        } else {
            $this->load->view("f_lap_laba",$data);
        }
    }

    function lap_rekap_transaksi($export='')
    {
        $format     = $this->input->post("format");
        $date1      = $this->input->post("date1");
        $date2      = $this->input->post("date2");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["date1"]      = $date1;
        $data["date2"]      = $date2;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND A1.KodeBarang='$barang'";
        // CONCAT(LEFT(A.Tanggal, 7), '-01') Tanggal
        $sql = $this->db->query("SELECT A2.Tanggal,A2.KodeBarang,A2.NamaBarang,SUM(A2.Qty_Masuk) Qty_Masuk,SUM(A2.Qty_keluar) Qty_Keluar,SUM(A2.Qty_REtur) Qty_Retur 
                                FROM (
                                    SELECT A1.Tanggal,A1.KodeBarang,A1.NamaBarang,
                                    (CASE WHEN A1.Jenis='Barang_Masuk' THEN (A1.Qty) ELSE 0 END) Qty_Masuk,
                                    (CASE WHEN A1.Jenis='Barang_Keluar' THEN (A1.Qty) ELSE 0 END) Qty_keluar,
                                    (CASE WHEN A1.Jenis='Barang_Retur' THEN (A1.Qty) ELSE 0 END) Qty_Retur
                                    FROM (
                                        SELECT 'Barang_Masuk' Jenis,A.Tanggal,B.KodeBarang,C.NamaBarang,SUM(B.Qty) Qty
                                        FROM barang_masuk A 
                                        INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                                        LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                        WHERE 1=1 AND A.Tanggal BETWEEN '$date1' AND '$date2'
                                        GROUP BY A.Tanggal,B.KodeBarang,C.NamaBarang
                                        UNION ALL
                                        SELECT 'Barang_Keluar' Jenis, A.Tanggal,B.KodeBarang,C.NamaBarang,SUM(B.Qty) Qty
                                        FROM barang_keluar A 
                                        INNER JOIN barang_keluar_detail B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                                        LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                        WHERE 1=1
                                        AND A.Tanggal BETWEEN '$date1' AND '$date2'
                                        GROUP BY  A.Tanggal,B.KodeBarang,C.NamaBarang
                                        UNION ALL
                                        SELECT 'Barang_retur' Jenis,(A.Tanggal) Tanggal,B.KodeBarang,C.NamaBarang,sum(B.Qty) Qty FROM retur A
                                        INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                                        INNER JOIN barang C ON C.Kodebarang=B.KodeBarang
                                        WHERE 1=1 AND A.Tanggal BETWEEN '$date1' AND '$date2'
                                        GROUP BY B.KodeBarang,C.NamaBarang,Tanggal
                                    ) A1 WHERE 1=1 $where_barang
                                    GROUP BY A1.Tanggal,A1.KodeBarang,A1.NamaBarang,A1.Qty
                                ) A2 GROUP BY A2.Tanggal,A2.KodeBarang,A2.NamaBarang
                                ORDER BY A2.NamaBarang,A2.Tanggal");
        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_lap_rekap_transaksi_xls",$data);
        } else {
            $this->load->view("f_lap_rekap_transaksi",$data);
        }
    }

    function lap_barang_retur($export=''){
        $format     = $this->input->post("format");
        $date1      = $this->input->post("date1");
        $date2      = $this->input->post("date2");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["date1"]      = $date1;
        $data["date2"]      = $date2;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND B.KodeBarang='$barang'";
        $where_jenis  = (empty($jenis_stok)) ? "" : " AND D.Jenis='$jenis_stok'";

        $sql = $this->db->query("SELECT MAX(A.Tanggal) Tanggal,A.NomorBarangKeluar,D.id_customer,E.NamaCustomer,E.Alamat,D.Jenis
                                ,B.KodeBarang,F.NamaBarang,C.Qty,SUM(B.Qty) QTY_SUDAH_RETUR,(C.Qty-SUM(B.Qty))+SUM(B.Qty) QTY_BEBAS
                                FROM retur A
                                INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                                INNER JOIN barang_keluar_detail C ON C.NomorBarangKeluar=A.NomorBarangKeluar AND C.KodeBarang=B.KodeBarang
                                INNER JOIN barang_keluar D ON D.NomorBarangKeluar=C.NomorBarangKeluar
                                LEFT JOIN customer E ON E.id_customer=D.id_customer
                                LEFT JOIN barang F ON F.Kodebarang=B.KodeBarang
                                WHERE 1=1
                                AND A.Tanggal BETWEEN '$date1' AND '$date2'
                                $where_barang
                                $where_jenis
                                GROUP BY A.NomorBarangKeluar,D.id_customer,E.NamaCustomer,E.Alamat,D.Jenis
                                ,B.KodeBarang,F.NamaBarang
                                ORDER BY A.NomorBarangKeluar,E.NamaCustomer,MAX(A.Tanggal),F.NamaBarang");

        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_lap_barang_retur_xls",$data);
        } else {
            $this->load->view("f_lap_barang_retur",$data);
        }
    }

    function lap_barang_keluar($export='')
    {
        $format     = $this->input->post("format");
        $date1      = $this->input->post("date1");
        $date2      = $this->input->post("date2");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["date1"]      = $date1;
        $data["date2"]      = $date2;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND B.KodeBarang='$barang'";
        $where_jenis  = (empty($jenis_stok)) ? "" : " AND A.Jenis='$jenis_stok'";

        $sql = $this->db->query("SELECT DISTINCT A.NomorBarangKeluar,A.Tanggal,A.Jenis,A.id_customer,D.NamaCustomer,B.KodeBarang,C.NamaBarang,
                                B.Qty,B.Harga_Terakhir,B.Keuntungan_Persen,B.Harga_Jual,B.Diskon,B.Harga_Diskon FROM barang_keluar A 
                                INNER JOIN barang_keluar_detail B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                                LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                LEFT JOIN customer D ON D.id_customer=A.id_customer
                                WHERE 1=1
                                AND A.Tanggal BETWEEN '$date1' and '$date2'
                                $where_barang
                                $where_jenis
                                ORDER BY A.Jenis,C.NamaBarang,D.NamaCustomer,A.Tanggal");

        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_lap_barang_keluar_xls",$data);
        } else {
            $this->load->view("f_lap_barang_keluar",$data);
        }
    }

    function lap_barang_masuk($export='')
    {
        $format     = $this->input->post("format");
        $date1      = $this->input->post("date1");
        $date2      = $this->input->post("date2");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["date1"]      = $date1;
        $data["date2"]      = $date2;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND B.KodeBarang='$barang'";


        $sql = $this->db->query("SELECT A.Tanggal,B.KodeBarang,C.NamaBarang,A.id_supplier,D.Nama_supplier,SUM(B.Qty) Qty,SUM(B.Harga) Harga 
                                FROM barang_masuk A 
                                INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                                LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                LEFT JOIN supplier D ON D.id_supplier=A.id_supplier
                                WHERE 1=1 AND A.Tanggal BETWEEN '$date1' AND '$date2'
                                $where_barang
                                GROUP BY A.Tanggal,B.KodeBarang,C.NamaBarang,A.id_supplier,D.Nama_supplier
                                ORDER BY D.Nama_supplier,C.NamaBarang,A.Tanggal");
        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_lap_barang_masuk_xls",$data);
        } else {
            $this->load->view("f_lap_barang_masuk",$data);
        }
        
    }

	
	
}
