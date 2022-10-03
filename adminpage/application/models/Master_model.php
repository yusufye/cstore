<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function tambahAkses() {
        $idnya = urutId('m_role',"role_id");
        $data = [
            'role_id'    => $idnya,
            'nama_role'  => $this->input->post('nama_akses'),
            'is_active'  => $this->input->post('is_active')
        ];
        $this->db->insert('m_role', $data);

        $hakrole = $this->input->post('hak');
        if ($hakrole!='') {
            foreach ($hakrole as $key => $value) {
                if($value!=""){
                    $explo=explode("~",$value);
                    $value= $explo[1];
                    $query = $this->db->query("SELECT * FROM m_role_access WHERE id_role='$idnya' AND id_menu='$explo[0]'");
                    $cekrow = $query->num_rows();
                    if($cekrow==0){
                        $data = [
                            'id_role' => $idnya,
                            'id_menu' => $explo[0]
                        ];
                        $this->db->insert('m_role_access', $data);
                    }
                    $data = [
                        'id_role' => $idnya,
                        'id_menu' => $explo[1]
                    ];
                    $this->db->insert('m_role_access', $data);
                }
            }
        }
    }

    public function editAkses($primary_id) {
        $this->db->set([
            'nama_role'    => $this->input->post('nama_akses'),
            'is_active'    => $this->input->post('is_active')
        ]);
        $this->db->where('role_id', $primary_id);
        $this->db->update('m_role');

        $this->db->delete('m_role_access', ['id_role' => $primary_id]);
        $hakrole = $this->input->post('hak');
        if ($hakrole!='') {
            foreach ($hakrole as $key => $value) {
                if($value!=""){
                    $explo=explode("~",$value);
                    $value= $explo[1];
                    $query = $this->db->query("SELECT * FROM m_role_access WHERE id_role='$primary_id' AND id_menu='$explo[0]'");
                    $cekrow = $query->num_rows();
                    if($cekrow==0){
                        $data = [
                            'id_role' => $primary_id,
                            'id_menu' => $explo[0]
                        ];
                        $this->db->insert('m_role_access', $data);
                    }
                    $data = [
                        'id_role' => $primary_id,
                        'id_menu' => $explo[1]
                    ];
                    $this->db->insert('m_role_access', $data);
                }
            }        
        }
    }

    public function tambahBank($upload) {
        $data = [
            'nama_bank'        => $this->input->post('nama_bank'),
            'nama_rekening'    => $this->input->post('nama_rekening'),
            'nomor_rekening'   => $this->input->post('nomor_rekening'),
            'logo_image'       => $upload['file']['file_name'],
            'is_active'        => 1,
            'created_at'       => date('Y-m-d H:i:s')
        ];
        $this->db->insert('m_bank', $data);
    }

    public function editBank($primary_id,$upload,$cekimg,$old_gambar) {

        if ($cekimg=='') { $imgnya = $old_gambar; }else{ $imgnya = $upload['file']['file_name']; }

        $this->db->set([
            'nama_bank'        => $this->input->post('nama_bank'),
            'nama_rekening'    => $this->input->post('nama_rekening'),
            'nomor_rekening'   => $this->input->post('nomor_rekening'),
            'logo_image'       => $imgnya,
            'is_active'        => $this->input->post('is_active')
        ]);
        $this->db->where('bank_id', $primary_id);
        $this->db->update('m_bank');
    }

    public function tambahSlider($upload) {
        $data = [
            'tipe_slider'       => $this->input->post('tipe_slider'),
            'ket_slider'        => $this->input->post('ket_slider'),
            'logo_image'        => $upload['file']['file_name'],
            'is_active'         => 1,
            'created_at'        => date('Y-m-d H:i:s')
        ];
        $this->db->insert('m_slider', $data);
    }

    public function editSlider($primary_id,$upload,$cekimg,$old_gambar) {

        if ($cekimg=='') { $imgnya = $old_gambar; }else{ $imgnya = $upload['file']['file_name']; }

        $this->db->set([
            'tipe_slider'       => $this->input->post('tipe_slider'),
            'ket_slider'        => $this->input->post('ket_slider'),
            'logo_image'        => $imgnya,
            'is_active'         => $this->input->post('is_active')
        ]);
        $this->db->where('slider_id', $primary_id);
        $this->db->update('m_slider');
    }

    public function tambahPartner($upload) {
        $data = [
            'logo_image'       => $upload['file']['file_name'],
            'is_active'        => 1,
            'created_at'       => date('Y-m-d H:i:s')
        ];
        $this->db->insert('m_partner', $data);
    }

    public function editPartner($primary_id,$upload,$cekimg,$old_gambar) {

        if ($cekimg=='') { $imgnya = $old_gambar; }else{ $imgnya = $upload['file']['file_name']; }

        $this->db->set([
            'logo_image'       => $imgnya,
            'is_active'        => $this->input->post('is_active')
        ]);
        $this->db->where('partner_id', $primary_id);
        $this->db->update('m_partner');
    }

}
