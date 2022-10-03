<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

    public function totalTransaksi($keyvalue, $tgl_awal, $tgl_akhir){
        return $this->db->query("SELECT * FROM tx_transaksi WHERE (is_status='s' OR is_status='b') AND (no_transaksi LIKE '%$keyvalue%') AND date(tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'")->num_rows();
    }

    public function riwayatTransaksi($keyvalue, $perpage, $pagenum, $tgl_awal, $tgl_akhir){
        $data = array();

        if($pagenum>1){
            $limit = $perpage;
            $start = $perpage * ($pagenum-1);
        }else{
            $limit = $perpage;
            $start = 0;
        }

        $result = $this->db->query("SELECT * FROM tx_transaksi WHERE (is_status='s' OR is_status='b') AND (no_transaksi LIKE '%$keyvalue%') AND date(tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY transaksi_id DESC LIMIT $start,$limit")->result_array();

        return $result;
    }

    public function downloadriwayatTransaksi($tgl_awal, $tgl_akhir){
        $data = array();
        $totalbayar_all = 0;
        $totalbayar_sls = 0;
        $totalbayar_btl = 0;

        $result = $this->db->query("SELECT * FROM tx_transaksi WHERE (is_status='s' OR is_status='b') AND date(tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY transaksi_id DESC")->result_array();

        foreach ($result as $row) {
            if($row['is_status']=='s'){
                $st = 'Selesai';
            }else if($row['is_status']=='b'){
                $st = 'Dibatalkan';
            }else{
                $st = 'Unknown';
            }

            $totalbayar = ($row['harga_total']+$row['ongkos_kirim']-$row['potongan_total']-$row['diskon_all_total']);
            $totalbayar_all += $totalbayar;
            if($row['is_status']=='s'){
                $totalbayar_sls += $totalbayar;
            }else{
                $totalbayar_btl += $totalbayar;
            }

            $cart = array();
            $rescart = $this->db->query("SELECT a.*,c.nama_warna,d.ukuran_size FROM tx_transaksi_det a LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE a.no_transaksi='$row[no_transaksi]'")->result_array();
            foreach ($rescart as $rowx) {

                if ($rowx['nama_warna']!='') {
                    if ($rowx['nama_warna']=='Default') {
                        $rowx['nama_warna'] = "";
                    }else{
                        $rowx['nama_warna'] = $rowx['nama_warna'].", ";
                    }
                }

                if ($rowx['ukuran_size']!='') {
                    if ($rowx['ukuran_size']=='Default') {
                        $rowx['ukuran_size'] = "";
                    }else{
                        $rowx['ukuran_size'] = $rowx['ukuran_size'].", ";
                    }
                }

                if ($rowx['nama_warna']=='' && $rowx['ukuran_size']=='') {
                    $varian = '-';
                }else{
                    $varian = substr($rowx['nama_warna'].$rowx['ukuran_size'], 0,-2);
                }

                $cart[] = array(
                    'nama_produk'               => $rowx['nama_produk']." ".$varian,
                    'harga_produk'              => formatRupiahnorp($rowx['harga_produk']),
                    'jumlah_beli'               => $rowx['jumlah_beli'],
                    'total_harga_produk'        => formatRupiahnorp($rowx['total_harga_produk'])
                );
            }

            $data[] = array(
                'no_transaksi'              => $row['no_transaksi'],
                'tgl_transaksi'             => indo($row['tgl_transaksi']),
                'subtotal_bayar'            => formatRupiahnorp($totalbayar-$row['ongkos_kirim']),
                'ongkos_kirim'              => formatRupiahnorp($row['ongkos_kirim']),
                'potongan_voucher'          => "-".formatRupiahnorp($row['potongan_voucher']),
                'total_bayar'               => formatRupiah($totalbayar-$row['potongan_voucher']),
                'status'                    => $st,
                'cart'                      => $cart
            );
        }

        return json_encode(array('success'=>true, 'result'=>$data, 'total_all'=>formatRupiah($totalbayar_all), 'total_selesai'=>formatRupiah($totalbayar_sls), 'total_batal'=>formatRupiah($totalbayar_btl)));
    }

    public function transaksiDetail($id) {
        $data = array();

        $res = $this->db->query("SELECT a.*,b.nama_kurir,b.level_kurir,b.lama_pengiriman FROM tx_transaksi a JOIN tx_kurir b ON a.no_transaksi=b.no_transaksi WHERE a.transaksi_id='$id' ORDER BY a.transaksi_id DESC ")->result_array();
        foreach ($res as $rows) {

            if($rows['is_status']=='s'){
              $st = '<i class="fa fa-check"></i>&nbsp;&nbsp;Selesai';
              $stx = 'success';
            }else if($rows['is_status']=='b'){
              $st = '<i class="fa fa-times"></i>&nbsp;&nbsp;Dibatalkan';
              $stx = 'danger';
            }else if($rows['is_status']=='p'){
              $st = '<i class="fa fa-clock-o"></i>&nbsp;&nbsp;Menunggu konfirmasi';
              $stx = 'warning';
            }else if($rows['is_status']=='y'){
              $st = 'Sedang diproses';
              $stx = 'info';
            }else if($rows['is_status']=='k'){
              $st = 'Pesanan sedang diperjalanan (kurir)';
              $stx = 'info';
            }else{
              $st = 'Unknown';
              $stx = 'default';
            }

            $totalbayar = $rows['harga_total']+$rows['ongkos_kirim']-$rows['potongan_total']-$rows['diskon_all_total'];

            if ($rows['biller_code']=='bca') {
                $m_bayar = 'Bank BCA (VA)';
            }else if ($rows['biller_code']=='70012') {
                $m_bayar = 'Bank Mandiri (VA)';
            }else if ($rows['biller_code']=='bri') {
                $m_bayar = 'Bank BRI (VA)';
            }else if ($rows['biller_code']=='bni') {
                $m_bayar = 'Bank BNI (VA)';
            }else if ($rows['biller_code']=='permata') {
                $m_bayar = 'Bank Permata (VA)';
            }else{
                if ($rows['payment_type']=='gopay') {
                    $m_bayar = 'Gopay';
                }else if ($rows['payment_type']=='qris') {
                    $m_bayar = 'QRIS';
                }else{
                    $m_bayar = 'Layanan Transfer Bank';
                }
            }

            if ($rows['metode_pembayaran']=='saldo') {
                $m_bayar = 'Saldo';
            }

            $data[] = array(
                'transaksi_id'      => $rows['transaksi_id'],
                'no_transaksi'      => $rows['no_transaksi'],
                'unique_id'         => $rows['unique_id'],
                'is_status'         => $rows['is_status'],
                'status_lbl'        => $st,
                'status_clr'        => $stx,
                'subtotal_bayar'    => formatRupiahnorp($totalbayar-$rows['ongkos_kirim']),
                'ongkos_kirim'      => formatRupiahnorp($rows['ongkos_kirim']),
                'potongan_voucher'  => "-".formatRupiahnorp($rows['potongan_voucher']),
                'total_bayar'       => formatRupiah($totalbayar-$rows['potongan_voucher']),
                'kode_voucher'      => $rows['kode_voucher'],
                'metode_pembayaran' => $rows['metode_pembayaran'],
                'bukti_pembayaran'  => $rows['bukti_pembayaran'],
                'if_cancel'         => $rows['if_cancel'],
                'payment_type'      => $rows['payment_type'],
                'biller_code'       => $rows['biller_code'],
                'bill_key'          => $rows['bill_key'],
                'nomor_resi'        => $rows['nomor_resi'],
                'm_bayar'           => $m_bayar,
                'nama_kurir'        => $rows['nama_kurir'],
                'level_kurir'       => $rows['level_kurir'],
                'lama_pengiriman'   => $rows['lama_pengiriman'],
                'tgl_transaksi'     => indo($rows['tgl_transaksi'])
            );
        }

        $m_cart = array();
        $q_cart = $this->db->query("SELECT b.*,c.nama_warna,d.ukuran_size FROM tx_transaksi a JOIN tx_transaksi_det b ON a.no_transaksi=b.no_transaksi LEFT JOIN m_warna c ON b.warna_id=c.warna_id LEFT JOIN m_ukuran d ON b.ukuran_id=d.ukuran_id WHERE a.transaksi_id='$id'")->result_array();
        foreach ($q_cart as $rows) {
            if ($rows['nama_warna']!='') {
                if ($rows['nama_warna']=='Default') {
                    $rows['nama_warna'] = "";
                }else{
                    $rows['nama_warna'] = $rows['nama_warna'].", ";
                }
            }

            if ($rows['ukuran_size']!='') {
                if ($rows['ukuran_size']=='Default') {
                    $rows['ukuran_size'] = "";
                }else{
                    $rows['ukuran_size'] = $rows['ukuran_size'].", ";
                }
            }

            if ($rows['nama_warna']=='' && $rows['ukuran_size']=='') {
                $varian = '-';
            }else{
                $varian = substr($rows['nama_warna'].$rows['ukuran_size'], 0,-2);
            }

            $harga_produk = $rows['harga_produk']-$rows['potongan_harga']-$rows['diskon_all_produk'];
            $tharga_produk = $rows['total_harga_produk']-$rows['total_potongan_harga']-$rows['total_diskon_all_produk'];

            $hs_diskon = $harga_produk+$rows['potongan_harga']+$rows['diskon_all_produk'];
            $hst_diskon = ($harga_produk*$rows['jumlah_beli'])+$rows['total_potongan_harga']+$rows['total_diskon_all_produk'];

            if ($hs_diskon==$harga_produk) {
                $hs_diskon = '0';
            }

            $m_cart[] = array(
                'transaksi_det_id'          => $rows['transaksi_det_id'],
                'nama_produk'               => $rows['nama_produk'],
                'harga_produk'              => formatRupiahnorp($harga_produk),
                'hs_diskon'                 => formatRupiahnorp($hs_diskon),
                'jumlah_beli'               => $rows['jumlah_beli'],
                'total_harga_produk'        => formatRupiahnorp($tharga_produk),
                'hst_diskon'                => formatRupiahnorp($hst_diskon),
                'catatan_produk'            => $rows['catatan_produk'],
                'rating_produk'             => $rows['rating_produk'],
                'publikasi_rating'          => $rows['publikasi_rating'],
                'ulasan_produk'             => $rows['ulasan_produk'],
                'publikasi_ulasan'          => $rows['publikasi_ulasan'],
                'varian'                    => $varian
            );
        }
        $m_alamat = $this->db->query("SELECT b.*,c.* FROM tx_transaksi a JOIN m_customer_det b ON a.cust_det_id=b.cust_det_id JOIN m_customer c ON a.cust_id=c.cust_id WHERE a.transaksi_id='$id'")->row_array();
        
        return array('success'=>true, 'result'=>$data, 'm_cart'=>$m_cart, 'm_alamat'=>$m_alamat);
    }

    public function transaksiDel($id) {

        $qtrx = $this->db->query("SELECT * FROM tx_transaksi WHERE transaksi_id='$id'")->row_array();

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

        $res_trx_t = $this->db->insert('tx_notifikasi', $dataInfo);

        if ($qtrx['metode_pembayaran']=='saldo') {
            $saldo = $this->db->query("SELECT * FROM tx_saldo WHERE cust_id='$qtrx[cust_id]' ORDER BY saldo_id DESC LIMIT 1")->row_array();

            $totalbayar_benerbener = $qtrx['harga_total']+$qtrx['ongkos_kirim']-$qtrx['potongan_total']-$qtrx['diskon_all_total']-$qtrx['potongan_voucher'];

            $datasaldo = [
                'cust_id'             => $qtrx['cust_id'],
                'kode_saldo'          => $qtrx['no_transaksi'],
                'status_saldo'        => 1, // masuk ~~
                'tipe'                => 'trx',
                'awal'                => $saldo['akhir'],
                'masuk'               => $totalbayar_benerbener,
                'keluar'              => 0,
                'akhir'               => $saldo['akhir']+$totalbayar_benerbener,
                'created_at'          => date("Y-m-d H:i:s")
            ];

            $res_trx_t = $this->db->insert('tx_saldo', $datasaldo);
        }

        if ($res_trx_t==true) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function transaksiProses($id) {

        $qtrx = $this->db->query("SELECT * FROM tx_transaksi WHERE transaksi_id='$id'")->row_array();

        $this->db->set(['is_status' => 'y']);
        $this->db->where('transaksi_id', $id);
        $this->db->update('tx_transaksi');

        $dataInfo = [
            'cust_id'       => $qtrx['cust_id'],
            'sync_id'       => $qtrx['unique_id'],
            'tipe_notif'    => 'trx',
            'judul_notif'   => 'Transaksi '.$qtrx['no_transaksi'].' Telah Diproses',
            'ket_notif'     => 'Transaksi telah diproses, pesanan akan segera dikirim.',
            'is_read'       => 'n',
            'created_at'    => date("Y-m-d H:i:s")
        ];

        $res = $this->db->insert('tx_notifikasi', $dataInfo);

        if ($res==true) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function transaksiKirim($id) {

        $qtrx = $this->db->query("SELECT * FROM tx_transaksi WHERE transaksi_id='$id'")->row_array();

        $this->db->set(['is_status' => 'k']);
        $this->db->where('transaksi_id', $id);
        $this->db->update('tx_transaksi');

        $dataInfo = [
            'cust_id'       => $qtrx['cust_id'],
            'sync_id'       => $qtrx['unique_id'],
            'tipe_notif'    => 'trx',
            'judul_notif'   => 'Pesanan '.$qtrx['no_transaksi'].' Telah Dikirim',
            'ket_notif'     => 'Pesanan telah dikirim, pesanan kamu akan segera sampai ditujuan.',
            'is_read'       => 'n',
            'created_at'    => date("Y-m-d H:i:s")
        ];

        $res = $this->db->insert('tx_notifikasi', $dataInfo);

        if ($res==true) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function transaksiResi($id,$resi) {
        $this->db->set(['nomor_resi' => $resi]);
        $this->db->where('transaksi_id', $id);
        $res = $this->db->update('tx_transaksi');

        if ($res==true) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function transaksiSelesai($id) {

        $qtrx = $this->db->query("SELECT * FROM tx_transaksi WHERE transaksi_id='$id'")->row_array();

        $this->db->set(['is_status' => 's']);
        $this->db->where('transaksi_id', $id);
        $this->db->update('tx_transaksi');

        $dataInfo = [
            'cust_id'       => $qtrx['cust_id'],
            'sync_id'       => $qtrx['unique_id'],
            'tipe_notif'    => 'trx',
            'judul_notif'   => 'Pesanan '.$qtrx['no_transaksi'].' Telah Tiba Ditujuan',
            'ket_notif'     => 'Pesanan telah sampai tujuan.',
            'is_read'       => 'n',
            'created_at'    => date("Y-m-d H:i:s")
        ];

        $res = $this->db->insert('tx_notifikasi', $dataInfo);

        if ($res==true) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function transaksiUlasan($id) {

        $this->db->set(['publikasi_rating' => 'y', 'publikasi_ulasan' => 'y']);
        $this->db->where('transaksi_det_id', $id);
        $res = $this->db->update('tx_transaksi_det');

        if ($res==true) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function loadUlasanRatPending() {

        $data = array();
        $query = $this->db->query("SELECT a.*,c.nama_warna,d.ukuran_size FROM tx_transaksi_det a LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE (a.publikasi_rating='n' OR  a.publikasi_ulasan='n') AND (a.rating_produk!='' OR  a.ulasan_produk!='')")->result_array();
    
        foreach ($query as $rows) { 

            if ($rows['nama_warna']!='') {
                if ($rows['nama_warna']=='Default') {
                    $rows['nama_warna'] = "";
                }else{
                    $rows['nama_warna'] = $rows['nama_warna'].", ";
                }
            }

            if ($rows['ukuran_size']!='') {
                if ($rows['ukuran_size']=='Default') {
                    $rows['ukuran_size'] = "";
                }else{
                    $rows['ukuran_size'] = $rows['ukuran_size'].", ";
                }
            }

            if ($rows['nama_warna']=='' && $rows['ukuran_size']=='') {
                $varian = '-';
            }else{
                $varian = substr($rows['nama_warna'].$rows['ukuran_size'], 0,-2);
            }

            $data[] = array(
                'transaksi_det_id'          => $rows['transaksi_det_id'],
                'nama_produk'               => $rows['nama_produk'],
                'rating_produk'             => $rows['rating_produk'],
                'ulasan_produk'             => $rows['ulasan_produk'],
                'varian'                    => $varian
            );

        }

        return $data;
    }

    public function topupDel($id) {

        $this->db->set([
            'is_status' => 'b',
            'if_cancel' => 'Transaksi telah dibatalkan pada <b>'.indo(date('Y-m-d')).' '.date('H:i').'</b> waktu setempat.<br>Oleh <b>Admin</b>.'
        ]);
        $this->db->where('topup_id', $id);
        $this->db->update('tx_topup');

    }

    public function topupSelesai($id) {
        
        $qtrx = $this->db->query("SELECT * FROM tx_topup WHERE topup_id='$id'")->row_array();
        $saldo = $this->db->query("SELECT * FROM tx_saldo WHERE cust_id='$qtrx[cust_id]' ORDER BY saldo_id DESC LIMIT 1")->row_array();

        $data = [
            'cust_id'             => $qtrx['cust_id'],
            'kode_saldo'          => $qtrx['kode_topup'],
            'status_saldo'        => 1, // masuk ~~
            'tipe'                => 'topup',
            'awal'                => $saldo['akhir'],
            'masuk'               => $qtrx['nominal_topup'],
            'keluar'              => 0,
            'akhir'               => $saldo['akhir']+$qtrx['nominal_topup'],
            'created_at'          => date("Y-m-d H:i:s")
        ];

        $res = $this->db->insert('tx_saldo', $data);

        $this->db->set(['is_status' => 'y']);
        $this->db->where('topup_id', $id);
        $this->db->update('tx_topup');

        if ($res==true) {
            return 'yes';
        }else{
            return 'no';
        }

    }

}
