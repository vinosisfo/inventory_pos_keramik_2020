<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
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

        $this->load->view("f_home");
	}
	
}
