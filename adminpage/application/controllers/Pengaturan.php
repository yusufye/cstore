<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('auth_model', 'auth');
        $this->load->model('menu_model', 'menu');
        $this->load->model('pengaturan_model', 'pengaturan');
    }

    public function sistem($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'Sistem';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('global_diskon', '', 'required');
        $this->form_validation->set_rules('metode_pembayaran', '', 'required');
        $this->form_validation->set_rules('metode_ulasan', '', 'required');
        $this->form_validation->set_rules('metode_rating', '', 'required');
        $this->form_validation->set_rules('google_client', '', 'required');
        $this->form_validation->set_rules('google_secret', '', 'required');
        $this->form_validation->set_rules('google_redirect', '', 'required');
        $this->form_validation->set_rules('midtrans_tipekey', '', 'required');
        $this->form_validation->set_rules('midtrans_clientkey', '', 'required');
        $this->form_validation->set_rules('midtrans_serverkey', '', 'required');
        $this->form_validation->set_rules('fitur_saldo', '', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/sistem', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $res = $this->pengaturan->editPengaturan('sistem');
            if ($res=='ok') {
                echo 'closemodalreload~Perubahan data berhasil.';
            }else{
                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
            }
        }
    }

    public function seoui($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'SEO & UI';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('meta_title', 'Meta Title', 'required');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'required');
        $this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'required');
        $this->form_validation->set_rules('ui_navbar', 'UI Navbar', 'required');
        $this->form_validation->set_rules('ui_kategori', 'UI Kategori', 'required');
        $this->form_validation->set_rules('ui_footer', 'UI Footer', 'required');
        $this->form_validation->set_rules('label_footer', 'Label Footer', 'required');
        $this->form_validation->set_rules('footer', 'Footer', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/seo-ui', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $old_gambar = $this->input->post('gambar_old');
            $cekimg = $_FILES['gambar']['name'];
            $old_gambar2 = $this->input->post('gambar_old2');
            $cekimg2 = $_FILES['gambar2']['name'];
            $old_gambar3 = $this->input->post('gambar_old3');
            $cekimg3 = $_FILES['gambar3']['name'];
            $old_gambar4 = $this->input->post('gambar_old4');
            $cekimg4 = $_FILES['gambar4']['name'];

            $upload = $this->auth->uploadGambar('gambar',$old_gambar,'logo','logo_admin_');
            if($upload['result'] == "success" || $cekimg==''){
                $upload2 = $this->auth->uploadGambar('gambar2',$old_gambar2,'logo','favicon_');
                if($upload2['result'] == "success" || $cekimg2==''){
                    $upload3 = $this->auth->uploadGambar('gambar3',$old_gambar3,'logo','empty_cart_');
                    if($upload3['result'] == "success" || $cekimg3==''){
                        $upload4 = $this->auth->uploadGambar('gambar4',$old_gambar4,'logo','logo_');
                        if($upload4['result'] == "success" || $cekimg4==''){
                            $res = $this->pengaturan->editPengaturan('seo-ui',$upload,$cekimg,$old_gambar,$upload2,$cekimg2,$old_gambar2,$upload3,$cekimg3,$old_gambar3,$upload4,$cekimg4,$old_gambar4);
                            if ($res=='ok') {
                                echo 'closemodalreload~Perubahan data berhasil.';
                            }else{
                                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
                                if ($cekimg!='') {
                                    unlink(FCPATH . './../assets/uploaded/logo/' . $upload['file']['file_name']);
                                }
                                if ($cekimg2!='') {
                                    unlink(FCPATH . './../assets/uploaded/logo/' . $upload2['file']['file_name']);
                                }
                                if ($cekimg3!='') {
                                    unlink(FCPATH . './../assets/uploaded/logo/' . $upload3['file']['file_name']);
                                }
                                if ($cekimg4!='') {
                                    unlink(FCPATH . './../assets/uploaded/logo/' . $upload4['file']['file_name']);
                                }
                            }
                        }else{
                            unlink(FCPATH . './../assets/uploaded/logo/' . $upload3['file']['file_name']);
                            echo 'no~Logo Toko : '.$upload4['error'];
                        }
                    }else{
                        unlink(FCPATH . './../assets/uploaded/logo/' . $upload2['file']['file_name']);
                        echo 'no~Empty Cart : '.$upload3['error'];
                    }
                }else{
                    unlink(FCPATH . './../assets/uploaded/logo/' . $upload['file']['file_name']);
                    echo 'no~Favicon : '.$upload2['error'];
                }
            }else{
                echo 'no~Logo Admin : '.$upload['error'];
            }
        }
    }

    public function tentang($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'Tentang Kami';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('tentang_kami', 'Tentang Kami', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/tentang-kami', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $res = $this->pengaturan->editPengaturan('tentang-kami');
            if ($res=='ok') {
                echo 'closemodalreload~Perubahan data berhasil.';
            }else{
                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
            }
        }
    }

    public function kontak($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'Kontak Kami';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('kontak_kami', 'Kontak Kami', 'required');
        $this->form_validation->set_rules('email_address', 'Email', 'required');
        $this->form_validation->set_rules('call_center', 'Call', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/kontak-kami', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $res = $this->pengaturan->editPengaturan('kontak-kami');
            if ($res=='ok') {
                echo 'closemodalreload~Perubahan data berhasil.';
            }else{
                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
            }
        }
    }

    public function faq($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'FAQ';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('faq_asked', '', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/faq', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $res = $this->pengaturan->editPengaturan('faq');
            if ($res=='ok') {
                echo 'closemodalreload~Perubahan data berhasil.';
            }else{
                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
            }
        }
    }

    public function privacy($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'Privacy Policy';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('privacy_policy', 'Privacy', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/privacy-policy', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $res = $this->pengaturan->editPengaturan('privacy-policy');
            if ($res=='ok') {
                echo 'closemodalreload~Perubahan data berhasil.';
            }else{
                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
            }
        }
    }

    public function terms($nb = null) {
        cek_menu_access();
        $data['nmenu'] = 'Pengaturan';
        $data['title'] = 'Terms & Conditions';
        $data['auth'] = $this->auth->getById('m_pengelola','pengelola_id',$this->session->userdata('p_id'));
        $data['sistem'] = $this->auth->rowData('_setting');

        $this->form_validation->set_rules('terms_conditions', '', 'required');

        if ($this->form_validation->run() == false) {
            if ($nb=='proses') {
                echo 'no~default';
            }else{
                $this->load->view('templates/in_header', $data);
                $this->load->view('templates/in_topbar', $data);
                $this->load->view('module/pengaturan/terms', $data);
                $this->load->view('templates/in_footer');
            }
        } else {
            $res = $this->pengaturan->editPengaturan('terms');
            if ($res=='ok') {
                echo 'closemodalreload~Perubahan data berhasil.';
            }else{
                echo 'no~Perubahan gagal disimpan, pastikan kolom sudah terisi silahkan coba lagi.';
            }
        }
    }



}
