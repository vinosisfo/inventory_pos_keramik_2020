<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kode extends CI_Model { 
  public function __construct()
  {
    parent::__construct();
    
  }

    function kode_user()
    {
      $tahun = date('y');
      $bulan = date('m');
      $hari  = date('d');

      $this->db->select('RIGHT(A.kode_pegawai,4) as kode', FALSE);
      $this->db->order_by('A.kode_pegawai','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get("user A");
      if($query->num_rows() <> 0){      
        $data = $query->row();
        $kode = intval($data->kode) + 1;
      }
      else 
      { 
        $kode = 1;    
      }
      $kodemax  = str_pad($kode, 4, "0", STR_PAD_LEFT);
      $kode_res = "PG".$tahun.$bulan.$kodemax;
      return $kode_res;  
    }

    function kode_barang()
    {
      $tahun = date('y');
      $bulan = date('m');
      $hari  = date('d');

      $this->db->select('RIGHT(A.KodeBarang,3) as kode', FALSE);
      $this->db->order_by('A.KodeBarang','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get("barang A");
      if($query->num_rows() <> 0){      
        $data = $query->row();
        $kode = intval($data->kode) + 1;
      }
      else 
      { 
        $kode = 1;    
      }
      $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
      $kode_res = "KBR".$tahun.$bulan.$kodemax;
      return $kode_res;
    }

    function kode_barang_masuk()
    {
      $tahun = date('y');
      $bulan = date('m');
      $hari  = date('d');

      $this->db->select('RIGHT(A.NomorBarangMasuk,3) as kode', FALSE);
      $this->db->order_by('A.NomorBarangMasuk','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get("barang_masuk A");
      if($query->num_rows() <> 0){      
        $data = $query->row();
        $kode = intval($data->kode) + 1;
      }
      else 
      { 
        $kode = 1;    
      }
      $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
      $kode_res = "BM".$tahun.$bulan.$hari.$kodemax;
      return $kode_res;
    }

    function kode_barang_keluar()
    {
      $tahun = date('y');
      $bulan = date('m');
      $hari  = date('d');

      $this->db->select('RIGHT(A.NomorBarangKeluar,3) as kode', FALSE);
      $this->db->order_by('A.NomorBarangKeluar','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get("barang_keluar A");
      if($query->num_rows() <> 0){      
        $data = $query->row();
        $kode = intval($data->kode) + 1;
      }
      else 
      { 
        $kode = 1;    
      }
      $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
      $kode_res = "BK".$tahun.$bulan.$hari.$kodemax;
      return $kode_res;
    }

    function kode_barang_retur()
    {
      $tahun = date('y');
      $bulan = date('m');
      $hari  = date('d');

      $this->db->select('RIGHT(A.NomorRetur,3) as kode', FALSE);
      $this->db->order_by('A.NomorRetur','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get("retur A");
      if($query->num_rows() <> 0){      
        $data = $query->row();
        $kode = intval($data->kode) + 1;
      }
      else 
      { 
        $kode = 1;    
      }
      $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
      $kode_res = "NR".$tahun.$bulan.$hari.$kodemax;
      return $kode_res;
    }
}
