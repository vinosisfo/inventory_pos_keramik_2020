<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ongkir extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
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
        $this->load->view("f_ongkir");

	}

	function get_data()
    {
        $list = $this->get_list();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();

            $row [] = $no;
            $row [] = '<button type="button" class="btn btn-primary btn-xs" onclick="edit_data(\''.$field->id_ongkir.'\')">Edit</button>';
            $row [] = $field->nama_ongkir;
            $row [] = $field->jumlah_min_order;
            $row [] = number_format($field->harga_ongkir);
            $row [] = ($field->Aktif==1) ? "Ya" : "Tdk";
            $data[] = $row;
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
        $kode_nama    = $this->input->post("kode_nama");
        $aktif        = $this->input->post("aktif");
        
        $where_kode_nama = (empty($kode_nama)) ? "" : " AND (A.nama_ongkir LIKE '%$kode_nama%')";
        $where_aktif     = (empty($aktif)) ? "" : (($aktif==1) ? " AND A.Aktif='$aktif'" : " AND A.Aktif='0'");

        $sql = "(SELECT A.id_ongkir,A.nama_ongkir,A.jumlah_min_order,A.harga_ongkir,A.Aktif 
                    FROM ongkir A 
                    WHERE 1=1
                    $where_kode_nama
                    $where_aktif
                ) A1
                ORDER BY A1.nama_ongkir";
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
        $kode_user    = $this->input->post("kode_user");

        $sql = "(SELECT A.id_ongkir
                FROM ongkir A ) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");
        $data["format"] = $format;
        $this->load->view("f_input_ongkir",$data);
    }

    function simpan_data()
    {
        $nama_ongkir  = strtoupper($this->input->post("nama_ongkir"));
        $min_order    = ($this->input->post("min_order"));
        $harga_ongkir = ($this->input->post("harga_ongkir"));

        $min_order_set    = str_replace(',','',$min_order);
        $harga_ongkir_set = str_replace(',','',$harga_ongkir);

        $this->db->trans_start();
        $data =[
                "nama_ongkir"      => $nama_ongkir,
                "jumlah_min_order" => ($min_order_set > 0) ? ($min_order_set) : 0,
                "harga_ongkir"     => ($harga_ongkir_set > 0) ? ($harga_ongkir_set) : 0,
                "Aktif"            => 1,
                ];
        $simpan_data = $this->db->insert("ongkir",$data);

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT * 
                                FROM ongkir A
                                WHERE A.id_ongkir='$kode'");
        $data["list"] = $sql;
        $this->load->view("f_edit_ongkir",$data);        
    }

    function update_data()
    {
        $id_ongkor    = $this->input->post("id_ongkir");
        $nama_ongkir  = strtoupper($this->input->post("nama_ongkir"));
        $min_order    = ($this->input->post("min_order"));
        $harga_ongkir = ($this->input->post("harga_ongkir"));

        $min_order_set    = str_replace(',','',$min_order);
        $harga_ongkir_set = str_replace(',','',$harga_ongkir);

        $this->db->trans_start();
        $data =[
                "nama_ongkir"      => $nama_ongkir,
                "jumlah_min_order" => ($min_order_set > 0) ? ($min_order_set) : 0,
                "harga_ongkir"     => ($harga_ongkir_set > 0) ? ($harga_ongkir_set) : 0,
                "Aktif"            => 1,
                ];
        $simpan_data = $this->db->update("ongkir",$data,["id_ongkir" => $id_ongkor]);

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }
	
}
