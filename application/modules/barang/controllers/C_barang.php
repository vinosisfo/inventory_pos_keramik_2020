<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_barang extends MX_Controller  {
	
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
        $this->load->view("f_barang");

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
            $row [] = '<button type="button" class="btn btn-info btn-xs" onclick="edit_data(\''.$field->KodeBarang.'\')">Edit</button>';
            $row [] = $field->KodeBarang;
            $row [] = $field->NamaBarang;
            $row [] = $field->Deskripsi;
            $row [] = number_format($field->Diskon,2);
            $row [] = number_format($field->Diskon_Jual,2);
            $row [] = number_format($field->UNTUNG,2);
            $row [] = number_format($field->Diskon_Reject,2);
            $row [] = number_format($field->HARGA_TERAKHIR);
            $row [] = '<img src="'.base_url('assets/foto_barang/'.$field->Foto).'" onclick="view_gambar(this)" class="img-responsive" id="foto_barang_'.$no.'" onerror="this.onerror=null;this.src=\''.base_url('assets/adminbsb/images/image-not-found.jpg').'\'">';
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
        
        $where_kode_nama = (empty($kode_nama)) ? "" : " AND (A.KodeBarang LIKE '%$kode_nama%' OR A.NamaBarang LIKE '%$kode_nama%')";
        $where_aktif     = (empty($aktif)) ? "" : (($aktif==1) ? " AND A.Aktif='$aktif'" : " AND A.Aktif='0'");

        $sql = "(SELECT A.KodeBarang,A.NamaBarang,A.Deskripsi,IFNULL(A.Diskon,0) Diskon,IFNULL(A.Diskon_Jual,0) Diskon_Jual, IFNULL(A.Keuntungan_Persen,0) UNTUNG 
                ,0 AS HARGA_TERAKHIR ,A.Foto,A.Aktif,A.Diskon_Reject
                FROM barang A
                WHERE 1=1 $where_kode_nama
                $where_aktif) A1
                ORDER BY A1.NamaBarang";
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

        $sql = "(SELECT A.Kodebarang
                FROM barang A
                WHERE 1=1) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input()
    {
        $format = $this->input->post("format");

        $data["format"] = $format;
        $this->load->view("f_input_barang",$data);
    }

    function simpan_data()
    {
        $kode_barang   = $this->m_kode->kode_barang();
        $nama_barang   = strtoupper($this->input->post("nama_barang"));
        $deskripsi     = ($this->input->post("deskripsi"));
        $diskon        = ($this->input->post("diskon"));
        $diskon_jual   = ($this->input->post("diskon_jual"));
        $diskon_reject = ($this->input->post("diskon_reject"));
        $untung        = ($this->input->post("untung"));
        $foto          = ($this->input->post("foto"));

        $diskon_set        = str_replace(",","",$diskon);
        $diskon_jual_set   = str_replace(",","",$diskon_jual);
        $diskon_reject_set = str_replace(",","",$diskon_reject);
        $untung_set        = str_replace(",","",$untung);

        $config['upload_path']   = 'assets/foto_barang/';
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        $config['max_size']      = 4024;
        $config['overwrite']     = TRUE;

        $this->load->library('upload');
        $files = $_FILES;

        $cek_barang = $this->db->query("SELECT A.NamaBarang FROM barang A WHERE A.NamaBarang='$nama_barang'");
        if($cek_barang->num_rows() > 0) {
            $pesan["pesan"] = "duplikat";
        } else {
            $this->db->trans_start();
            $data =[
                    "Kodebarang"        => $kode_barang,
                    "NamaBarang"        => $nama_barang,
                    "Deskripsi"         => $deskripsi,
                    "Diskon"            => ($diskon_set > 0) ? ($diskon_set) : 0,
                    "Diskon_Jual"       => ($diskon_jual_set > 0) ? ($diskon_jual_set) : 0,
                    "Diskon_Reject"     => ($diskon_reject_set > 0) ? ($diskon_reject_set) : 0,
                    "Keuntungan_Persen" => ($untung_set > 0) ? ($untung_set) : 0,
                    "Aktif"             => 1,
                    ];
            $simpan_data = $this->db->insert("barang",$data);

            $foto = $_FILES['foto']['name'];
            if(!empty($foto))
            {
                $_FILES['files']['name']     = $files['foto']['name'];
                $_FILES['files']['type']     = $files['foto']['type'];
                $_FILES['files']['tmp_name'] = $files['foto']['tmp_name'];
                $_FILES['files']['error']    = $files['foto']['error'];
                $_FILES['files']['size']     = $files['foto']['size'];

                $ext       = pathinfo($foto, PATHINFO_EXTENSION);
                $imageName = $kode_barang.'.'.$ext;

                $config['file_name'] = $imageName;
                $this->upload->initialize($config);
                if($this->upload->do_upload('files')){
                    $fileData = $this->upload->data();
                    $uploadData['picturePath'] = $fileData['file_name'];
                    $update_foto = $this->db->query("UPDATE barang A SET A.Foto='$imageName' 
                                                    WHERE A.KodeBarang='$kode_barang'");
                }
                else
                {
                    $error = array('error' => $this->upload->display_errors());
                }
            }

            
            $this->db->trans_complete();
            $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        }
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $format = $this->input->post("format");
        $kode   = $this->input->post("kode");

        $sql = $this->db->query("SELECT A.KodeBarang,A.NamaBarang,A.Deskripsi,IFNULL(A.Diskon,0) Diskon,IFNULL(A.Diskon_Jual,0) Diskon_Jual, IFNULL(A.Keuntungan_Persen,0) UNTUNG 
                                ,0 AS HARGA_TERAKHIR ,A.Foto,A.Aktif,A.Diskon_Reject
                                FROM barang A
                                WHERE 1=1 AND A.KodeBarang='$kode'");
        $data["list"] = $sql;
        $this->load->view("f_edit_barang",$data);        
    }

    function update_data()
    {
        $kode_barang   = $this->input->post("kode_barang");
        $nama_barang   = strtoupper($this->input->post("nama_barang"));
        $deskripsi     = ($this->input->post("deskripsi"));
        $diskon        = ($this->input->post("diskon"));
        $diskon_jual   = ($this->input->post("diskon_jual"));
        $diskon_reject = ($this->input->post("diskon_reject"));
        $untung        = ($this->input->post("untung"));
        $foto          = ($this->input->post("foto"));
        $aktif         = ($this->input->post("aktif"));

        $diskon_set        = str_replace(",","",$diskon);
        $diskon_jual_set   = str_replace(",","",$diskon_jual);
        $diskon_reject_set = str_replace(",","",$diskon_reject);
        $untung_set        = str_replace(",","",$untung);

        $config['upload_path']   = 'assets/foto_barang/';
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        $config['max_size']      = 4024;
        $config['overwrite']     = TRUE;

        $this->load->library('upload');
        $files = $_FILES;

        $this->db->trans_start();
        $data =[
                "NamaBarang"        => $nama_barang,
                "Deskripsi"         => $deskripsi,
                "Diskon"            => ($diskon_set > 0) ? ($diskon_set) : 0,
                "Diskon_Jual"       => ($diskon_jual_set > 0) ? ($diskon_jual_set) : 0,
                "Diskon_Reject"     => ($diskon_reject_set > 0) ? ($diskon_reject_set) : 0,
                "Keuntungan_Persen" => ($untung_set > 0) ? ($untung_set) : 0,
                "Aktif"             => $aktif,
                ];
        $simpan_data = $this->db->update("barang",$data,["Kodebarang" => $kode_barang]);

        $foto = $_FILES['foto']['name'];
        if(!empty($foto))
        {
            $_FILES['files']['name']     = $files['foto']['name'];
            $_FILES['files']['type']     = $files['foto']['type'];
            $_FILES['files']['tmp_name'] = $files['foto']['tmp_name'];
            $_FILES['files']['error']    = $files['foto']['error'];
            $_FILES['files']['size']     = $files['foto']['size'];

            $ext       = pathinfo($foto, PATHINFO_EXTENSION);
            $imageName = $kode_barang.'.'.$ext;

            $config['file_name'] = $imageName;
            $this->upload->initialize($config);
            if($this->upload->do_upload('files')){
                $fileData = $this->upload->data();
                $uploadData['picturePath'] = $fileData['file_name'];
                $update_foto = $this->db->query("UPDATE barang A SET A.Foto='$imageName' 
                                                WHERE A.KodeBarang='$kode_barang'");
            }
            else
            {
                $error = array('error' => $this->upload->display_errors());
            }
        }

        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }
	
}
