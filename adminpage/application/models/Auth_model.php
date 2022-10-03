<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function cronJob() {
    }

    public function emailTestSmtp($mailto,$mail) {
        return emailTestSmtp($mailto,$mail);
    }

    public function prosesLogin($username) {
        $this->db->where("username='$username'");
        return $this->db->get('m_pengelola')->row_array();
    }

    public function getById($tabel,$field,$primary_id) {
        return $this->db->get_where($tabel, [$field => $primary_id])->row_array();
    }

    public function pendapatan_bulan_ini($tgl_awal, $tgl_akhir) {
        $pendapatan = $this->db->query("SELECT sum(harga_total)as harga, sum(ongkos_kirim)as ongkir, sum(potongan_total)as potongan, sum(diskon_all_total)as d_all, sum(potongan_voucher)as voucher FROM tx_transaksi WHERE is_status='s' AND date(tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'")->row_array();
        return $pendapatan;
    }

    public function resultData($tabel,$where='',$isi='*',$orderby='') {
        if ($where=='') {
            $where = ' ';
        }else{
            $where = ' WHERE '.$where;
        }

        if ($orderby=='') {
            $orderby = ' ';
        }else{
            $orderby = ' ORDER BY '.$orderby;
        }

        $query = "SELECT $isi FROM $tabel $where $orderby";
        return $this->db->query($query)->result_array();
    }

    public function rowData($tabel,$where='',$isi = '*',$orderby='') {
        if ($where=='') {
            $where = ' ';
        }else{
            $where = ' WHERE '.$where;
        }

        if ($orderby=='') {
            $orderby = ' ';
        }else{
            $orderby = ' ORDER BY '.$orderby;
        }

        $query = "SELECT $isi FROM $tabel $where $orderby";
        return $this->db->query($query)->row_array();
    }

    public function countRow($tabel,$where='',$isi='*') {

        if ($where=='') {
            $where = '';
        }else{
            $where = ' WHERE '.$where;
        }

        $query = "SELECT $isi FROM $tabel $where";
        return $this->db->query($query)->num_rows();
    }

    public function sumRow($tabel,$where='',$isi) {

        if ($where=='') {
            $where = ' ';
        }else{
            $where = ' WHERE '.$where;
        }

        $query = "SELECT sum($isi) as total FROM $tabel $where";
        return $this->db->query($query)->row_array();
    }

    public function uploadGambar($nama_name='gambar',$old_gambar,$dir,$namafile = "file_"){

        if ($nama_name=='' || $nama_name==null) { $nama_name = 'gambar'; }

        $config['upload_path'] = './../assets/uploaded/'.$dir.'/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size']  = '2000';
        $config['max_width'] = '3024';
        $config['max_height'] = '3024';
        $config['remove_space'] = TRUE;
        $nmfile = $namafile.time();
        $config['file_name'] = $nmfile;
      
        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        $this->upload->initialize($config);
        if($this->upload->do_upload($nama_name)){ // Lakukan upload dan Cek jika proses upload berhasil
          // Jika berhasil :
          if ($old_gambar!='new') { unlink(FCPATH .'./../assets/uploaded/'.$dir.'/'.$old_gambar); }
          $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
          return $return;
        }else{
          // Jika gagal :
          $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
          return $return;
        }
    }

    public function hapusiFlag($tabel,$field,$primary_id,$setfield='is_hapus') {
        $this->db->set([
            $setfield     => 'y'
        ]);

        $this->db->where($field, $primary_id);
        $this->db->update($tabel);
    }

    public function produkTerlaris() {
        $data = array();
        $produk = $this->db->query("SELECT a.produk_id, a.nama_produk, sum(d.keluar) as best FROM m_produk a LEFT JOIN tx_stok d ON a.produk_id=d.produk_id WHERE a.is_active=1 AND a.is_hapus='n' GROUP BY a.produk_id ORDER BY best DESC LIMIT 50 ")->result_array();
 
        foreach ($produk as $rows) {
            $data[] = array(
                'produk_id'             => $rows['produk_id'],
                'nama_produk'           => $rows['nama_produk'],
                'terjual'               => $rows['best']
            );
        }

        return $data;
    }

    public function produkStokHabis() {
        $query = $this->db->query("SELECT a.produk_id, a.nama_produk, sum(d.masuk) as masuk, sum(d.keluar) as keluar, (sum(d.masuk)-sum(d.keluar)) as stok FROM m_produk a LEFT JOIN tx_stok d ON a.produk_id=d.produk_id WHERE a.is_active=1 AND a.is_hapus='n' GROUP BY a.produk_id ORDER BY stok ASC LIMIT 50")->result_array();
        return $query;
    }

    public function cek_transaksi_auto_batal() {
        $todaytime = date('Y-m-d H:i:s');
        $query_qtrx = $this->db->query("SELECT * FROM tx_transaksi WHERE is_status='p'")->result_array();

        foreach ($query_qtrx as $qtrx) {
            if ($qtrx['batas_waktu_pembayaran']<=$todaytime) {
                $bul = date('m'); $tahun = date('Y'); $tgl = date('mY');
                $nores = $this->db->query("SELECT max(substr(kode_stok,17,5))as no FROM tx_stok WHERE substr(kode_stok,5,4)='BTAL' AND substr(kode_stok,12,4)='$tahun'")->row_array();
                $has=intval($nores['no'])+1;
                $noTrx="TRX/BTAL/".$tgl."/".sprintf("%05d",$has);

                $query_stok = $this->db->query("SELECT * FROM tx_stok WHERE kode_stok='$qtrx[no_transaksi]'")->result_array();
                    
                foreach ($query_stok as $rows) {
                    $stok = $this->produk->cekStok($rows['produk_id'],$rows['produk_warna_id'],$rows['produk_ukuran_id']);
                    $data = [
                        'kode_stok'           => $noTrx,
                        'status_stok'         => 3, // stok kembali karna batal ~~
                        'produk_id'           => $rows['produk_id'],
                        'produk_warna_id'     => $rows['produk_warna_id'],
                        'produk_ukuran_id'    => $rows['produk_ukuran_id'],
                        'admin_id'            => $this->session->userdata('p_id'),
                        'cust_id'             => '0',
                        'awal'                => $stok['akhir'],
                        'masuk'               => $rows['keluar'],
                        'keluar'              => 0,
                        'akhir'               => $stok['akhir']+$rows['keluar'],
                        'label_stok'          => 'BTL', // default untuk batal
                        'keterangan_stok'     => 'BATAL-'.$rows['kode_stok'],
                        'created_at'          => date("Y-m-d H:i:s")
                    ];

                    $this->db->insert('tx_stok', $data);

                    $this->db->set(['keterangan_stok' => 'BATAL-'.$rows['kode_stok']]);
                    $this->db->where('kode_stok', $rows['kode_stok']);
                    $this->db->update('tx_stok');
                }

                $this->db->set([
                    'is_status' => 'b',
                    'if_cancel' => 'Transaksi telah dibatalkan pada <b>'.indo(date('Y-m-d')).' '.date('H:i').'</b> waktu setempat.<br>Oleh <b>Admin</b>.',
                    'tgl_konfirmasi' => date("Y-m-d H:i:s")
                ]);
                $this->db->where('no_transaksi', $qtrx['no_transaksi']);
                $this->db->update('tx_transaksi');

                $dataInfo = [
                    'cust_id'       => $qtrx['cust_id'],
                    'sync_id'       => $qtrx['unique_id'],
                    'tipe_notif'    => 'trx',
                    'judul_notif'   => 'Transaksi '.$qtrx['no_transaksi'].' Telah Dibatalkan',
                    'ket_notif'     => 'Transaksi telah dibatalkan oleh admin.',
                    'is_read'       => 'n',
                    'created_at'    => date("Y-m-d H:i:s")
                ];

                $this->db->insert('tx_notifikasi', $dataInfo);
            }
        }
    }

    public function cek_transaksi_topup_auto_batal() {
        $todaytime = date('Y-m-d H:i:s');
        $query_qtrx = $this->db->query("SELECT * FROM tx_topup WHERE is_status='p'")->result_array();

        foreach ($query_qtrx as $qtrx) {
            if ($qtrx['batas_waktu_pembayaran']<=$todaytime) {

                $this->db->set([
                    'is_status' => 'b',
                    'if_cancel' => 'Transaksi telah dibatalkan pada <b>'.indo(date('Y-m-d')).' '.date('H:i').'</b> waktu setempat.<br>Oleh <b>Admin</b>.'
                ]);
                $this->db->where('topup_id', $qtrx['topup_id']);
                $this->db->update('tx_topup');

                $dataInfo = [
                    'cust_id'       => $qtrx['cust_id'],
                    'sync_id'       => $qtrx['unique_id'],
                    'tipe_notif'    => 'topup',
                    'judul_notif'   => 'Topup '.$qtrx['kode_topup'].' Telah Dibatalkan',
                    'ket_notif'     => 'Topup telah dibatalkan oleh admin.',
                    'is_read'       => 'n',
                    'created_at'    => date("Y-m-d H:i:s")
                ];

                $this->db->insert('tx_notifikasi', $dataInfo);
            }
        }
    }

}
