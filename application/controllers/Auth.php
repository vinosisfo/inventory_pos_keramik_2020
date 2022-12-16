<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{ 
		$this->load->view("login");
	}

	function login()
	{
		$username = $this->input->post("username");
		$password = md5($this->input->post("password"));

		$sql = $this->db->from("user A")
						->where("(A.kode_pegawai='$username' OR A.username='$username')",)
						->where("A.password",$password)
						->get();
		if($sql->num_rows() > 0)
		{
			$data = $sql->row();

			$sess_data['kode_pegawai'] = $data->kode_pegawai;
			$sess_data['username']     = $data->username;

			$sql_admin = $this->db->query("SELECT A.id_akses
											FROM akses_user A 
											LEFT JOIN user_akses_user B ON B.id_akses=A.ID_AKSES 
											WHERE A.id_akses=1 AND B.kode_pegawai='$data->kode_pegawai'");
		
			$sess_data["akses_admin"] = $sql_admin->num_rows();
			$this->session->set_userdata($sess_data);
			$status = "ok";
			
		}
		else
		{
			$status = "Gagal";
		}
		$data_res["pesan"] = $status;
		echo json_encode($data_res);
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('Auth'));
	}

	function update_password()
	{
		$username = $this->input->post("username");
		$password = md5($this->input->post("password"));

		$cek_username = $this->db->query("SELECT A.kode_pegawai FROM user A where A.kode_pegawai='$username'");
		if($cek_username->num_rows() ==0){
			$pesan="kosong";
		} else {
			$sql   = $this->db->query("UPDATE User A SET A.password='$password' WHERE A.kode_pegawai='$username'");
			$pesan = "ok";
		}
		$hasil["pesan"] = $pesan;
		echo json_encode($hasil);
	}
	
}
