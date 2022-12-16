<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MX_Controller  {
	
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
        $this->load->view("f_menu");

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
            $row [] = '<button type="button" class="btn btn-info btn-xs" onclick="edit_data(\''.$field->id_menu.'\')">Edit</button>';
            $row [] = $field->nama_menu;
            $row [] = $field->path_menu;
            $row [] = $field->JENIS;
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
        $nama_menu    = $this->input->post("nama_menu");
        $jenis_menu   = $this->input->post("jenis_menu");
        
        $where_nama_menu  = (empty($nama_menu)) ? "" : " AND (A.nama_menu LIKE '%$nama_menu%')";
        $where_jenis_menu = (empty($jenis_menu)) ? "" : " AND A1.JENIS='$jenis_menu'";
        
        $sql = "(SELECT A.id_menu,A.nama_menu,A.path_menu,A.Aktif,(CASE WHEN A.parent_1 IS NULL THEN 'MAIN MENU'
                        WHEN A.parent_1 IS NOT NULL AND A.parent_2 IS NULL THEN 'SUB MENU 1'
                        WHEN A.parent_1 IS NOT NULL AND A.parent_2 IS NOT NULL THEN 'GROUP MENU'
                ELSE '' END ) AS JENIS  
                FROM menu A WHERE 1=1
                $where_nama_menu
                ORDER BY A.nama_menu) A1 WHERE 1=1 $where_jenis_menu";
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

        $where_kode_pegawai = ($akses_admin > 0) ? "" : " AND A.kode_pegawai='$kode_pegawai'";
        $sql = "(SELECT A.id_menu 
                FROM menu A
                ORDER BY A.nama_menu) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT A.id_menu,A.nama_menu,A.path_menu,A.Aktif,(CASE WHEN A.parent_1 IS NULL THEN 'MAIN MENU'
                                        WHEN A.parent_1 IS NOT NULL AND A.parent_2 IS NULL THEN 'SUB MENU 1'
                                        WHEN A.parent_1 IS NOT NULL AND A.parent_2 IS NOT NULL THEN 'GROUP MENU'
                                ELSE '' END ) AS JENIS  
                                FROM menu A WHERE A.id_menu='$kode'");
        $data["list"] = $sql;
        $this->load->view("f_edit_menu",$data);        
    }

    function update_data()
    {
        $id_menu   = $this->input->post("id_menu");
        $nama_menu = strtoupper($this->input->post("nama_menu"));
        $aktif     = ($this->input->post("aktif"));

        $this->db->trans_start();
        $data["nama_menu"] = $nama_menu;
        $data["Aktif"]     = $aktif;

        $simpan_data = $this->db->update("menu",$data,["id_menu" => $id_menu]);
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }
	
}
