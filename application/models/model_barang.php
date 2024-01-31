<?php
class Model_barang extends CI_Model{
    public function tampil_data(){
        return $this->db->get('tb_barang');
    }
    public function tambah_barang($data,$table){
        $this->db->insert($table,$data);
    }

    public function edit_barang($where,$table){
        return $this->db->get_where($table,$where);
    }

    public function hapus_produk($where,$table){
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function detail_brg($id_brg){
        $result = $this->db->where('id_brg', $id_brg)->get('tb_barang');
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return false;
        }
    }

    public function get_produk_by_id($id){
        $hsl=$this->db->query("SELECT * FROM tb_barang WHERE id_brg='$id'");
        if($hsl->num_rows()>0){
            foreach ($hsl->result() as $data) {
                $hasil=array(
                    'id_brg' => $data->id,
                    'nama_barang' => $data->nama_barang,
                    'keterangan' => $data->keterangan,
                    'kategori' => $data->kategori,
                    'harga' => $data->harga,
                    'stok' => $data->stok,
                    'gambar' => $data->gambar
                );
            }
        }
        return $hasil;
    }

    public function find($id){
        $result = $this->db->where('id_brg', $id)
                           ->limit(1)
                           ->get('tb_barang');
        if($result->num_rows() > 0){
            return $result->row();
        }else{
            return array();
        }
    }
}