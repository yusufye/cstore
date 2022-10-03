<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Rest_proses extends CI_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, X-Requested-With, x-xsrf-token");
        header("Content-Type: application/json; charset=utf-8");
        $this->load->model('rest_proses_model', 'restproses');
        $this->load->model('produk_model', 'produk');

        require APPPATH.'libraries/phpmailer/src/Exception.php';
        require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
        require APPPATH.'libraries/phpmailer/src/SMTP.php';
        // require APPPATH.'libraries/PHPMailer/PHPMailerAutoload.php';
    }

    function index() {
        $config = array(
            'name'      => 'Carvellonic',
            'website'   => 'https://carvellonic.com'
        );
        echo json_encode($config);
    }

    function proses_signin() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_signin($postjson);
        echo $data;
    }    

    function proses_signup() {
        $mail = new PHPMailer();
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_signup($postjson,$mail);
        echo $data;
    }    

    function proses_aktivasi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_aktivasi($postjson);
        echo $data;
    }

    function kirim_ulang_aktivasi() {
        $mail = new PHPMailer();
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->kirim_ulang_aktivasi($postjson,$mail);
        echo $data;
    }

    function proses_edit_akun() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_edit_akun($postjson);
        echo $data;
    }

    function proses_edit_password() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_edit_password($postjson);
        echo $data;
    }

    function proses_add_cart() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_add_cart($postjson);
        echo $data;
    }

    function proses_del_cart() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_del_cart($postjson);
        echo $data;
    }

    function proses_add_whislist() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_add_whislist($postjson);
        echo $data;
    }

    function proses_option_alamat() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_option_alamat($postjson);
        echo $data;
    }

    function proses_simpan_transaksi() {
        $mail = new PHPMailer();
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_simpan_transaksi($postjson,$mail);
        echo $data;
    }

    function proses_batalkan_transaksi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_batalkan_transaksi($postjson);
        echo $data;
    }

    function proses_tiba_transaksi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_tiba_transaksi($postjson);
        echo $data;
    }

    function proses_update_transaksi_midtrans() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_update_transaksi_midtrans($postjson);
        echo $data;
    }

    function proses_kirim_bukti_bayar() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_kirim_bukti_bayar($postjson);
        echo $data;
    }

    function proses_kirim_bukti_bayar_topup() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_kirim_bukti_bayar_topup($postjson);
        echo $data;
    }

    function proses_simpan_ulasan_rating() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_simpan_ulasan_rating($postjson);
        echo $data;
    }

    function proses_baca_notifikasi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_baca_notifikasi($postjson);
        echo $data;
    }

    function proses_topup_saldo() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_topup_saldo($postjson);
        echo $data;
    }

    function proses_update_topup_midtrans() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restproses->proses_update_topup_midtrans($postjson);
        echo $data;
    }

    //Masukan function selanjutnya disini
}
?>