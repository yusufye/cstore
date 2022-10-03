<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

    public function editPengaturan($menu,$upload=null,$cekimg=null,$old_gambar=null,$upload2=null,$cekimg2=null,$old_gambar2=null,$upload3=null,$cekimg3=null,$old_gambar3=null,$upload4=null,$cekimg4=null,$old_gambar4=null) {
        $proses = 'y';

        if ($menu=='sistem') {

            if ($this->input->post('metode_pembayaran')!='midtrans' && $this->input->post('metode_pembayaran')!='manual') {
                $metode_pay = 'manual';
            }else{
                $metode_pay = $this->input->post('metode_pembayaran');
            }

            if ($this->input->post('metode_ulasan')!='konfirmasi' && $this->input->post('metode_ulasan')!='auto' && $this->input->post('metode_ulasan')!='off') {
                $metode_ulsn = 'konfirmasi';
            }else{
                $metode_ulsn = $this->input->post('metode_ulasan');
            }

            if ($this->input->post('metode_rating')!='konfirmasi' && $this->input->post('metode_rating')!='auto' && $this->input->post('metode_rating')!='off') {
                $metode_rat = 'konfirmasi';
            }else{
                $metode_rat = $this->input->post('metode_rating');
            }

            $this->db->set([
                'global_diskon'             => $this->input->post('global_diskon'),
                'google_client'             => $this->input->post('google_client'),
                'google_secret'             => $this->input->post('google_secret'),
                'google_redirect'           => $this->input->post('google_redirect'),
                'midtrans_tipekey'          => $this->input->post('midtrans_tipekey'),
                'midtrans_clientkey'        => $this->input->post('midtrans_clientkey'),
                'midtrans_serverkey'        => $this->input->post('midtrans_serverkey'),
                'metode_pembayaran'         => $metode_pay,
                'metode_ulasan'             => $metode_ulsn,
                'metode_rating'             => $metode_rat,
                'fitur_saldo'               => $this->input->post('fitur_saldo')
                // 'maintance'                 => $this->input->post('maintance')
            ]);
        }else if ($menu=='seo-ui') {

            if ($cekimg=='') { $imgnya = $old_gambar; }else{ $imgnya = $upload['file']['file_name']; }
            if ($cekimg2=='') { $imgnya2 = $old_gambar2; }else{ $imgnya2 = $upload2['file']['file_name']; }
            if ($cekimg3=='') { $imgnya3 = $old_gambar3; }else{ $imgnya3 = $upload3['file']['file_name']; }
            if ($cekimg4=='') { $imgnya4 = $old_gambar4; }else{ $imgnya4 = $upload4['file']['file_name']; }

            $this->db->set([
                'meta_title'                => $this->input->post('meta_title'),
                'meta_description'          => $this->input->post('meta_description'),
                'meta_keywords'             => $this->input->post('meta_keywords'),
                'ui_navbar'                 => $this->input->post('ui_navbar'),
                'ui_kategori'               => $this->input->post('ui_kategori'),
                'ui_footer'                 => $this->input->post('ui_footer'),
                'label_footer'              => $this->input->post('label_footer'),
                'footer'                    => $this->input->post('footer'),
                'logo_image'                => $imgnya,
                'favicon_image'             => $imgnya2,
                'empty_cart_image'          => $imgnya3,
                'logo_toko_image'           => $imgnya4
            ]);
        }else if ($menu=='tentang-kami') {
            $this->db->set([
                'tentang_kami'              => $this->input->post('tentang_kami')
            ]);
        }else if ($menu=='kontak-kami') {
            $this->db->set([
                'google_maps'               => $this->input->post('google_maps'),
                'kontak_kami'               => $this->input->post('kontak_kami'),
                'email_address'             => $this->input->post('email_address'),
                'call_center'               => $this->input->post('call_center'),
                'call_center2'              => $this->input->post('call_center2'),
                'whatsapp'                  => $this->input->post('whatsapp'),
                'whatsapp2'                 => $this->input->post('whatsapp2'),
                'instagram'                 => $this->input->post('instagram'),
                'facebook'                  => $this->input->post('facebook')
            ]);
        }else if ($menu=='faq') {
            $this->db->set([
                'faq_asked'                 => $this->input->post('faq_asked')
            ]);
        }else if ($menu=='privacy-policy') {
            $this->db->set([
                'privacy_policy'            => $this->input->post('privacy_policy')
            ]);
        }else if ($menu=='terms') {
            $this->db->set([
                'terms_conditions'          => $this->input->post('terms_conditions')
            ]);
        }else{
            $proses = 'n';
        }

        if ($proses=='y') {
            $this->db->where('setting_id', '1');
            $res = $this->db->update('_setting');
        }else{
            $res = 'no';
        }


        if ($res==true) return 'ok'; else return 'no';
    }

}
