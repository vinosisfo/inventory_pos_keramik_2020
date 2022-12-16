<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller  {
	
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
        $this->load->view("f_user");

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
            $row [] = '<button type="button" class="btn btn-info btn-xs" onclick="edit_data(\''.$field->kode_pegawai.'\')">Edit</button>';
            $row [] = ($this->session->userdata("akses_admin") > 0) ? '<a onclick="detail_data(\''.$field->kode_pegawai.'\')" href="#" class="btn btn-danger btn-xs">Detail</a>' : "";
            $row [] = ($this->session->userdata("akses_admin") > 0) ? '<a onclick="hapus_data(\''.$field->kode_pegawai.'\')" href="#" class="btn btn-warning btn-xs">Hapus</a>' : "";
            $row [] = $field->kode_pegawai;
            $row [] = $field->username;
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
        $kode_user    = $this->input->post("kode_user");
        // var_dump($akses_admin,$kode_pegawai); die();
        
        $where_kode_user    = (empty($kode_user)) ? "" : " AND (A.kode_pegawai LIKE '%$kode_user%' OR A.Username LIKE '%$kode_user%')";
        $where_kode_pegawai = ($akses_admin > 0) ? "" : " AND A.kode_pegawai='$kode_pegawai'";
        
        $sql = "(SELECT * FROM user A
                WHERE 1=1 $where_kode_pegawai 
                $where_kode_user) A1";
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
        $sql = "(SELECT * FROM user A WHERE 1=1 $where_kode_pegawai) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");

        $data["format"]       = $format;
        $data["kode_pegawai"] = $this->m_kode->kode_user();
        $this->load->view("f_input_user",$data);
    }

    function simpan_data()
    {
        $kode_pegawai = $this->m_kode->kode_user();
        $username     = strtoupper($this->input->post("username"));
        $password     = md5($this->input->post("password"));

        $this->db->trans_start();
        $data =[
                "kode_pegawai" => $kode_pegawai,
                "username"     => $username,
                "password"     => $password,
                ];
        $simpan_data = $this->db->insert("user",$data);
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT * FROM user A WHERE A.kode_pegawai='$kode'");
        $data["list"] = $sql;
        $this->load->view("f_edit_user",$data);        
    }

    function update_data()
    {
        $kode_pegawai  = $this->input->post("kode_pegawai");
        $username      = strtoupper($this->input->post("username"));
        $password      = ($this->input->post("password"));
        $password_lama = $this->input->post("password_lama");

        $this->db->trans_start();
        $data["username"]= $username;
        if(strlen($password) > 0){
            $data["password"] = md5($password);
        }

        $simpan_data = $this->db->update("user",$data,["kode_pegawai" => $kode_pegawai]);
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function hapus_data()
    {
        $kode = $this->input->post("kode");
        $sql  = $this->db->query("DELETE A FROM user A WHERE A.kode_pegawai='$kode'");
        
        $pesan["pesan"] = "ok";
        echo json_encode($pesan);
    }

    function get_detail_data()
    {
        $kode_pegawai = $this->input->post("kode");
        $sql_header = $this->db->query("SELECT * FROM user A where A.kode_pegawai='$kode_pegawai'");
        $sql = $this->db->query("SELECT distinct A.id_akses,A.nama_akses,
                                (SELECT X.kode_pegawai FROM  user_akses_user X WHERE X.id_akses=A.id_akses AND X.kode_pegawai='$kode_pegawai') kode_pegawai
                                FROM akses_user A
                                WHERE A.Aktif=1");
        $data["head"] = $sql_header;
        $data["list"] = $sql;
        $this->load->view("f_detail_akses_menu",$data);
    }

    function simpan_akses_user()
    {
        $kode_pegawai = $this->input->post("kode_pegawai");
        $id_akses     = $this->input->post("id_akses_set");

        $this->db->trans_start();
        $delete_akses_menu = $this->db->query("DELETE FROM user_akses_user WHERE kode_pegawai='$kode_pegawai'");
        foreach ($id_akses as $key => $value) {
            if(strlen($id_akses[$key]) > 0){
                $simpan_menu = $this->db->query("INSERT INTO user_akses_user(kode_pegawai,id_akses) VALUES('$kode_pegawai','$id_akses[$key]')");
            }
        }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }
	
}
