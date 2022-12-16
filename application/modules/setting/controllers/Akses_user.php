<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses_user extends MX_Controller  {
	
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
        $this->load->view("f_akses_user");

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
            $row [] = '<button type="button" class="btn btn-info btn-xs" onclick="edit_data(\''.$field->id_akses.'\')">Edit</button>';
            $row [] = ($field->id_akses==1) ? "" : '<a onclick="detail_data(\''.$field->id_akses.'\')" href="#" class="btn btn-danger btn-xs">Detail</a>';
            $row [] = $field->nama_akses;
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
        $nama_akses    = $this->input->post("nama_akses");
        
        $where_akses    = (empty($nama_akses)) ? "" : " AND (A.nama_akses LIKE '%$nama_akses%')";

        
        $sql = "(SELECT * FROM akses_user A
                WHERE 1=1 $where_akses) A1";
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

        $sql = "(SELECT * FROM akses_user A WHERE 1=1) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");

        $data["format"]       = $format;
        $this->load->view("f_input_akses_user",$data);
    }

    function simpan_data()
    {
        $nama_akses = strtoupper($this->input->post("nama_akses"));
        $aktif      = 1;

        $cek_data = $this->db->query("SELECT A.nama_akses FROM akses_user A where A.nama_akses='$nama_akses'");
        if($cek_data->num_rows() > 0){
            $pesan["pesan"] = "duplikat";
        } else {
            $this->db->trans_start();
            $data =[
                    "nama_akses" => $nama_akses,
                    "Aktif"      => $aktif,
                    ];
            $simpan_data = $this->db->insert("akses_user",$data);
            $this->db->trans_complete();
            $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        }
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT * FROM akses_user A WHERE A.id_akses='$kode'");
        $data["list"] = $sql;
        $this->load->view("f_edit_akses_user",$data);        
    }

    function update_data()
    {
        $id_akses   = $this->input->post("id_akses");
        $nama_akses = strtoupper($this->input->post("nama_akses"));
        $aktif      = $this->input->post("aktif");

        $this->db->trans_start();
        $data["nama_akses"] = $nama_akses;
        $data["Aktif"]      = $aktif;

        $simpan_data = $this->db->update("akses_user",$data,["id_akses" => $id_akses]);
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function get_detail_data()
    {
        $id_akses = $this->input->post("kode");

        $sql_head = $this->db->query("SELECT * FROM akses_user A where A.id_akses='$id_akses'");
        $sql = $this->db->query("SELECT * FROM (
                                    SELECT DISTINCT C.id_akses,C.nama_akses,A.id_menu,A.nama_menu,
                                    (SELECT X.nama_menu FROM menu X WHERE X.id_menu=A.parent_1) AS head_menu 
                                    FROM menu A 
                                    LEFT JOIN user_akses_menu B ON B.id_menu=A.id_menu
                                    LEFT JOIN akses_user C ON C.id_akses=B.ID_AKSES AND C.id_akses=$id_akses
                                    WHERE A.Aktif=1
                                ) A1 ORDER BY A1.head_menu");

        
        $data["list"] = $sql;
        $data["head"] = $sql_head;
        $this->load->view("f_detail_akses_user",$data);
    }

    function simpan_akses_user()
    {
        $id_akses = $this->input->post("id_akses");
        $id_menu = $this->input->post("id_menu_set");

        $this->db->trans_start();
        $delete_akses_menu = $this->db->query("DELETE FROM user_akses_menu WHERE id_akses='$id_akses'");
        foreach ($id_menu as $key => $value) {
            if(strlen($id_menu[$key]) > 0){
                $simpan_menu = $this->db->query("INSERT INTO user_akses_menu(id_akses,id_menu) VALUES('$id_akses','$id_menu[$key]')");
            }
        }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }
	
}
