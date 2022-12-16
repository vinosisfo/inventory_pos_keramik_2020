<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_laporan_barang extends MX_Controller  {
	
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
        $this->load->view("f_format_laporan",$data);

    }
    
    function get_view()
    {
        $format = $this->input->post("format");
        if($format=="list_harga"){ 
            $this->lap_list_harga();
        }
        else if($format=="stok_barang"){
            $this->lap_list_stok();
        }
    }

    function get_view_export()
    {
        $format = $this->input->post("format");
        if($format=="list_harga"){ 
            $this->lap_list_harga('export');
        }
        else if($format=="stok_barang"){
            $this->lap_list_stok("export");
        }
    }

    function lap_list_stok($export='')
    {
        $format     = $this->input->post("format");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND A.KodeBarang='$barang'";
        $where_jenis  = (empty($jenis_stok)) ? "" : " AND B.Jenis='$jenis_stok'";

        $sql = $this->db->query("SELECT A.Kodebarang,A.NamaBarang,B.Jenis,B.Qty_Stok 
                                FROM barang A 
                                INNER JOIN stok_barang B ON B.KodeBarang=A.Kodebarang
                                WHERE A.Aktif=1
                                $where_barang
                                $where_jenis
                                ORDER BY B.Jenis,A.NamaBarang");

        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_list_stok_xls",$data);
        } else {
            $this->load->view("f_list_stok",$data);
        }
    }

    function lap_list_harga($export='')
    {
        $format     = $this->input->post("format");
        $barang     = $this->input->post("barang");
        $jenis_stok = $this->input->post("jenis_stok");

        $data["format"]     = $format;
        $data["barang"]     = $barang;
        $data["jenis_stok"] = $jenis_stok;

        $where_barang = (empty($barang)) ? "" : " AND A.KodeBarang='$barang'";

        $sql = $this->db->query("SELECT A1.*,((A1.Harga_Terakhir*(CASE WHEN A1.Keuntungan_Persen > 0 THEN A1.Keuntungan_Persen ELSE 1 END))/100)+A1.Harga_Terakhir Harga_Jual FROM (
                                    SELECT A.Kodebarang,A.NamaBarang,A.Diskon,A.Keuntungan_Persen,A.Diskon_Reject,
                                    IFNULL((SELECT X.Harga FROM barang_masuk_detail X 
                                        INNER JOIN barang_masuk Y ON Y.NomorBarangMasuk=X.NomorBarangMasuk
                                        WHERE X.KodeBarang=A.KodeBarang
                                        ORDER BY Y.Tanggal DESC LIMIT 1),0) Harga_Terakhir
                                    FROM barang A
                                    WHERE A.Aktif=1
                                    $where_barang
                                    ) A1
                                    ORDER BY A1.NamaBarang");
        $data["list"] = $sql;
        if($export=="export"){
            $this->load->view("f_list_harga_barang_xls",$data);
        } else {
            $this->load->view("f_list_harga_barang",$data);
        }
        
    }

	
	
}
