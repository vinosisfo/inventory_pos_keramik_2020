<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_barang_keluar extends MX_Controller  {
	
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
        $this->load->view("f_barang_keluar");

	}

	function get_data()
    {
        $list = $this->get_list();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();

            $row  [] = $no;
            $row  [] = '<button type="button" class="btn btn-primary btn-xs" onclick="edit_data(\''.$field->NomorBarangKeluar.'\')">Edit</button>';
            $row  [] = '<img src="'.base_url().'assets/adminbsb/images/print.jpg" class="img-responsive" style="width : 40px; height : auto;" onclick="print_data(\''.$field->NomorBarangKeluar.'\')" title="Print">';
            $row  [] = $field->NomorBarangKeluar;
            $row  [] = $field->Tanggal;
            $row  [] = $field->NamaCustomer;
            $row  [] = $field->Alamat;
            $row  [] = $field->KodeBarang;
            $row  [] = $field->NamaBarang;
            $row  [] = number_format($field->Harga_Jual);
            $row  [] = number_format($field->Diskon_set);
            $row  [] = number_format($field->Qty);
            $row  [] = number_format($field->Harga_Diskon);
            $row  [] = date("Y-m-d",strtotime($field->Tglinput));
            $row  [] = $field->username;
            $data[]  = $row;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    function get_form_print($kode)
    {
        $sql = $this->db->query("SELECT DISTINCT A.NomorBarangKeluar,A.id_customer,D.NamaCustomer,D.Alamat,A.Tanggal,A.Jenis,
                                A.Jenis_Jual,A.id_ongkir,F.nama_ongkir,A.jumlah_min_order,A.harga_ongkir,A.Total_Ongkir,
                                B.KodeBarang,C.NamaBarang,B.Qty,B.Harga_Terakhir,B.Keuntungan_Persen,B.Harga_Jual,B.Diskon,B.Harga_Diskon 
                                ,E.Qty_Stok,(E.Qty_Stok+B.Qty) qty_stok_set
                                FROM barang_keluar A 
                                INNER JOIN barang_keluar_detail B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                                LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                LEFT JOIN customer D ON D.id_customer=A.id_customer
                                LEFT JOIN stok_barang E ON E.KodeBarang=B.KodeBarang AND E.Jenis=A.Jenis
                                LEFT JOIN ongkir F ON F.id_ongkir=A.id_ongkir
                                WHERE A.NomorBarangKeluar='$kode'
                                ORDER BY C.NamaBarang");
        $data["list"] = $sql;
        $this->load->view("f_barang_keluar_print",$data);
    }

    function export_xls(){
        $kode_pegawai = $this->session->userdata("kode_pegawai");
        $akses_admin  = $this->session->userdata("akses_admin");
        $customer     = $this->input->post("customer_src");
        $barang       = $this->input->post("barang_src");
        $jenis_stok   = $this->input->post("jenis_stok_src");
        $date1        = $this->input->post("date1");
        $date2        = $this->input->post("date2");
        
        $where_customer   = (empty($customer)) ? "" : " AND D.NamaCustomer LIKE '%$customer%'";
        $where_barang     = (empty($barang)) ? "" : " AND (B.KodeBarang LIKE '%$barang%' OR C.NamaBarang LIKE '%$barang%')";
        $where_jenis_stok = (empty($jenis_stok)) ? "" : " AND A.Jenis='$jenis_stok'";


        $sql = $this->db->query("SELECT * FROM (SELECT DISTINCT A.NomorBarangKeluar,A.Tanggal,A.id_customer,D.NamaCustomer,D.Alamat,(CASE WHEN A.Jenis='MASUK' THEN 'Non Reject' ELSE 'Reject' END) Jenis,A.Tglinput,E.username
                    ,B.KodeBarang,C.NamaBarang,B.Qty,B.Harga_Terakhir,B.Keuntungan_Persen,B.Harga_Jual,B.Diskon AS DISKON_ALL,C.Diskon,C.Diskon_Reject,B.Harga_Diskon,
                    (CASE WHEN A.Jenis='MASUK' THEN (C.Diskon) ELSE (C.Diskon+C.Diskon_Reject) END) Diskon_set
                    FROM barang_keluar A 
                    INNER JOIN barang_keluar_detail  B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                    LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                    LEFT JOIN customer D ON D.id_customer=A.id_customer
                    LEFT JOIN user E ON E.kode_pegawai=A.UserInput
                    WHERE 1=1
                    AND A.Tanggal BETWEEN '$date1' AND '$date2'
                    $where_customer
                    $where_barang
                    $where_jenis_stok
                ) A1
                ORDER BY A1.Tanggal DESC, A1.NamaCustomer, A1.NamaBarang");
        $data["list"] = $sql;
        $this->load->view("f_barang_keluar_xls",$data);
    }

    function get_list()
    {
        $this->query_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function query_data()
    {
        $kode_pegawai = $this->session->userdata("kode_pegawai");
        $akses_admin  = $this->session->userdata("akses_admin");
        $customer     = $this->input->post("customer");
        $barang       = $this->input->post("barang");
        $jenis_stok   = $this->input->post("jenis_stok");
        $date1        = $this->input->post("date1");
        $date2        = $this->input->post("date2");
        
        $where_customer   = (empty($customer)) ? "" : " AND D.NamaCustomer LIKE '%$customer%'";
        $where_barang     = (empty($barang)) ? "" : " AND (B.KodeBarang LIKE '%$barang%' OR C.NamaBarang LIKE '%$barang%')";
        $where_jenis_stok = (empty($jenis_stok)) ? "" : " AND A.Jenis='$jenis_stok'";


        $sql = "(SELECT DISTINCT A.NomorBarangKeluar,A.Tanggal,A.id_customer,D.NamaCustomer,D.Alamat,(CASE WHEN A.Jenis='MASUK' THEN 'Non Reject' ELSE 'Reject' END) Jenis,A.Tglinput,E.username
                    ,B.KodeBarang,C.NamaBarang,B.Qty,B.Harga_Terakhir,B.Keuntungan_Persen,B.Harga_Jual,B.Diskon AS DISKON_ALL,C.Diskon,C.Diskon_Reject,B.Harga_Diskon,
                    (CASE WHEN A.Jenis='MASUK' THEN (C.Diskon) ELSE (C.Diskon+C.Diskon_Reject) END) Diskon_set
                    FROM barang_keluar A 
                    INNER JOIN barang_keluar_detail  B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                    LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                    LEFT JOIN customer D ON D.id_customer=A.id_customer
                    LEFT JOIN user E ON E.kode_pegawai=A.UserInput
                    WHERE 1=1
                    AND A.Tanggal BETWEEN '$date1' AND '$date2'
                    $where_customer
                    $where_barang
                    $where_jenis_stok
                ) A1
                ORDER BY A1.Tanggal DESC, A1.NamaCustomer, A1.NamaBarang";
        $this->db->from($sql);
    }

    function count_filtered()
    {
        $this->query_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $kode_pegawai = $this->session->userdata("kode_pegawai");
        $akses_admin  = $this->session->userdata("akses_admin");

        $sql = "(SELECT DISTINCT A.NomorBarangKeluar,A.Jenis,B.KodeBarang
                FROM barang_keluar A 
                    INNER JOIN barang_keluar_detail B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                ) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");

        $customer = $this->db->query("SELECT A.id_customer,A.NamaCustomer 
                                    FROM customer A WHERE A.Aktif=1
                                    ORDER BY A.NamaCustomer");

        $ongkir = $this->db->query("SELECT A.id_ongkir,A.nama_ongkir 
                                    FROM ongkir A WHERE A.Aktif=1");

        $data_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang 
                                        FROM barang A WHERE A.Aktif=1
                                        ORDER BY A.NamaBarang");
        $data["format"]   = $format;
        $data["customer"] = $customer;
        $data["ongkir"]   = $ongkir;
        $data["barang"]   = $data_barang;
        $this->load->view("f_input_barang_keluar",$data);
    }

    function get_barang_kode()
    {
        $data        = json_decode(file_get_contents('php://input'), true);
        @$term       = $data["term"];
        $kode_barang = $data["kode_barang"];
        $jenis       = $data["jenis"];

        // var_dump($data);die();

        $sql = "(SELECT A.KodeBarang,B.NamaBarang,A.Qty_Stok FROM stok_barang A 
                INNER JOIN barang B ON B.KodeBarang=A.KodeBarang
                WHERE 1=1 AND A.Qty_Stok > 0 AND A.Jenis='$jenis'
                AND (B.KodeBarang LIKE '%$term%')) A1";
        $sql_set = $this->db->from($sql)
                            ->where_not_in('A1.KodeBarang',$kode_barang)
                            ->order_by("A1.KodeBarang")
                            ->get();
        
        $opsi = [];
        foreach ($sql_set->result() as $data) {
            $opsi[]=["id" => $data->KodeBarang, "text" => $data->KodeBarang];
        }
        echo json_encode($opsi);
    }

    function get_barang_nama()
    {
        $data        = json_decode(file_get_contents('php://input'), true);
        @$term       = $data["term"];
        $kode_barang = $data["kode_barang"];
        $jenis       = $data["jenis"];

        $sql = "(SELECT A.KodeBarang,B.NamaBarang,A.Qty_Stok FROM stok_barang A 
                INNER JOIN barang B ON B.KodeBarang=A.KodeBarang
                WHERE 1=1 AND A.Qty_Stok > 0 AND A.Jenis='$jenis'
                AND (B.KodeBarang LIKE '%$term%')) A1";
        $sql_set = $this->db->from($sql)
                            ->where_not_in('A1.KodeBarang',$kode_barang)
                            ->order_by("A1.KodeBarang")
                            ->get();
        
        $opsi = [];
        foreach ($sql_set->result() as $data) {
            $opsi[]=["id" => $data->KodeBarang, "text" => $data->NamaBarang];
        }
        echo json_encode($opsi);
    }

    function get_detail_barang() 
    {
        $kode_barang = $this->input->post("kode_barang");
        $jenis       = $this->input->post("jenis");
        $jenis_stok  = $this->input->post("jenis_stok");
        $jenis_jual  = $this->input->post("jenis_jual");
        $qty_lama    = $this->input->post("qty_lama");

        $diskon_set = ($jenis_stok=="MASUK") ? (($jenis_jual=="pemakai") ? " A2.Diskon" : " A2.Diskon_Jual") : (($jenis_jual=="pemakai") ? " (A2.Diskon+Diskon_Reject)" : " (A2.Diskon_Jual+Diskon_Reject)");

        $sql = $this->db->query("SELECT A2.*,(CASE WHEN $diskon_set > 0 THEN (A2.Harga_Jual-(A2.Harga_Jual*$diskon_set)/100) 
                                        ELSE A2.Harga_Jual END) Harga_Diskon
                                FROM(
                                    SELECT A1.*,(CASE WHEN A1.Keuntungan_Persen > 0 THEN ((A1.Harga_Terakhir*A1.Keuntungan_Persen)/100)+A1.Harga_Terakhir
                                                ELSE A1.Harga_Terakhir END) Harga_Jual FROM (
                                    SELECT A.Kodebarang,A.NamaBarang,A.Deskripsi,A.Diskon,A.Diskon_Jual,A.Diskon_Reject,A.Keuntungan_Persen,B.Qty_Stok
                                    ,(SELECT Y.Harga FROM barang_masuk X INNER JOIN barang_masuk_detail Y ON Y.NomorBarangMasuk=X.NomorBarangMasuk 
                                    WHERE Y.KodeBarang=A.KodeBarang ORDER BY X.Tanggal DESC LIMIT 1) AS Harga_Terakhir
                                    FROM barang A 
                                    INNER JOIN stok_barang B ON B.KodeBarang=A.KodeBarang
                                    WHERE A.Aktif=1 AND A.KodeBarang='$kode_barang'
                                    AND B.Jenis='$jenis_stok'
                                    ) A1
                                ) A2");
                                
        $id             = $sql->row()->Kodebarang;
        $text           = ($jenis=="kode") ? ($sql->row()->NamaBarang) : ($sql->row()->Kodebarang);
        $harga_terakhir = $sql->row()->Harga_Terakhir;
        $untung_persen  = $sql->row()->Keuntungan_Persen;
        $harga          = $sql->row()->Harga_Jual;
        $diskon         = ($jenis_stok=="MASUK") ? (($jenis_jual=="pemakai") ? ($sql->row()->Diskon) : ($sql->row()->Diskon_Jual)) : (($jenis_jual=="pemakai") ? ($sql->row()->Diskon+$sql->row()->Diskon_Reject) : ($sql->row()->Diskon_Jual+$sql->row()->Diskon_Reject));
        $harga_diskon   = $sql->row()->Harga_Diskon;
        $qty_lama_set   = (empty($qty_lama)) ? (0) : (str_replace(',','',$qty_lama));
        $qty_stok       = $sql->row()->Qty_Stok+$qty_lama_set;

        $sql_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang,Deskripsi
                                        FROM barang A WHERE A.Aktif=1 AND A.Kodebarang<>'$id'
                                        ORDER BY A.NamaBarang");

        $opsi = '<option value="'.$id.'">'.$text.'</option>';
        $opsi .= '<option value="">Pilih</option>';
        foreach ($sql_barang->result() as $data) {
            $text_all = ($jenis=="kode") ? ($data->NamaBarang) : ($data->Kodebarang);
            $opsi .= '<option value="'.$data->Kodebarang.'">'.$text_all.'</option>';
        }
        
        $hasil["harga_terakhir"] = number_format($harga_terakhir);
        $hasil["untung_persen"]  = number_format($untung_persen);
        $hasil["harga_jual"]     = number_format($harga);
        $hasil["diskon"]         = number_format($diskon);
        $hasil["harga_diskon"]   = number_format($harga_diskon);
        $hasil["qty_stok"]       = number_format($qty_stok);
        $hasil["barang"]         = $opsi;
        echo json_encode($hasil);
    }

    function set_sub_total(){
        $qty_baris        = $this->input->post("qty_baris");
        $qty_stok_baris   = $this->input->post("qty_stok_baris");
        $harga_baris      = $this->input->post("harga_baris");
        $jumlah_min_order = $this->input->post("jumlah_min_order");
        $harga_ongkir     = $this->input->post("harga_ongkir");

        $qty_baris_set        = str_replace(',','',$qty_baris);
        $qty_stok_baris_set   = str_replace(',','',$qty_stok_baris);
        $harga_baris_set      = str_replace(',','',$harga_baris);
        $jumlah_min_order_set = str_replace(',','',$jumlah_min_order);
        $harga_ongkir_set     = str_replace(',','',empty($harga_ongkir) ? 0 : ($harga_ongkir));

        $qty          = $this->input->post("Qty");
        $harga_barang = $this->input->post("harga_diskon");

        $total_qty       = 0;
        $total_harga     = 0;
        $total_sub_total = 0;
        foreach ($qty as $key => $value) {
            $qty_set   = str_replace(',','',$qty[$key]);
            $harga_set = str_replace(',','',$harga_barang[$key]);

            $total_qty       += ($qty_set > 0) ? ($qty_set) : 0;
            $total_harga     += ($harga_set > 0) ? ($harga_set) : 0;
            $total_sub_total += (($qty_set > 0) AND ($harga_set > 0)) ? ($qty_set*$harga_set) : 0;
        }

        $sub_total_baris = (($qty_baris_set > 0) AND ($harga_baris_set > 0)) ? ($qty_baris_set*$harga_baris_set) : 0;
        $status_qty      = ($qty_baris_set > $qty_stok_baris) ? "lebih" : "ok";
        $total_ongkir    = ($jumlah_min_order_set > 0) ? (($total_qty >=$jumlah_min_order_set) ? 0 : ($harga_ongkir_set)) : ($harga_ongkir_set*$total_qty);

        $data["status_qty"]      = $status_qty;
        $data["sub_total"]       = number_format($sub_total_baris);
        $data["total_qty"]       = number_format($total_qty);
        $data["total_harga"]     = number_format($total_harga);
        $data["total_sub_total"] = number_format($total_sub_total);
        $data["total_ongkir"]    = number_format($total_ongkir);
        $data["total_all"]       = number_format($total_sub_total+$total_ongkir);

        echo json_encode($data);
    }

    function get_duplikat()
    {
        $kode_barang = $this->input->post("kode_barang");
        foreach ($kode_barang as $key => $value) {
            if(!empty($kode_barang[$key]))
            {
                $cek_data[] = strtoupper($kode_barang[$key]);
            }
        }
        $get_data = array_diff_key($cek_data, array_unique($cek_data));
        $hasil["hasil"] = (empty($get_data)) ? "ok" : "ada";
        echo json_encode($hasil);

    }

    function simpan_data()
    {
        $kode_barang_keluar = $this->m_kode->kode_barang_keluar();
        $kode_pegawai       = $this->session->userdata("kode_pegawai");
        $tanggal            = $this->input->post("tanggal");
        $customer           = $this->input->post("customer");
        $jenis_stok         = $this->input->post("jenis_stok");
        $jenis_jual         = $this->input->post("jenis_jual");
        $ongkir             = $this->input->post("ongkir");
        $jumlah_min_order   = $this->input->post("jumlah_min_order");
        $harga_ongkir       = $this->input->post("harga_ongkir");
        $total_sub_total    = $this->input->post("total_sub_total");
        $total_ongkir       = $this->input->post("total_ongkir");

        $kode_barang    = $this->input->post("kode_barang");
        $qty            = $this->input->post("Qty");
        $harga_terakhir = $this->input->post("harga_terakhir");
        $untung_persen  = $this->input->post("untung_persen");
        $harga_barang   = $this->input->post("harga_barang");
        $diskon         = $this->input->post("diskon");
        $harga_diskon   = $this->input->post("harga_diskon");
        

        $this->db->trans_start();
        $data =[
                "NomorBarangKeluar" => $kode_barang_keluar,
                "Tanggal"           => $tanggal,
                "id_customer"       => $customer,
                "Jenis"             => $jenis_stok,
                "UserInput"         => $kode_pegawai,
                "TglInput"          => date("Y-m-d H:i:s"),
                "Jenis_Jual"        => $jenis_jual,
                "id_ongkir"         => $ongkir,
                "jumlah_min_order"  => str_replace(',','',$jumlah_min_order),
                "harga_ongkir"      => str_replace(',','',$harga_ongkir),
                "Total_Harga"       => str_replace(',','',$total_sub_total),
                "Total_Ongkir"      => str_replace(',','',$total_ongkir),
                ];
        $simpan_data = $this->db->insert("barang_keluar",$data);

        foreach ($kode_barang as $key => $value) {
            $qty_set            = str_replace(',','',$qty[$key]);
            $harga_terakhir_set = str_replace(',','',$harga_terakhir[$key]);
            $untung_persesn_set = str_replace(',','',$untung_persen[$key]);
            $harga_set          = str_replace(',','',$harga_barang[$key]);
            $diskon_set         = str_replace(',','',$diskon[$key]);
            $harga_diskon_set   = str_replace(',','',$harga_diskon[$key]);

            $detail = $this->db->query("INSERT INTO barang_keluar_detail(NomorBarangkeluar,KodeBarang,Qty,Harga_Terakhir,Keuntungan_Persen,Harga_Jual,Diskon,Harga_Diskon)
                                        VALUES('$kode_barang_keluar','$kode_barang[$key]','$qty_set','$harga_terakhir_set','$untung_persesn_set','$harga_set','$diskon_set','$harga_diskon_set')");

            $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok-$qty_set 
                                            WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='$jenis_stok'");            
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT DISTINCT A.NomorBarangKeluar,A.id_customer,D.NamaCustomer,D.Alamat,A.Tanggal,A.Jenis,
                                A.Jenis_Jual,A.id_ongkir,F.nama_ongkir,A.jumlah_min_order,A.harga_ongkir,A.Total_Ongkir,
                                B.KodeBarang,C.NamaBarang,B.Qty,B.Harga_Terakhir,B.Keuntungan_Persen,B.Harga_Jual,B.Diskon,B.Harga_Diskon 
                                ,E.Qty_Stok,(E.Qty_Stok+B.Qty) qty_stok_set
                                FROM barang_keluar A 
                                INNER JOIN barang_keluar_detail B ON B.NomorBarangKeluar=A.NomorBarangKeluar
                                LEFT JOIN barang C ON C.Kodebarang=B.KodeBarang
                                LEFT JOIN customer D ON D.id_customer=A.id_customer
                                LEFT JOIN stok_barang E ON E.KodeBarang=B.KodeBarang AND E.Jenis=A.Jenis
                                LEFT JOIN ongkir F ON F.id_ongkir=A.id_ongkir
                                WHERE A.NomorBarangKeluar='$kode'
                                ORDER BY C.NamaBarang");

        $customer = $this->db->query("SELECT A.id_customer,A.NamaCustomer 
                                    FROM customer A WHERE A.Aktif=1
                                    ORDER BY A.NamaCustomer");
        
        $ongkir = $this->db->query("SELECT A.id_ongkir,A.nama_ongkir 
                                    FROM ongkir A WHERE A.Aktif=1");

        $data_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang 
                                        FROM barang A WHERE A.Aktif=1
                                        ORDER BY A.NamaBarang");

        $data["list"]     = $sql;
        $data["customer"] = $customer;
        $data["ci"]       = $this;
        $data["barang"]   = $data_barang;
        $data["ongkir"]   = $ongkir;
        $this->load->view("f_edit_barang_keluar_new",$data);        
    }

    function get_barang_edit($kode_barang){
        $data_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang 
                                        FROM barang A WHERE A.Aktif=1 AND A.KodeBarang<>'$kode_barang'
                                        ORDER BY A.NamaBarang");
        return $data_barang;
    }

    function hapus_row()
    {
        $nomor       = $this->input->post("nomor");
        $kodebarang  = $this->input->post("kodebarang");
        $jumlah_data = $this->input->post("jumlah_row");
        $qty         = $this->input->post("qty");
        $qty_set     = str_replace(',','',$qty);

        $this->db->trans_start();
            $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok+$qty_set 
                                            WHERE A.KodeBarang='$kodebarang' AND A.Jenis='MASUK'");

            $hapus_detail = $this->db->query("DELETE FROM barang_keluar_detail WHERE KodeBarang='$kodebarang' AND NomorBarangkeluar='$nomor'");

            if($jumlah_data==1){
                $hapus_header = $this->db->query("DELETE FROM barang_keluar WHERE NomorBarangkeluar='$nomor'");
            }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function update_head_hapus_row()
    {
        $kode_barang_keluar = $this->input->post("nomor");
        $kode_pegawai       = $this->session->userdata("kode_pegawai");
        $tanggal            = $this->input->post("tanggal");
        $customer           = $this->input->post("customer");
        $jenis_stok         = $this->input->post("jenis_stok");
        $jenis_jual         = $this->input->post("jenis_jual");
        $ongkir             = $this->input->post("ongkir");
        $jumlah_min_order   = $this->input->post("jumlah_min_order");
        $harga_ongkir       = $this->input->post("harga_ongkir");
        $total_sub_total    = $this->input->post("total_sub_total");
        $total_ongkir       = $this->input->post("total_ongkir");

        $this->db->trans_start();
        $data =[
                "Tanggal"          => $tanggal,
                "id_customer"      => $customer,
                "Jenis"            => $jenis_stok,
                "UserInput"        => $kode_pegawai,
                "TglInput"         => date("Y-m-d H:i:s"),
                "Jenis_Jual"       => $jenis_jual,
                "id_ongkir"        => $ongkir,
                "jumlah_min_order" => str_replace(',','',$jumlah_min_order),
                "harga_ongkir"     => str_replace(',','',$harga_ongkir),
                "Total_Harga"      => str_replace(',','',$total_sub_total),
                "Total_Ongkir"     => str_replace(',','',$total_ongkir),
                ];
        $simpan_data = $this->db->update("barang_keluar",$data,["NomorBarangKeluar" => $kode_barang_keluar,]);
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }
    function update_data()
    {
        $kode_barang_keluar = $this->input->post("nomor");
        $kode_pegawai       = $this->session->userdata("kode_pegawai");
        $tanggal            = $this->input->post("tanggal");
        $customer           = $this->input->post("customer");
        $jenis_stok         = $this->input->post("jenis_stok");
        $jenis_jual         = $this->input->post("jenis_jual");
        $ongkir             = $this->input->post("ongkir");
        $jumlah_min_order   = $this->input->post("jumlah_min_order");
        $harga_ongkir       = $this->input->post("harga_ongkir");
        $total_sub_total    = $this->input->post("total_sub_total");
        $total_ongkir       = $this->input->post("total_ongkir");

        $kode_barang    = $this->input->post("kode_barang");
        $qty            = $this->input->post("Qty");
        $qty_lama       = $this->input->post("qty_lama");
        $harga_terakhir = $this->input->post("harga_terakhir");
        $untung_persen  = $this->input->post("untung_persen");
        $harga_barang   = $this->input->post("harga_barang");
        $diskon         = $this->input->post("diskon");
        $harga_diskon   = $this->input->post("harga_diskon");
        

        $this->db->trans_start();
        $data =[
                "Tanggal"          => $tanggal,
                "id_customer"      => $customer,
                "Jenis"            => $jenis_stok,
                "UserInput"        => $kode_pegawai,
                "TglInput"         => date("Y-m-d H:i:s"),
                "Jenis_Jual"       => $jenis_jual,
                "id_ongkir"        => $ongkir,
                "jumlah_min_order" => str_replace(',','',$jumlah_min_order),
                "harga_ongkir"     => str_replace(',','',$harga_ongkir),
                "Total_Harga"      => str_replace(',','',$total_sub_total),
                "Total_Ongkir"     => str_replace(',','',$total_ongkir),
                ];
        $simpan_data = $this->db->update("barang_keluar",$data,["NomorBarangKeluar" => $kode_barang_keluar,]);
        $hapus_detail = $this->db->query("DELETE FROM barang_keluar_detail WHERE NomorBarangkeluar='$kode_barang_keluar'");
        foreach ($kode_barang as $key => $value) {
            $qty_set            = str_replace(',','',$qty[$key]);
            $qty_lama_set       = str_replace(',','',$qty_lama[$key]);
            $harga_terakhir_set = str_replace(',','',$harga_terakhir[$key]);
            $untung_persesn_set = str_replace(',','',$untung_persen[$key]);
            $harga_set          = str_replace(',','',$harga_barang[$key]);
            $diskon_set         = str_replace(',','',$diskon[$key]);
            $harga_diskon_set   = str_replace(',','',$harga_diskon[$key]);

            $qty_lama_fix = (empty($qty_lama_set)) ? 0 : ($qty_lama_set);
            $detail = $this->db->query("INSERT INTO barang_keluar_detail(NomorBarangkeluar,KodeBarang,Qty,Harga_Terakhir,Keuntungan_Persen,Harga_Jual,Diskon,Harga_Diskon)
                                        VALUES('$kode_barang_keluar','$kode_barang[$key]','$qty_set','$harga_terakhir_set','$untung_persesn_set','$harga_set','$diskon_set','$harga_diskon_set')");

            $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=(A.Qty_Stok+$qty_lama_fix)-$qty_set 
                                            WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='$jenis_stok'");            
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }

    function get_harga_ongkir()
    {
        $jenis_ongkir = $this->input->post("jenis_ongkir");
        $sql = $this->db->query("SELECT A.id_ongkir,A.nama_ongkir,A.harga_ongkir,A.jumlah_min_order 
                                FROM ongkir A WHERE A.Aktif=1 AND A.id_ongkir=$jenis_ongkir");
        
        $harga_ongkir = ($sql->num_rows() > 0) ? ($sql->row()->harga_ongkir) : 0;
        $jumlah       = ($sql->num_rows() > 0) ? (($sql->row()->jumlah_min_order == 0 ) ? 0 : ($sql->row()->jumlah_min_order)) : 0;

        $hasil["harga_ongkir"] = number_format($harga_ongkir);
        $hasil["jumlah"]       = number_format($jumlah);
        echo json_encode($hasil);
    }

    function get_alamat()
    {
        $customer = $this->input->post("customer");
        $sql      = $this->db->query("SELECT A.Alamat FROM customer A WHERE A.id_customer='$customer'");
        $alamat   = ($sql->num_rows() > 0) ? ($sql->row()->Alamat) : "";

        $hasil["alamat"] = $alamat;
        echo json_encode($hasil);
        
    }
	
}
