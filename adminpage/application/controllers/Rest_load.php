<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_load extends CI_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, X-Requested-With, x-xsrf-token");
        header("Content-Type: application/json; charset=utf-8");
        $this->load->model('rest_load_model', 'restload');
        $this->load->model('produk_model', 'produk');
    }

    //Menampilkan data kontak
    function index() {
        $config = array(
            'name'      => 'Carvellonic',
            'website'   => 'https://carvellonic.com'
        );
        echo json_encode($config);
    }

    function load_pengaturan() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_pengaturan($postjson);
        echo $data;
    }

    function load_customer() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_customer($postjson);
        echo $data;
    }

    function load_alamat_customer() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_alamat_customer($postjson);
        echo $data;
    }

    function load_slider() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_slider($postjson);
        echo $data;
    }

    function load_kategori() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori($postjson);
        echo $data;
    }

    function load_kategori_det() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori_det($postjson);
        echo $data;
    }

    function load_sub_kategori() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori($postjson);
        echo $data;
    }

    function load_sub_kategori_det() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori_det($postjson);
        echo $data;
    }

    function load_sub_kategori_lv2() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori_lv2($postjson);
        echo $data;
    }

    function load_kategori_pilihan_only() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori_pilihan_only($postjson);
        echo $data;
    }

    function load_kategori_pilihan() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori_pilihan($postjson);
        echo $data;
    }

    function load_sub_kategori_pilihan_zero() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori_pilihan_zero($postjson);
        echo $data;
    }

    function load_sub_kategori_pilihan_satu() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori_pilihan_satu($postjson);
        echo $data;
    }

    function load_sub_kategori_pilihan_dua() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori_pilihan_dua($postjson);
        echo $data;
    }

    function load_sub_kategori_pilihan_tiga() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_sub_kategori_pilihan_tiga($postjson);
        echo $data;
    }

    function load_kategori_lv1() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori_lv1($postjson);
        echo $data;
    }

    function load_kategori_lv2() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori_lv2($postjson);
        echo $data;
    }

    function load_kategori_lv2_all() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kategori_lv2_all($postjson);
        echo $data;
    }

    function load_produk() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_produk($postjson);
        echo $data;
    }

    function load_produk_varian() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_produk_varian($postjson);
        echo $data;
    }

    function load_produk_stok() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_produk_stok($postjson);
        echo $data;
    }

    function load_produk_search() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_produk_search($postjson);
        echo $data;
    }

    function load_new_arrivals() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_new_arrivals($postjson);
        echo $data;
    }

    function load_cart() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_cart($postjson);
        echo $data;
    }

    function load_item_cart() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_item_cart($postjson);
        echo $data;
    }

    function load_jumlah_cart() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_jumlah_cart($postjson);
        echo $data;
    }

    function load_provinsi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_provinsi($postjson);
        echo $data;
    }

    function load_kabkot() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kabkot($postjson);
        echo $data;
    }

    function load_kurir_cost() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kurir_cost($postjson);
        echo $data;
    }

    function load_kurir_pilihan() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_kurir_pilihan($postjson);
        echo $data;
    }

    function load_riwayat_transaksi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_riwayat_transaksi($postjson);
        echo $data;
    }

    function load_cek_resi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_cek_resi($postjson);
        echo $data;
    }

    function load_ulasan_transaksi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_ulasan_transaksi($postjson);
        echo $data;
    }

    function load_ulasan_produk() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_ulasan_produk($postjson);
        echo $data;
    }

    function load_notifikasi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_notifikasi($postjson);
        echo $data;
    }

    function load_auto_cek_notifikasi() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_auto_cek_notifikasi($postjson);
        echo $data;
    }

    function load_voucher() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_voucher($postjson);
        echo $data;
    }

    function check_voucher() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->check_voucher($postjson);
        echo $data;
    }

    function load_riwayat_topup() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_riwayat_topup($postjson);
        echo $data;
    }

    function load_riwayat_topup_det() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_riwayat_topup_det($postjson);
        echo $data;
    }

    function load_riwayat_saldo() {
        $postjson = json_decode(file_get_contents('php://input'), true);
        $data = $this->restload->load_riwayat_saldo($postjson);
        echo $data;
    }

    //Masukan function selanjutnya disini
}

?>