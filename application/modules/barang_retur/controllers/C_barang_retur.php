<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_barang_retur extends MX_Controller  {
	
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
        $this->load->view("f_barang_retur");

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
            $row  [] = $field->NomorRetur;
            $row  [] = $field->Tanggal;
            $row  [] = $field->NomorBarangKeluar;
            $row  [] = $field->NamaCustomer;
            $row  [] = $field->Alamat;
            $row  [] = $field->KodeBarang;
            $row  [] = $field->NamaBarang;
            $row  [] = number_format($field->QTY_KELUAR);
            $row  [] = number_format($field->QTY_RETUR);
            $row  [] = date("Y-m-d",strtotime($field->TglInput));
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

        $sql = $this->db->query("SELECT A.NomorBarangKeluar,D.id_customer,E.NamaCustomer,E.Alamat,D.Jenis
                                ,B.KodeBarang,F.NamaBarang,C.Qty,SUM(B.Qty) QTY_SUDAH_RETUR,(C.Qty-SUM(B.Qty))+SUM(B.Qty) QTY_BEBAS
                                FROM retur A
                                INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                                INNER JOIN barang_keluar_detail C ON C.NomorBarangKeluar=A.NomorBarangKeluar AND C.KodeBarang=B.KodeBarang
                                INNER JOIN barang_keluar D ON D.NomorBarangKeluar=C.NomorBarangKeluar
                                LEFT JOIN customer E ON E.id_customer=D.id_customer
                                LEFT JOIN barang F ON F.Kodebarang=B.KodeBarang
                                WHERE D.NomorBarangKeluar='$kode'
                                GROUP BY A.NomorBarangKeluar,D.id_customer,E.NamaCustomer,E.Alamat,D.Jenis
                                ,B.KodeBarang,F.NamaBarang");
        $data["list"] = $sql;
        $this->load->view("f_barang_retur_print",$data);
    }

    function export_data()
    {
        $kode_pegawai = $this->session->userdata("kode_pegawai");
        $akses_admin  = $this->session->userdata("akses_admin");
        $customer     = $this->input->post("customer_src");
        $barang       = $this->input->post("barang_src");
        $nomor_retur  = $this->input->post("nomor_retur_src");
        $nomor_keluar = $this->input->post("nomor_keluar_src");
        $date1        = $this->input->post("date1");
        $date2        = $this->input->post("date2");
        
        $where_customer     = (empty($customer)) ? "" : " AND F.NamaCustomer LIKE '%$customer%'";
        $where_barang       = (empty($barang)) ? "" : " AND D.Namabarang LIKE '%$barang%'";
        $where_nomor_retur  = (empty($nomor_retur)) ? "" : " AND A.NomorRetur LIKE '%$nomor_retur%'";
        $where_nomor_keluar = (empty($nomor_keluar)) ? "" : " AND E.NomorBarangKeluar LIKE '%$nomor_keluar%'";

        $sql = $this->db->query("SELECT * FROM (
                    SELECT DISTINCT A.NomorRetur,A.Tanggal,A.NomorBarangKeluar,B.KodeBarang,D.NamaBarang,B.Qty QTY_RETUR,C.Qty QTY_KELUAR 
                    ,F.NamaCustomer,F.Alamat,A.TglInput,G.username
                    FROM retur A 
                    INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                    INNER JOIN barang_keluar_detail C ON C.NomorBarangKeluar=A.NomorBarangKeluar AND C.KodeBarang=B.KodeBarang
                    LEFT JOIN barang D ON D.Kodebarang=B.KodeBarang
                    INNER JOIN barang_keluar E ON E.NomorBarangKeluar=C.NomorBarangKeluar
                    LEFT JOIN customer F ON F.id_customer=E.id_customer
                    LEFT JOIN user G ON G.kode_pegawai=A.UserInput
                    WHERE 1=1 
                    AND A.Tanggal BETWEEN '$date1' AND '$date2'
                    $where_customer
                    $where_barang
                    $where_nomor_keluar
                    $where_nomor_retur
                ) A1
                ORDER BY A1.Tanggal DESC,A1.NamaBarang");
        $data["list"] = $sql;
        $this->load->view('f_barang_retur_xls',$data);
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
        $nomor_retur  = $this->input->post("nomor_retur");
        $nomor_keluar = $this->input->post("nomor_keluar");
        $date1        = $this->input->post("date1");
        $date2        = $this->input->post("date2");
        
        $where_customer     = (empty($customer)) ? "" : " AND F.NamaCustomer LIKE '%$customer%'";
        $where_barang       = (empty($barang)) ? "" : " AND D.Namabarang LIKE '%$barang%'";
        $where_nomor_retur  = (empty($nomor_retur)) ? "" : " AND A.NomorRetur LIKE '%$nomor_retur%'";
        $where_nomor_keluar = (empty($nomor_keluar)) ? "" : " AND E.NomorBarangKeluar LIKE '%$nomor_keluar%'";

        $sql = "(SELECT DISTINCT A.NomorRetur,A.Tanggal,A.NomorBarangKeluar,B.KodeBarang,D.NamaBarang,B.Qty QTY_RETUR,C.Qty QTY_KELUAR 
                ,F.NamaCustomer,F.Alamat,A.TglInput,G.username
                FROM retur A 
                INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                INNER JOIN barang_keluar_detail C ON C.NomorBarangKeluar=A.NomorBarangKeluar AND C.KodeBarang=B.KodeBarang
                LEFT JOIN barang D ON D.Kodebarang=B.KodeBarang
                INNER JOIN barang_keluar E ON E.NomorBarangKeluar=C.NomorBarangKeluar
                LEFT JOIN customer F ON F.id_customer=E.id_customer
                LEFT JOIN user G ON G.kode_pegawai=A.UserInput
                WHERE 1=1 
                AND A.Tanggal BETWEEN '$date1' AND '$date2'
                $where_customer
                $where_barang
                $where_nomor_keluar
                $where_nomor_retur
                ) A1
                ORDER BY A1.Tanggal DESC,A1.NamaBarang";
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

        $sql = "(SELECT DISTINCT A.NomorRetur,A.Tanggal,A.NomorBarangKeluar,B.KodeBarang
                    FROM retur A 
                    INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                    INNER JOIN barang_keluar_detail C ON C.NomorBarangKeluar=A.NomorBarangKeluar AND C.KodeBarang=B.KodeBarang
                    LEFT JOIN barang D ON D.Kodebarang=B.KodeBarang
                    INNER JOIN barang_keluar E ON E.NomorBarangKeluar=C.NomorBarangKeluar
                    LEFT JOIN customer F ON F.id_customer=E.id_customer
                    LEFT JOIN user G ON G.kode_pegawai=A.UserInput
                    WHERE 1=1  
                ) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");

        $nomor_cust = $this->db->query("SELECT DISTINCT A.NomorBarangKeluar,A.id_customer,B.NamaCustomer,A.Jenis 
                                        FROM barang_keluar A
                                        LEFT JOIN customer B ON B.id_customer=A.id_customer
                                        ORDER BY A.tanggal DESC");
        $data["format"]     = $format;
        $data["nomor_cust"] = $nomor_cust;
        $this->load->view("f_input_barang_retur",$data);
    }

    function get_jenis(){
        $nomor = $this->input->post("nomor");
        $sql   = $this->db->query("SELECT A.Jenis FROM barang_keluar A WHERE A.NomorBarangKeluar='$nomor'");
        $jenis = ($sql->num_rows() > 0) ? ($sql->row()->Jenis) : "";
        
        $hasil["jenis"] = ($jenis=="MASUK") ? "Non Reject" : "Reject";
        echo json_encode($hasil);
    }

    function get_barang_kode()
    {
        $data        = json_decode(file_get_contents('php://input'), true);
        @$term       = $data["term"];
        $kode_barang = $data["kode_barang"];
        $nomor       = $data["nomor"];

        $sql = "(SELECT DISTINCT A.KodeBarang,B.NamaBarang,(A.Qty-sum(IFNULL(D.Qty,0))) QTY FROM barang_keluar_detail A 
                INNER JOIN barang B ON B.Kodebarang=A.KodeBarang
                LEFT JOIN retur C ON C.NomorBarangKeluar=A.NomorBarangKeluar
                LEFT JOIN retur_detail D ON D.NomorRetur=C.NomorRetur AND D.KodeBarang=A.KodeBarang
                WHERE A.NomorBarangKeluar='$nomor'
                AND (B.KodeBarang LIKE '%$term%')
                GROUP BY A.KodeBarang,B.NamaBarang
                ) A1";
        $sql_set = $this->db->from($sql)
                            ->where_not_in('A1.KodeBarang',$kode_barang)
                            ->where("A1.QTY > 0")
                            ->order_by("A1.NamaBarang")
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
        $kode_barang = $data["nama_barang"];
        $nomor       = $data["nomor"];

        $sql = "(SELECT DISTINCT A.KodeBarang,B.NamaBarang,(A.Qty-sum(IFNULL(D.Qty,0))) QTY FROM barang_keluar_detail A 
                INNER JOIN barang B ON B.Kodebarang=A.KodeBarang
                LEFT JOIN retur C ON C.NomorBarangKeluar=A.NomorBarangKeluar
                LEFT JOIN retur_detail D ON D.NomorRetur=C.NomorRetur AND D.KodeBarang=A.KodeBarang
                WHERE A.NomorBarangKeluar='$nomor'
                AND (B.KodeBarang LIKE '%$term%')
                GROUP BY A.KodeBarang,B.NamaBarang
                ) A1";
        $sql_set = $this->db->from($sql)
                            ->where_not_in('A1.KodeBarang',$kode_barang)
                            ->where("A1.QTY > 0")
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

        $sql = $this->db->query("SELECT A.Kodebarang,A.NamaBarang,Deskripsi
                                FROM barang A WHERE A.Aktif=1 AND A.Kodebarang='$kode_barang'
                                ORDER BY A.NamaBarang");
                                
        $id   = $sql->row()->Kodebarang;
        $text = ($jenis=="kode") ? ($sql->row()->NamaBarang) : ($sql->row()->Kodebarang);

        $sql_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang,Deskripsi
                                        FROM barang A WHERE A.Aktif=1 AND A.Kodebarang<>'$id'
                                        ORDER BY A.NamaBarang");

        $opsi = '<option value="'.$id.'">'.$text.'</option>';
        $opsi .= '<option value="">Pilih</option>';
        foreach ($sql_barang->result() as $data) {
            $text_all = ($jenis=="kode") ? ($data->NamaBarang) : ($data->Kodebarang);
            $opsi .= '<option value="'.$data->Kodebarang.'">'.$text_all.'</option>';
        }
        

        $hasil["barang"] = $opsi;
        echo json_encode($hasil);
    }

    function get_qty()
    {
        $kode_barang = $this->input->post('kode_barang');
        $nomor       = $this->input->post('nomor');

        $sql = $this->db->query("SELECT A.Qty qty_awal,(A.Qty-sum(IFNULL(D.Qty,0))) Qty,sum(IFNULL(D.Qty,0)) qty_retur 
                                FROM barang_keluar_detail A 
                                LEFT JOIN retur C ON C.NomorBarangKeluar=A.NomorBarangKeluar
                                LEFT JOIN retur_detail D ON D.NomorRetur=C.NomorRetur AND D.KodeBarang=A.KodeBarang
                                WHERE A.KodeBarang='$kode_barang' AND A.NomorBarangKeluar='$nomor'");
        $hasil["hasil"]     = ($sql->num_rows() > 0) ? (number_format($sql->row()->Qty)) : 0;
        $hasil["qty_awal"]  = ($sql->num_rows() > 0) ? (number_format($sql->row()->qty_awal)) : 0;
        $hasil["qty_retur"] = ($sql->num_rows() > 0) ? (number_format($sql->row()->qty_retur)) : 0;
        echo json_encode($hasil);
    }

    function set_sub_total(){
        $qty_baris        = $this->input->post("qty_baris");
        $qty_keluar_baris = $this->input->post("qty_keluar");

        $qty_baris_set        = str_replace(',','',$qty_baris);
        $qty_keluar_baris_set = str_replace(',','',$qty_keluar_baris);

        $qty        = $this->input->post("Qty");
        $qty_keluar = $this->input->post("Qty_keluar");

        $total_qty_keluar = 0;
        $total_qty_retur  = 0;
        foreach ($qty as $key => $value) {
            $qty_set        = str_replace(',','',$qty[$key]);
            $qty_keluar_set = str_replace(',','',$qty_keluar[$key]);

            $total_qty_keluar += ($qty_keluar_set > 0) ? ($qty_keluar_set) : 0;
            $total_qty_retur  += ($qty_set > 0) ? ($qty_set) : 0;
        }

        $data["total_keluar"]    = number_format($total_qty_keluar);
        $data["total_retur"]     = number_format($total_qty_retur);
        echo json_encode($data);
    }

    function cek_qty()
    {
        $qty_keluar = $this->input->post("qty_keluar");
        $qty_retur  = $this->input->post("qty_retur");

        $qty_keluar_set = str_replace(',','',$qty_keluar);
        $qty_retur_set  = str_replace(',','',$qty_retur);

        $hasil["hasil"] = ($qty_retur_set > $qty_keluar_set) ? "lebih" : "ok";
        echo json_encode($hasil);
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
        $kode_barang_retur = $this->m_kode->kode_barang_retur();
        $kode_pegawai      = $this->session->userdata("kode_pegawai");
        $tanggal           = $this->input->post("tanggal");
        $nomor_keluar      = $this->input->post("nomor_keluar");

        $kode_barang  = $this->input->post("kode_barang");
        $qty          = $this->input->post("Qty");

        $this->db->trans_start();
        $data =[
                "NomorRetur"        => $kode_barang_retur,
                "Tanggal"           => $tanggal,
                "NomorBarangKeluar" => $nomor_keluar,
                "UserInput"         => $kode_pegawai,
                "TglInput"          => date("Y-m-d H:i:s"),
                ];
        $simpan_data = $this->db->insert("retur",$data);

        foreach ($kode_barang as $key => $value) {
            $qty_set   = str_replace(',','',$qty[$key]);

            $detail = $this->db->query("INSERT INTO retur_detail(NomorRetur,KodeBarang,Qty)
                                        VALUES('$kode_barang_retur','$kode_barang[$key]','$qty_set')");

            $cek_stok = $this->db->query("SELECT A.KodeBarang FROM stok_barang A WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='REJECT'");
            if($cek_stok->num_rows() > 0){
                $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok+$qty_set 
                                                WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='REJECT'");
            } else {
                $simpan_stok = $this->db->query("INSERT INTO stok_barang(KodeBarang,Jenis,Qty_Stok)
                                                VALUES('$kode_barang[$key]','REJECT','$qty_set')");
            }
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT A.NomorBarangKeluar,D.id_customer,E.NamaCustomer,D.Jenis
                                ,B.KodeBarang,F.NamaBarang,C.Qty,SUM(B.Qty) QTY_SUDAH_RETUR,(C.Qty-SUM(B.Qty))+SUM(B.Qty) QTY_BEBAS
                                FROM retur A
                                INNER JOIN retur_detail B ON B.NomorRetur=A.NomorRetur
                                INNER JOIN barang_keluar_detail C ON C.NomorBarangKeluar=A.NomorBarangKeluar AND C.KodeBarang=B.KodeBarang
                                INNER JOIN barang_keluar D ON D.NomorBarangKeluar=C.NomorBarangKeluar
                                LEFT JOIN customer E ON E.id_customer=D.id_customer
                                LEFT JOIN barang F ON F.Kodebarang=B.KodeBarang
                                WHERE D.NomorBarangKeluar='$kode'
                                GROUP BY A.NomorBarangKeluar,D.id_customer,E.NamaCustomer,D.Jenis
                                ,B.KodeBarang,F.NamaBarang");

        $data["list"]       = $sql;
        $data["ci"]         = $this;
        $this->load->view("f_edit_barang_retur",$data);        
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
        $jumlah_data = $this->input->post("jumlah_data");
        $qty         = $this->input->post("qty");
        $qty_set     = str_replace(',','',$qty);

        $this->db->trans_start();
            $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok-$qty_set 
                                            WHERE A.KodeBarang='$kodebarang' AND A.Jenis='REJECT'");

            $hapus_detail = $this->db->query("DELETE A FROM retur_detail A 
                                            INNER JOIN retur B ON B.NomorRetur=A.NomorRetur
                                            WHERE B.NomorBarangKeluar='$nomor' AND A.KodeBarang='$kodebarang'");

            if($jumlah_data==1){
                $hapus_header = $this->db->query("DELETE FROM retur WHERE NomorBarangKeluar='$nomor'");
            }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function update_data()
    {
        $kode_barang_retur = $this->m_kode->kode_barang_retur();
        $kode_pegawai      = $this->session->userdata("kode_pegawai");
        $tanggal           = $this->input->post("tanggal");
        $nomor_keluar      = $this->input->post("nomor_keluar");

        $kode_barang = $this->input->post("kode_barang");
        $qty         = $this->input->post("Qty");
        $sudah_retur = $this->input->post("sudah_retur");

        $this->db->trans_start();
        $hapus_header = $this->db->query("DELETE FROM retur WHERE NomorBarangKeluar='$nomor_keluar'");
        $hapus_detail = $this->db->query("DELETE A FROM retur_detail A 
                                            INNER JOIN retur B ON B.NomorRetur=A.NomorRetur
                                            WHERE B.NomorBarangKeluar='$nomor_keluar'");

        $data =[
                "NomorRetur"        => $kode_barang_retur,
                "Tanggal"           => $tanggal,
                "NomorBarangKeluar" => $nomor_keluar,
                "UserInput"         => $kode_pegawai,
                "TglInput"          => date("Y-m-d H:i:s"),
                ];
        $simpan_data = $this->db->insert("retur",$data);

        foreach ($kode_barang as $key => $value) {
            $qty_set         = str_replace(',','',$qty[$key]);
            $sudah_retur_set = str_replace(',','',$sudah_retur[$key]);

            $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok-$sudah_retur_set 
                                            WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='REJECT'");

            $detail = $this->db->query("INSERT INTO retur_detail(NomorRetur,KodeBarang,Qty)
                                        VALUES('$kode_barang_retur','$kode_barang[$key]','$qty_set')");

            $cek_stok = $this->db->query("SELECT A.KodeBarang FROM stok_barang A WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='REJECT'");
            if($cek_stok->num_rows() > 0){
                $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok+$qty_set 
                                                WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='REJECT'");
            } else {
                $simpan_stok = $this->db->query("INSERT INTO stok_barang(KodeBarang,Jenis,Qty_Stok)
                                                VALUES('$kode_barang[$key]','REJECT','$qty_set')");
            }
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }
	
}
