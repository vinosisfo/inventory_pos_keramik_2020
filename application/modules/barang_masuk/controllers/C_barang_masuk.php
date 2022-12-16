<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_barang_masuk extends MX_Controller  {
	
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
        $this->load->view("f_barang_masuk");

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
            $row  [] = '<button type="button" class="btn btn-primary btn-xs" onclick="edit_data(\''.$field->NomorBarangMasuk.'\')">Edit</button>';
            $row  [] = '<img src="'.base_url().'assets/adminbsb/images/print.jpg" class="img-responsive" style="width : 40px; height : auto;" onclick="print_data(\''.$field->NomorBarangMasuk.'\')" title="Print">';
            $row  [] = $field->NomorBarangMasuk;
            $row  [] = $field->Tanggal;
            $row  [] = $field->Nama_supplier;
            $row  [] = $field->Alamat;
            $row  [] = $field->KodeBarang;
            $row  [] = $field->NamaBarang;
            $row  [] = number_format($field->Qty);
            $row  [] = number_format($field->Harga);
            $row  [] = number_format($field->Qty*$field->Harga);
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
        $supplier     = $this->input->post("supplier");
        $barang       = $this->input->post("barang");
        $date1        = $this->input->post("date1");
        $date2        = $this->input->post("date2");
        
        $where_supplier = (empty($supplier)) ? "" : " AND C.Nama_supplier LIKE '%$supplier%'";
        $where_barang   = (empty($barang)) ? "" : " AND E.NamaBarang Like '%$barang%'";

        $sql = "(SELECT DISTINCT A.NomorBarangMasuk,A.Tanggal,A.id_supplier,C.Nama_supplier,C.Alamat,B.KodeBarang,E.NamaBarang,B.Qty,B.Harga,
                    A.TglInput,D.username
                    FROM barang_masuk A 
                    INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                    LEFT JOIN supplier C ON C.id_supplier=A.id_supplier
                    LEFT JOIN user D ON D.kode_pegawai=A.UserInput
                    LEFT JOIN barang E ON E.Kodebarang=B.KodeBarang
                    WHERE 1=1 AND A.Tanggal BETWEEN '$date1' AND '$date2'
                    $where_supplier
                    $where_barang
                ) A1
                ORDER BY A1.Tanggal DESC, A1.Nama_supplier, A1.NamaBarang";
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

        $sql = "(SELECT DISTINCT A.NomorBarangMasuk,A.Tanggal,A.id_supplier,C.Nama_supplier,C.Alamat,B.KodeBarang,E.NamaBarang,B.Qty,B.Harga,
                    A.TglInput,D.username
                    FROM barang_masuk A 
                    INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                    LEFT JOIN supplier C ON C.id_supplier=A.id_supplier
                    LEFT JOIN user D ON D.kode_pegawai=A.UserInput
                    LEFT JOIN barang E ON E.Kodebarang=B.KodeBarang 
                ) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");

        $supplier = $this->db->query("SELECT A.id_supplier,A.Nama_supplier 
                                    FROM supplier A WHERE A.Aktif=1
                                    ORDER BY A.Nama_supplier");

        $data_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang 
                                        FROM barang A WHERE A.Aktif=1
                                        ORDER BY A.NamaBarang");
        $data["format"]   = $format;
        $data["supplier"] = $supplier;
        $data["barang"]   = $data_barang;
        $this->load->view("f_input_barang_masuk",$data);
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

    function set_sub_total(){
        $qty_baris   = $this->input->post("qty_baris");
        $harga_baris = $this->input->post("harga_baris");

        $qty_baris_set   = str_replace(',','',$qty_baris);
        $harga_baris_set = str_replace(',','',$harga_baris);

        $qty          = $this->input->post("Qty");
        $harga_barang = $this->input->post("harga_barang");

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

        $data["sub_total"]       = number_format($sub_total_baris);
        $data["total_qty"]       = number_format($total_qty);
        $data["total_harga"]     = number_format($total_harga);
        $data["total_sub_total"] = number_format($total_sub_total);

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
        $kode_barang_masuk = $this->m_kode->kode_barang_masuk();
        $kode_pegawai      = $this->session->userdata("kode_pegawai");
        $tanggal           = $this->input->post("tanggal");
        $supplier          = $this->input->post("supplier");

        $kode_barang  = $this->input->post("kode_barang");
        $qty          = $this->input->post("Qty");
        $harga_barang = $this->input->post("harga_barang");

        $this->db->trans_start();
        $data =[
                "NomorBarangMasuk" => $kode_barang_masuk,
                "Tanggal"          => $tanggal,
                "id_supplier"      => $supplier,
                "UserInput"        => $kode_pegawai,
                "TglInput"         => date("Y-m-d H:i:s"),
                ];
        $simpan_data = $this->db->insert("barang_masuk",$data);

        foreach ($kode_barang as $key => $value) {
            $qty_set   = str_replace(',','',$qty[$key]);
            $harga_set = str_replace(',','',$harga_barang[$key]);

            $detail = $this->db->query("INSERT INTO barang_masuk_detail(NomorBarangMasuk,KodeBarang,Qty,Harga)
                                        VALUES('$kode_barang_masuk','$kode_barang[$key]','$qty_set','$harga_set')");

            $cek_stok = $this->db->query("SELECT A.KodeBarang FROM stok_barang A WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='MASUK'");
            if($cek_stok->num_rows() > 0){
                $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok+$qty_set 
                                                WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='MASUK'");
            } else {
                $simpan_stok = $this->db->query("INSERT INTO stok_barang(KodeBarang,Jenis,Qty_Stok)
                                                VALUES('$kode_barang[$key]','MASUK','$qty_set')");
            }
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT A.NomorBarangMasuk,A.id_supplier,C.Nama_supplier,A.Tanggal,B.KodeBarang,D.NamaBarang, B.Qty,B.Harga,(B.Qty*B.Harga) AS SUB_TOTAL
                                FROM barang_masuk A 
                                INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                                LEFT JOIN supplier C ON C.id_supplier=A.id_supplier
                                LEFT JOIN barang D ON D.Kodebarang=B.KodeBarang
                                WHERE A.NomorBarangMasuk='$kode'");

        $supplier = $this->db->query("SELECT A.id_supplier,A.Nama_supplier 
                                    FROM supplier A WHERE A.Aktif=1
                                    ORDER BY A.Nama_supplier");

        $data_barang = $this->db->query("SELECT A.Kodebarang,A.NamaBarang 
                                        FROM barang A WHERE A.Aktif=1
                                        ORDER BY A.NamaBarang");

        $data["list"]       = $sql;
        $data["supplier"]   = $supplier;
        $data["ci"]         = $this;
        $data["barang_all"] = $data_barang;
        $this->load->view("f_edit_barang_masuk",$data);        
    }

    function get_form_print()
    {
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT A.NomorBarangMasuk,A.id_supplier,C.Nama_supplier,C.Alamat,A.Tanggal,B.KodeBarang,D.NamaBarang, B.Qty,B.Harga,(B.Qty*B.Harga) AS SUB_TOTAL
                                FROM barang_masuk A 
                                INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                                LEFT JOIN supplier C ON C.id_supplier=A.id_supplier
                                LEFT JOIN barang D ON D.Kodebarang=B.KodeBarang
                                WHERE A.NomorBarangMasuk='$kode'");

        $data["list"]       = $sql;
        $data["ci"]         = $this;
        $this->load->view("f_print_barang_masuk",$data);
    }

    function export_list()
    {
        $kode_pegawai = $this->session->userdata("kode_pegawai");
        $akses_admin  = $this->session->userdata("akses_admin");
        $supplier     = $this->input->post("supplier_src");
        $barang       = $this->input->post("barang_src");
        $date1        = $this->input->post("date1");
        $date2        = $this->input->post("date2");
        
        $where_supplier = (empty($supplier)) ? "" : " AND C.Nama_supplier LIKE '%$supplier%'";
        $where_barang   = (empty($barang)) ? "" : " AND E.NamaBarang Like '%$barang%'";
        
        $sql = $this->db->query("SELECT * FROM (SELECT DISTINCT A.NomorBarangMasuk,A.Tanggal,A.id_supplier,C.Nama_supplier,C.Alamat,B.KodeBarang,E.NamaBarang,B.Qty,B.Harga,
                    A.TglInput,D.username
                    FROM barang_masuk A 
                    INNER JOIN barang_masuk_detail B ON B.NomorBarangMasuk=A.NomorBarangMasuk
                    LEFT JOIN supplier C ON C.id_supplier=A.id_supplier
                    LEFT JOIN user D ON D.kode_pegawai=A.UserInput
                    LEFT JOIN barang E ON E.Kodebarang=B.KodeBarang
                    WHERE 1=1 AND A.Tanggal BETWEEN '$date1' AND '$date2'
                    $where_supplier
                    $where_barang
                ) A1
                ORDER BY A1.Tanggal DESC, A1.Nama_supplier, A1.NamaBarang");
        $data["list"] = $sql;
        $this->load->view('f_barang_masuk_xls',$data);
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
                                            WHERE A.KodeBarang='$kodebarang' AND A.Jenis='MASUK'");

            $hapus_detail = $this->db->query("DELETE FROM barang_masuk_detail WHERE KodeBarang='$kodebarang' AND NomorBarangMasuk='$nomor'");

            if($jumlah_data==1){
                $hapus_header = $this->db->query("DELETE FROM barang_masuk WHERE NomorBarangMasuk='$nomor'");
            }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function update_data()
    {
        $kode_barang_masuk = $this->input->post("nomor");
        $kode_pegawai      = $this->session->userdata("kode_pegawai");
        $tanggal           = $this->input->post("tanggal");
        $supplier          = $this->input->post("supplier");

        $kode_barang  = $this->input->post("kode_barang");
        $qty          = $this->input->post("Qty");
        $harga_barang = $this->input->post("harga_barang");

        $kode_barang_lama = $this->input->post("kode_barang_lama");
        $qty_lama         = $this->input->post("qty_lama");

        $this->db->trans_start();
        $data =[
                "Tanggal"          => $tanggal,
                "id_supplier"      => $supplier,
                "UserInput"        => $kode_pegawai,
                "TglInput"         => date("Y-m-d H:i:s"),
                ];
        $simpan_data  = $this->db->update("barang_masuk",$data,["NomorBarangMasuk" => $kode_barang_masuk,]);
        $hapus_detail = $this->db->query("DELETE FROM barang_masuk_detail WHERE NomorBarangMasuk='$kode_barang_masuk'");
        foreach ($kode_barang as $key => $value) {
            $qty_set      = str_replace(',','',$qty[$key]);
            $harga_set    = str_replace(',','',$harga_barang[$key]);
            $qty_lama_set = str_replace(',','',$qty_lama[$key]);

            if((strlen($kode_barang_lama[$key]) > 2) AND ($qty_lama_set > 0)){
                $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok-$qty_lama_set 
                                                WHERE A.KodeBarang='$kode_barang_lama[$key]' AND A.Jenis='MASUK'");
            }

            $detail = $this->db->query("INSERT INTO barang_masuk_detail(NomorBarangMasuk,KodeBarang,Qty,Harga)
                                        VALUES('$kode_barang_masuk','$kode_barang[$key]','$qty_set','$harga_set')");

            $cek_stok = $this->db->query("SELECT A.KodeBarang FROM stok_barang A WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='MASUK'");
            if($cek_stok->num_rows() > 0){
                $update_stok = $this->db->query("UPDATE stok_barang A SET A.Qty_Stok=A.Qty_Stok+$qty_set 
                                                WHERE A.KodeBarang='$kode_barang[$key]' AND A.Jenis='MASUK'");
            } else {
                $simpan_stok = $this->db->query("INSERT INTO stok_barang(KodeBarang,Jenis,Qty_Stok)
                                                VALUES('$kode_barang[$key]','MASUK','$qty_set')");
            }
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }
	
}
