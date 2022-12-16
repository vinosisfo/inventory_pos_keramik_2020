<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_supplier extends MX_Controller  {
	
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
        $this->load->view("f_supplier");

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
            $row [] = '<button type="button" class="btn btn-primary btn-xs" onclick="edit_data(\''.$field->id_supplier.'\')">Edit</button>';
            $row [] = $field->Nama_supplier;
            $row [] = $field->Alamat;
            $row [] = $field->Notlp;
            $row [] = $field->Email;
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
        
        $where_kode_nama = (empty($kode_nama)) ? "" : " AND (A.Nama_supplier LIKE '%$kode_nama%')";
        $where_aktif     = (empty($aktif)) ? "" : (($aktif==1) ? " AND A.Aktif='$aktif'" : " AND A.Aktif='0'");

        $sql = "(SELECT A.id_supplier,A.Nama_supplier,A.Alamat,A.Notlp,A.Email,A.Aktif 
                    FROM supplier A
                    WHERE 1=1  
                    $where_kode_nama
                    $where_aktif
                ) A1
                ORDER BY A1.Nama_supplier";
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

        $sql = "(SELECT A.id_supplier
                FROM supplier A ) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");
        $data["format"] = $format;
        $this->load->view("f_input_supplier",$data);
    }

    function simpan_data()
    {
        $nama_supplier = strtoupper($this->input->post("nama_supplier"));
        $alamat        = ($this->input->post("alamat"));
        $no_tlp        = ($this->input->post("no_tlp"));
        $email         = ($this->input->post("email"));

        // $cek_barang = $this->db->query("SELECT A.Nama_Supplier FROM supplier A WHERE A.Nama_Supplier='$nama_supplier'");
        // if($cek_barang->num_rows() > 0) {
        //     $pesan["pesan"] = "duplikat";
        // } else {
            $this->db->trans_start();
            $data =[
                    "Nama_Supplier" => $nama_supplier,
                    "Alamat"        => $alamat,
                    "NoTlp"         => $no_tlp,
                    "Email"         => $email,
                    "Aktif"         => 1,
                    ];
            $simpan_data = $this->db->insert("supplier",$data);

            $this->db->trans_complete();
            $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        // }
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT A.id_supplier,A.Nama_supplier,A.Alamat,A.Notlp,A.Email,A.Aktif 
                                FROM supplier A
                                WHERE A.id_supplier='$kode'");
        $data["list"] = $sql;
        $this->load->view("f_edit_supplier",$data);        
    }

    function update_data()
    {
        $id_supplier   = $this->input->post("id_supplier");
        $nama_supplier = strtoupper($this->input->post("nama_supplier"));
        $alamat        = ($this->input->post("alamat"));
        $no_tlp        = ($this->input->post("no_tlp"));
        $email         = ($this->input->post("email"));
        $aktif         = $this->input->post("aktif");

        $this->db->trans_start();
        $data =[
                "Nama_Supplier" => $nama_supplier,
                "Alamat"        => $alamat,
                "NoTlp"         => $no_tlp,
                "Email"         => $email,
                "Aktif"         => $aktif,
                ];
        $simpan_data = $this->db->update("supplier",$data,["id_supplier" => $id_supplier]);

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        
        echo json_encode($pesan);
    }
	
}
