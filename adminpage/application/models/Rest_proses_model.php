<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rest_proses_model extends CI_Model {

    public function proses_signin($postjson) {

        $cektoken = $this->db->query("SELECT is_token FROM m_customer WHERE is_token='$postjson[email]'")->num_rows();

        if ($cektoken>=1) {
            $cekdata = cekDatarowarray('m_customer','is_token',$postjson['email']);

            if($cekdata['is_active']=='1'){
                if (password_verify($postjson['password'], $cekdata['is_password'])) {
                    return json_encode(array('success'=>true, 'result'=>$cekdata, 'msg'=>'Login berhasil.'));
                }else{
                    return json_encode(array('success'=>false, 'result'=>$cekdata, 'msg'=>'Login gagal password tidak sesuai, silahkan coba lagi.'));
                }
            }else if($cekdata['is_active']=='2'){
                return json_encode(array('success'=>false, 'msg'=>'Akun ini tidak aktif, hubungi kontak support untuk informasi lebih lanjut.'));
            }else{
                return json_encode(array('success'=>false, 'msg'=>'Login gagal, akun tidak terdaftar.'));
            }
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Login gagal, akun tidak terdaftar.'));
        }
    }

    public function proses_signup($postjson,$mail) {
        $this->db->delete('m_customer', ['is_token' => $postjson['email'], 'is_active' => 0]);
        $cektoken = $this->db->query("SELECT is_token FROM m_customer WHERE is_token='$postjson[email]' AND is_active!=0")->num_rows();
        if ($cektoken==0) {
            $idnya = urutId('m_customer',"cust_id");
            $randname = $idnya; 
            $aktivasi = randNumb(6);
            $resemail = emailOtpSmtp($postjson['email'],$aktivasi,$mail);
            if ($resemail=='y') {
                $data = [
                    'cust_id'               => $randname,
                    'is_token'              => $postjson['email'],
                    'cust_gambar'           => 'user-default-01.png',
                    'is_active'             => 0, // 0 = pending, 1 = aktif, 2 = tidak aktif
                    'is_sosmed'             => 'n',
                    'is_sosmed_from'        => $postjson['from'],
                    'kode_aktivasi'         => $aktivasi,
                    'created_at'            => date('Y-m-d H:i:s')
                ];

                if ($postjson['email']!=null) {
                    $this->db->insert('m_customer', $data);
                    return json_encode(array('success'=>true, 'email'=>$postjson['email'], 'kode_aktivasi'=>$aktivasi, 'msg'=>'Kode OTP telah dikirim, cek pesan email atau folder spam untuk melanjutkan.'));
                }
            }else{
                return json_encode(array('success'=>false, 'msg'=>$resemail));
            }

        }else{
            return json_encode(array('success'=>false, 'msg'=>'Email sudah terdaftar.'));
        }
    }

    public function proses_aktivasi($postjson) {

        if ($postjson['kode_aktivasi']=='google' || $postjson['kode_aktivasi']=='apple') {
            $result = cekDatarowarray('m_customer','is_token',$postjson['email']);
            if ($result) {
                if($result['is_active']=='1'){

                    if ($postjson['tipe']!='web') {
                        $this->db->set(['onesignal_player' => $postjson['onesignalid']]);
                        $this->db->where('cust_id', $result['cust_id']);
                        $this->db->update('m_customer');
                    }

                    return json_encode(array('success'=>true, 'result'=>$result, 'msg'=>'Login berhasil.'));
                }else{
                    return json_encode(array('success'=>false, 'msg'=>'Akun ini tidak aktif, hubungi kontak support untuk informasi lebih lanjut.'));
                }
            }else{
                $idnya = urutId('m_customer',"cust_id");
                $data = [
                    'cust_id'               => $idnya,
                    'cust_nama'             => $postjson['nama'],
                    'is_token'              => $postjson['email'],
                    'cust_gambar'           => 'user-default-01.png',
                    'onesignal_player'      => $postjson['onesignalid'],
                    'is_active'             => 1, // 2 = tidak aktif
                    'is_sosmed'             => 'y',
                    'is_sosmed_from'        => $postjson['kode_aktivasi'],
                    'kode_aktivasi'         => $postjson['kode_aktivasi'],
                    'created_at'            => date('Y-m-d H:i:s')
                ];

                if ($postjson['email']!=null) {
                    $this->db->insert('m_customer', $data);
                    $resultses = cekDatarowarray('m_customer','is_token',$postjson['email']);
                    return json_encode(array('success'=>true, 'result'=>$resultses, 'msg'=>'Login berhasil.'));
                }else{
                    return json_encode(array('success'=>false, 'msg'=>'Aktivasi login gagal, silahkan coba lagi.'));
                }
            }
        }else{
            $result =  $this->db->query("SELECT * FROM m_customer WHERE is_token='$postjson[email]' AND kode_aktivasi='$postjson[kode_aktivasi]'")->row_array();
            if ($result) {
                if($result['is_active']=='1'){
                    if ($postjson['tipe']!='web') {
                        $this->db->set(['onesignal_player' => $postjson['onesignalid']]);
                        $this->db->where('cust_id', $result['cust_id']);
                        $this->db->update('m_customer');
                    }

                    $result = cekDatarowarray('m_customer','cust_id',$result['cust_id']);

                    return json_encode(array('success'=>true, 'result'=>$result, 'msg'=>'Login berhasil.'));
                }else if($result['is_active']=='0'){
                    $this->db->set(['is_active' => 1]);
                    $this->db->where('cust_id', $result['cust_id']);
                    $this->db->update('m_customer');

                    $result = cekDatarowarray('m_customer','cust_id',$result['cust_id']);

                    return json_encode(array('success'=>true, 'result'=>$result, 'msg'=>'Login berhasil.'));
                }else{
                    return json_encode(array('success'=>false, 'msg'=>'Akun ini tidak aktif, hubungi kontak support untuk informasi lebih lanjut.'));
                }
            }else{
                return json_encode(array('success'=>false, 'msg'=>'Kode OTP tidak sesuai.'));
            }
        }
    }

    public function kirim_ulang_aktivasi($postjson,$mail) {
        $kodeaktivasi = randNumb(6);
        $resemail = emailOtpSmtp($postjson['email'],$kodeaktivasi,$mail);
        if ($resemail=='y') {
            $this->db->set(['kode_aktivasi' => $kodeaktivasi]);
            $this->db->where('is_token', $postjson['email']);
            $this->db->update('m_customer');
            return $kodeaktivasi;
        }else{
            return 'n';
        }
    }

    public function proses_edit_akun($postjson) {

        $cekdata = cekDatarowarray('m_customer','cust_id',$postjson['idcust']);

        if ($postjson['tipe']=='web') {

            $randname = date('Ymdhis').$postjson['idcust'];
            $pict = $postjson['gambar'];
            $temporari = $postjson['gambar_tmp'];
            $namagbr = "cust_id_".$randname."-".$postjson['gambar'];
            $filetujuan = "./../assets/uploaded/profile/".$namagbr;
            $ukurangambar= $postjson['gambar_size'];
            $maxukuran = 2000000;
            $maxwidth = 2000;

            //list( $width, $height ) = getimagesize($temporari);
            // if($ukurangambar <= $maxukuran)

            if ($postjson['gambar']=='') {
                $namagbr = $cekdata['cust_gambar'];
            }else{
                if ($cekdata['cust_gambar']!='user-default-01.png') {
                    if (file_exists(FCPATH."./../assets/uploaded/profile/".$cekdata['cust_gambar'])){
                      unlink(FCPATH."./../assets/uploaded/profile/".$cekdata['cust_gambar']);
                    }
                }
                // if($width <= $maxwidth){
                //     move_uploaded_file($temporari, $filetujuan);
                // }else{
                //     resizeImgv2($temporari, $filetujuan);
                // }
                resizeImgv2($temporari, $filetujuan);
            }
        }else{
            if ($postjson['gambar']!='') {
                $ext = 'jpeg';
                $randname = date('Ymdhis').$postjson['idcust'];
                $imgstring = $postjson['gambar'];
                $imgstring = trim(str_replace('data:image/'.$ext.';base64,', "", $imgstring));
                $imgstring = str_replace(' ', '+', $imgstring);
                $data = base64_decode($imgstring);
                $namagbr  = "cust_id_".$postjson['idcust']."_".$randname.".jpg";  
                $directoryx = "./../assets/uploaded/profile/".$namagbr;
                file_put_contents($directoryx, $data);
                if ($cekdata['cust_gambar']!='user-default-01.png') {
                    if (file_exists(FCPATH."./../assets/uploaded/profile/".$cekdata['cust_gambar'])){
                        unlink(FCPATH."./../assets/uploaded/profile/".$cekdata['cust_gambar']);
                    }
                }
            }else{
                $namagbr = $cekdata['cust_gambar'];
            }
        }

        $this->db->set([
            'cust_nama'     => $postjson['cust_nama'],
            'cust_ponsel'   => $postjson['cust_ponsel'],
            'cust_gambar'   => $namagbr
        ]);
        $this->db->where('cust_id', $postjson['idcust']);
        $i = $this->db->update('m_customer');

        if ($i==true) {
            return json_encode(array('success'=>true, 'msg'=>'Edit data berhasil.'));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Edit data gagal, silahkan coba lagi.'));
        }
    }

    public function proses_edit_password($postjson) {

        $cekdata = cekDatarowarray('m_customer','cust_id',$postjson['idcust']);
        $password = password_hash($postjson['password_baru'], PASSWORD_DEFAULT);

        if ($postjson['password_lama']=='') {
            $re = 'y';
        }else{
            if (password_verify($postjson['password_lama'], $cekdata['is_password'])) {
                $re = 'y';
            }else{
                $re = 'n';
            }
        }

        if ($re=='y') {
            $this->db->set(['is_password' => $password]);
            $this->db->where('cust_id', $postjson['idcust']);
            $i = $this->db->update('m_customer');
        }else{
            $i = false;
        }


        if ($i==true) {
            return json_encode(array('success'=>true, 'msg'=>'Edit password berhasil.'));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Password lama tidak sesuai, silahkan coba lagi.'));
        }
    }

    public function proses_add_cart($postjson) {

        // Cek stok
        if ($postjson['idwarna']==0) {
            $idw = 1;
        }else{
            $idw = $postjson['idwarna'];
        }

        $c_w = $this->db->query("SELECT * FROM m_produk_warna WHERE produk_id='$postjson[idproduk]' AND warna_id='$idw'")->row_array();

        if ($postjson['idukuran']==0) {
            $idu = 1;
        }else{
            $idu = $postjson['idukuran'];
        }

        $c_u = $this->db->query("SELECT * FROM m_produk_ukuran WHERE produk_id='$postjson[idproduk]' AND ukuran_id='$idu'")->row_array();

        $stok = $this->produk->cekStok($postjson['idproduk'],$c_w['produk_warna_id'],$c_u['produk_ukuran_id']);

        if ($stok['akhir']>=$postjson['jumlah_qty']) {

            $q_cek_stok_beli = $this->db->query("SELECT * FROM tx_cart WHERE produk_id='$postjson[idproduk]' AND warna_id='$idw' AND ukuran_id='$idu' AND cust_id='$postjson[idcust]'");

            $cek_stok_beli = $q_cek_stok_beli->row_array();
            $cek_stok_beli_r = $q_cek_stok_beli->num_rows();


            // jika update cart item
            if ($postjson['idcart']!=0) {
                $cek_stok_beli_r = 0;
            }

            if ($cek_stok_beli_r==0) {
                if ($stok['akhir']>=($postjson['jumlah_qty']+isset($cek_stok_beli['jumlah_beli']))) {
                    $data = [
                        'cust_id'         => $postjson['idcust'],
                        'produk_id'       => $postjson['idproduk'],
                        'warna_id'        => $idw,
                        'ukuran_id'       => $idu,
                        'jumlah_beli'     => $postjson['jumlah_qty'],
                        'created_at'      => date('Y-m-d H:i:s')
                    ];

                    if ($postjson['idcust']!=null) {
                        // jika update cart item
                        if ($postjson['idcart']!=0) {
                            $this->db->delete('tx_cart', ['cart_id' => $postjson['idcart'], 'cust_id' => $postjson['idcust']]);
                        }
                        $this->db->insert('tx_cart', $data);
                        return json_encode(array('success'=>true, 'msg'=>'Produk di tambahkan ke keranjang.'));
                    }
                }else{
                    return json_encode(array('success'=>false, 'msg'=>'Total yang kamu beli melebihi jumlah stok.'));
                }
            }else{
                if ($stok['akhir']>=($postjson['jumlah_qty']+$cek_stok_beli['jumlah_beli'])) {
                    $update_cart = array('jumlah_beli' => $cek_stok_beli['jumlah_beli']+$postjson['jumlah_qty']);
                    $w_kr = array('cart_id' => $cek_stok_beli['cart_id'], 'cust_id' => $postjson['idcust']);
                    $this->db->where($w_kr);
                    $this->db->update('tx_cart', $update_cart);
                    return json_encode(array('success'=>true, 'msg'=>'Produk di tambahkan ke keranjang.'));
                }else{
                    return json_encode(array('success'=>false, 'msg'=>'Total yang kamu beli melebihi jumlah stok.'));
                }
            }
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Stok produk ini habis atau kurang dari jumlah yang kamu beli.'));
        }

    }

    public function proses_del_cart($postjson) {
        $res = $this->db->delete('tx_cart', ['cart_id' => $postjson['idcart'], 'cust_id' => $postjson['idcust']]);
        return json_encode(array('success'=>true, 'msg'=>'-'));
    }

    public function proses_add_whislist($postjson) {

        $check = $this->db->query("SELECT * FROM tx_wishlist WHERE cust_id='$postjson[idcust]' AND produk_id='$postjson[idproduk]'")->num_rows();

        if ($check>0) {
            $res = $this->db->delete('tx_wishlist', ['cust_id' => $postjson['idcust'], 'produk_id' => $postjson['idproduk']]);
            if ($res==true) {
                $ires = 'del';
                return json_encode(array('success'=>true, 'msg'=>'Produk di hapus dari daftar whistlist kamu.', 'ires'=>$ires));
            }else{
                $ires = 'er_del';
                return json_encode(array('success'=>false, 'msg'=>'Silahkan coba lagi.', 'ires'=>$ires));
            }
        }else{
            $data = [
                'cust_id'         => $postjson['idcust'],
                'produk_id'       => $postjson['idproduk']
            ];
            
            if ($postjson['idcust']!=null) {
                $ires = 'add';
                $this->db->insert('tx_wishlist', $data);
                return json_encode(array('success'=>true, 'msg'=>'Produk di tambahkan ke daftar whistlist kamu.', 'ires'=>$ires));
            }
        }

    }

    public function proses_option_alamat($postjson) {

        $provinsi = explode("*", $postjson["provinsi_id_ex"]);
        $kab = explode("*", $postjson["kabkot_id_ex"]);
        
        if ($postjson['tipe']=='add') {

            $check = $this->db->query("SELECT cust_id FROM m_customer_det WHERE cust_id='$postjson[idcust]'")->num_rows();
            if ($check<=0) {
                $is_selected = 1;
            }else{
                $is_selected = 0;
            }

            $data = [
                'is_selected'           => $is_selected,
                'cust_id'               => $postjson['idcust'],
                'label_alamat'          => $postjson['label_alamat'],
                'nama_penerima'         => $postjson['nama_penerima'],
                'ponsel_penerima'       => $postjson['ponsel_penerima'],
                'id_pusat'              => '151', // pusat toko
                'nama_pusat'            => 'Jakarta Barat', // pusat toko
                'id_provinsi'           => $provinsi[0],
                'nama_provinsi'         => $provinsi[1],
                'id_kabkot'             => $kab[0],
                'nama_kabkot'           => $kab[1],
                'kodepos'               => $postjson['kode_pos_ex'],
                'alamat_lengkap'        => $postjson['alamat_lengkap'],
                'created_at'            => date('Y-m-d H:i:s')
            ];
                
            if ($postjson['idcust']!=null) {
                $i = $this->db->insert('m_customer_det', $data);
                if ($i==true) {
                    return json_encode(array('success'=>true, 'msg'=>'Simpan data berhasil.'));
                }else{
                    return json_encode(array('success'=>false, 'msg'=>'Simpan data gagal, silahkan coba lagi.'));
                }
            }
        }else{

            $this->db->set([
                'label_alamat'          => $postjson['label_alamat'],
                'nama_penerima'         => $postjson['nama_penerima'],
                'ponsel_penerima'       => $postjson['ponsel_penerima'],
                'id_provinsi'           => $provinsi[0],
                'nama_provinsi'         => $provinsi[1],
                'id_kabkot'             => $kab[0],
                'nama_kabkot'           => $kab[1],
                'kodepos'               => $postjson['kode_pos_ex'],
                'alamat_lengkap'        => $postjson['alamat_lengkap']
            ]);
            $this->db->where('cust_det_id', $postjson['idalamat']);
            $i = $this->db->update('m_customer_det');
            if ($i==true) {
                return json_encode(array('success'=>true, 'msg'=>'Edit data berhasil.'));
            }else{
                return json_encode(array('success'=>false, 'msg'=>'Edit data gagal, silahkan coba lagi.'));
            }

        }

    }

    public function proses_topup_saldo($postjson,$mail = null) {
        $pengaturanSistem = pengaturanSistem();

        $res_trx = false;
        $todaydate = date('Y-m-d H:i:s');

        $bul = date('m'); $tahun = date('Y'); $tgl = date('mY');
        $nores = $this->db->query("SELECT max(substr(kode_topup,17,5))as no FROM tx_topup WHERE substr(kode_topup,5,4)='TOUP' AND substr(kode_topup,12,4)='$tahun'")->row_array();
        $has=intval($nores['no'])+1;
        $noTrx="TRX/TOUP/".$tgl."/".sprintf("%05d",$has);

        //batas waktu pembayaran
        $cenvertedtime = date('Y-m-d H:i:s',strtotime('+'.$pengaturanSistem['limit_batas_bayar'].' hour',strtotime($todaydate)));

        if ($pengaturanSistem['metode_pembayaran']=='midtrans') {
            $response_midtrans = json_decode($postjson['snapobj'],true);
            if (isset($response_midtrans['va_numbers'])) {
              $billkey = $response_midtrans['va_numbers'][0]['va_number'];
              $billercode = $response_midtrans['va_numbers'][0]['bank'];
            }else if (isset($response_midtrans['permata_va_number'])) {
              $billkey = $response_midtrans['permata_va_number'];
              $billercode = "permata";
            }else{
                if ($response_midtrans['payment_type']=='gopay' || $response_midtrans['payment_type']=='qris') {
                    $response_midtrans['pdf_url'] = '';
                    $billkey = '';
                    $billercode = '';
                }else if ($response_midtrans['payment_type']=='cstore'){
                    $billkey = '';
                    $billercode = '';
                }else{
                    $billkey = $response_midtrans['bill_key'];
                    $billercode = $response_midtrans['biller_code'];
                }
            }
            $pdf_url_pay = $response_midtrans['pdf_url'];
            $payment_type = $response_midtrans['payment_type'];
        }else{
            $response_midtrans = 'manual';
            $billkey = 'manual';
            $billercode = 'manual';
            $pdf_url_pay = 'manual';
            $payment_type = 'manual';

            $postjson['idunique'] = 'TOPUP-'.date('Y-m-d-His')."-cstore-xid-".$postjson['idcust'];
        }

        if ($postjson['statuspay']=='y') {
            $buktibyr = 'y';
        }else{
            $buktibyr = 'n';
        }

        $dataFirst = [
            'kode_topup'              => $noTrx,
            'unique_id'               => $postjson['idunique'],
            'cust_id'                 => $postjson['idcust'],
            'nominal_topup'           => $postjson['nominaltopup'],
            'is_status'               => $postjson['statuspay'],
            'bukti_pembayaran'        => $buktibyr,
            'batas_waktu_pembayaran'  => $cenvertedtime,
            'cara_pembayaran'         => $pdf_url_pay,
            'payment_type'            => $payment_type,
            'biller_code'             => $billercode,
            'bill_key'                => $billkey,
            'response_midtrans'       => $postjson['snapobj'],
            'created_at'              => date('Y-m-d H:i:s')
        ];

        if ($postjson['nominaltopup']>=10000 && $postjson['idcust']!=null) {
            $res_trx = $this->db->insert('tx_topup', $dataFirst);
        }

        if ($res_trx==true) {
            $msg = 'Proses berhasil, silahkan untuk melakukan pembayaran sesuai dengan nominal topup yang dimasukan '.formatRupiah($postjson['nominaltopup']).'.';
            return json_encode(array('success'=>true, 'msg'=>$msg, 'uid'=>$postjson['idunique']));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
        }

    }

    public function proses_simpan_transaksi($postjson,$mail = null) {
        $pengaturanSistem = pengaturanSistem();
        // $idTrx = urutId('tx_transaksi',"transaksi_id");

        $res_trx = false;
        $todaydate = date('Y-m-d H:i:s');
        $harga_total_default = 0; $berat_total_default = 0;
        $total_potongan = 0; $total_global_diskon = 0; $total_tambahan_harga = 0;

        $bul = date('m'); $tahun = date('Y'); $tgl = date('mY');
        $nores = $this->db->query("SELECT max(substr(no_transaksi,17,5))as no FROM tx_transaksi WHERE substr(no_transaksi,5,4)='INVT' AND substr(no_transaksi,12,4)='$tahun'")->row_array();
        $has=intval($nores['no'])+1;
        $noTrx="TRX/INVT/".$tgl."/".sprintf("%05d",$has);

        $query = $this->db->query("SELECT a.*,b.*,c.nama_warna,d.ukuran_size FROM tx_cart a JOIN m_produk b ON a.produk_id=b.produk_id LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE b.is_active=1 AND b.is_hapus='n' AND a.cust_id='$postjson[idcust]'")->result_array();

        foreach ($query as $rows) {
          if ($rows['potongan_status']=='y') {
            $today = strtotime('Y-m-d'); 
            $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
            $jarakhari = $today - $tgl_mulai;
            $selisihari = $jarakhari / 60 / 60 / 24;
            $jarakhari_a = $today - $tgl_akhir;
            $selisihari_a = $jarakhari_a / 60 / 60 / 24;
            if ($selisihari<=0 && $selisihari_a<=0) {
              $potongan_diskon = $rows['potongan_diskon'];
              $harga_p_new = $rows['harga_produk']-$potongan_diskon;
              $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
              $harga_p_new = $harga_p_new-$global_diskon;
              $potongan_status = 'y';
            }else{
              $potongan_diskon = 0;
              $harga_p_new = $rows['harga_produk'];
              $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
              $harga_p_new = $harga_p_new-$global_diskon;
              $potongan_status = 'n';
            }
          }else{
            $potongan_diskon = 0;
            $harga_p_new = $rows['harga_produk'];
            $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
            $harga_p_new = $harga_p_new-$global_diskon;
            $potongan_status = 'n';
          }
          if ($pengaturanSistem['global_diskon']>0) {
            $hrga_awal = $rows['harga_produk']-$potongan_diskon;
            $potongan_status = 'y';
          }else{
            $hrga_awal = $rows['harga_produk'];
          }

          $lx_u = $this->db->query("SELECT * FROM m_produk_ukuran WHERE produk_id='$rows[produk_id]' AND ukuran_id='$rows[ukuran_id]'")->row_array();

          $harga_total_default += $rows['harga_produk']*$rows['jumlah_beli'];
          $berat_total_default += $rows['berat_produk']*$rows['jumlah_beli'];
          $total_potongan += $potongan_diskon*$rows['jumlah_beli'];
          $total_global_diskon += $global_diskon*$rows['jumlah_beli'];
          $total_tambahan_harga += $lx_u['tambahan_harga']*$rows['jumlah_beli'];

        }

        // ongkos kirim
        $o_kirim = $this->db->query("SELECT ongkos_kirim FROM tx_kurir WHERE kurir_id='$postjson[idkurir]' AND cust_id='$postjson[idcust]'")->row_array();

        $paynext = 'y';
        if ($postjson['metodepembayaran']=='saldo') {
            $saldo = $this->db->query("SELECT * FROM tx_saldo WHERE cust_id='$postjson[idcust]' ORDER BY saldo_id DESC LIMIT 1")->row_array();

            $totalbayar_benerbener = $harga_total_default+$o_kirim['ongkos_kirim']-$total_potongan-$total_global_diskon-$postjson['potonganvoucher'];

            if ($saldo['akhir']>=$totalbayar_benerbener) {
                $paynext = 'y';
                $postjson['statuspay'] = 'y';
            }else{
                $paynext = 'n';
            }
        }

        if ($paynext=='y') {
            //batas waktu pembayaran
            $cenvertedtime = date('Y-m-d H:i:s',strtotime('+'.$pengaturanSistem['limit_batas_bayar'].' hour',strtotime($todaydate)));

            if ($pengaturanSistem['metode_pembayaran']=='midtrans' && $postjson['metodepembayaran']=='bank') {
                $response_midtrans = json_decode($postjson['snapobj'],true);
                if (isset($response_midtrans['va_numbers'])) {
                  $billkey = $response_midtrans['va_numbers'][0]['va_number'];
                  $billercode = $response_midtrans['va_numbers'][0]['bank'];
                }else if (isset($response_midtrans['permata_va_number'])) {
                  $billkey = $response_midtrans['permata_va_number'];
                  $billercode = "permata";
                }else{
                    if ($response_midtrans['payment_type']=='gopay' || $response_midtrans['payment_type']=='qris') {
                        $response_midtrans['pdf_url'] = '';
                        $billkey = '';
                        $billercode = '';
                    }else if ($response_midtrans['payment_type']=='cstore'){
                        $billkey = '';
                        $billercode = '';
                    }else{
                        $billkey = $response_midtrans['bill_key'];
                        $billercode = $response_midtrans['biller_code'];
                    }
                }
                $pdf_url_pay = $response_midtrans['pdf_url'];
                $payment_type = $response_midtrans['payment_type'];
            }else{
                $response_midtrans = 'manual';
                $billkey = 'manual';
                $billercode = 'manual';
                $pdf_url_pay = 'manual';
                $payment_type = 'manual';

                $postjson['idunique'] = date('Y-m-d-His')."-cstore-xid-".$postjson['idcust'];
            }

            if ($postjson['potonganvoucher']==0) {
                $kodevoucher = '';
            }else{
                $kodevoucher = $postjson['kodevoucher'];
            }

            if ($postjson['statuspay']=='y') {
                $buktibyr = 'y';
            }else{
                $buktibyr = 'n';
            }

            $dataFirst = [
                'no_transaksi'            => $noTrx,
                'unique_id'               => $postjson['idunique'],
                'cust_id'                 => $postjson['idcust'],
                'cust_det_id'             => $postjson['idalamat'],
                'harga_total'             => $harga_total_default,
                'berat_total'             => $berat_total_default,
                'ongkos_kirim'            => $o_kirim['ongkos_kirim'],
                'tambahan_harga_total'    => $total_tambahan_harga,
                'potongan_total'          => $total_potongan,
                'diskon_all_total'        => $total_global_diskon,
                'pers_diskon_all'         => $pengaturanSistem['global_diskon'],
                'kode_voucher'            => $kodevoucher,
                'potongan_voucher'        => $postjson['potonganvoucher'],
                'is_read'                 => 'n',
                'is_status'               => $postjson['statuspay'],
                'transaksi_from'          => 'WEB',
                'metode_pembayaran'       => $postjson['metodepembayaran'],
                'bukti_pembayaran'        => $buktibyr,
                'tgl_transaksi'           => date('Y-m-d H:i:s'),
                'batas_waktu_pembayaran'  => $cenvertedtime,
                'cara_pembayaran'         => $pdf_url_pay,
                'payment_type'            => $payment_type,
                'biller_code'             => $billercode,
                'bill_key'                => $billkey,
                'response_midtrans'       => $postjson['snapobj']
            ];

            if ($harga_total_default!=0 && $berat_total_default!=0) {
                if ($postjson['idcust']!=null) {
                    $res_trx = $this->db->insert('tx_transaksi', $dataFirst);
                }
            }

            if ($res_trx==true) {

                if ($kodevoucher!='') {
                    $voucherdata = $this->db->query("SELECT voucher_id FROM m_voucher WHERE kode_voucher='$kodevoucher'")->row_array();
                    $dataVoucher = [
                        'voucher_id'      => $voucherdata['voucher_id'],
                        'cust_id'         => $postjson['idcust'],
                        'kode_voucher'    => $kodevoucher,
                        'created_at'      => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert('m_voucher_det', $dataVoucher);
                }

                $cek_cart_no_stok = 0;
                $harga_total_default_det = 0; $berat_total_default_det = 0;
                $total_potongan_det = 0; $total_global_diskon_det = 0; $total_tambahan_harga_det = 0;
                
                $query = $this->db->query("SELECT a.*,b.*,c.nama_warna,d.ukuran_size FROM tx_cart a JOIN m_produk b ON a.produk_id=b.produk_id LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE b.is_active=1 AND b.is_hapus='n' AND a.cust_id='$postjson[idcust]'");
                
                $res = $query->result_array(); $nums = $query->num_rows();

                foreach ($res as $rows) {

                    $l_w = $this->db->query("SELECT * FROM m_produk_warna WHERE produk_id='$rows[produk_id]' AND warna_id='$rows[warna_id]'")->row_array();
                    $l_u = $this->db->query("SELECT * FROM m_produk_ukuran WHERE produk_id='$rows[produk_id]' AND ukuran_id='$rows[ukuran_id]'")->row_array();

                    $stok = $this->produk->cekStok($rows['produk_id'],$l_w['produk_warna_id'],$l_u['produk_ukuran_id']);

                    // pengecekan stok
                    if ($stok['akhir']>=$rows['jumlah_beli']) {

                        if ($rows['potongan_status']=='y') {
                            $today = strtotime('Y-m-d'); 
                            $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                            $jarakhari = $today - $tgl_mulai;
                            $selisihari = $jarakhari / 60 / 60 / 24;
                            $jarakhari_a = $today - $tgl_akhir;
                            $selisihari_a = $jarakhari_a / 60 / 60 / 24;
                            if ($selisihari<=0 && $selisihari_a<=0) {
                              $potongan_diskon = $rows['potongan_diskon'];
                              $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                              $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                              $harga_p_new = $harga_p_new-$global_diskon;
                              $potongan_status = 'y';
                            }else{
                              $potongan_diskon = 0;
                              $harga_p_new = $rows['harga_produk'];
                              $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                              $harga_p_new = $harga_p_new-$global_diskon;
                              $potongan_status = 'n';
                            }
                        }else{
                            $potongan_diskon = 0;
                            $harga_p_new = $rows['harga_produk'];
                            $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                            $harga_p_new = $harga_p_new-$global_diskon;
                            $potongan_status = 'n';
                          }
                          if ($pengaturanSistem['global_diskon']>0) {
                            $hrga_awal = $rows['harga_produk']-$potongan_diskon;
                            $potongan_status = 'y';
                          }else{
                            $hrga_awal = $rows['harga_produk'];
                        }

                        $harga_total_default_det += $rows['harga_produk']*$rows['jumlah_beli'];
                        $berat_total_default_det += $rows['berat_produk']*$rows['jumlah_beli'];
                        $total_potongan_det += $potongan_diskon*$rows['jumlah_beli'];
                        $total_global_diskon_det += $global_diskon*$rows['jumlah_beli'];
                        $total_tambahan_harga_det += $l_u['tambahan_harga']*$rows['jumlah_beli'];

                        $dataSec = [
                            'no_transaksi'              => $noTrx,
                            'unique_id'                 => $postjson['idunique'],
                            'cust_id'                   => $postjson['idcust'],
                            'produk_id'                 => $rows['produk_id'],
                            'warna_id'                  => $rows['warna_id'],
                            'ukuran_id'                 => $rows['ukuran_id'],
                            'nama_produk'               => $rows['nama_produk'],
                            'harga_produk'              => $rows['harga_produk'],
                            'berat_produk'              => $rows['berat_produk'],
                            'tambahan_harga'            => $l_u['tambahan_harga'],
                            'potongan_harga'            => $potongan_diskon,
                            'diskon_all_produk'         => $global_diskon,
                            'pers_diskon_all_produk'    => $pengaturanSistem['global_diskon'],
                            'jumlah_beli'               => $rows['jumlah_beli'],
                            'total_harga_produk'        => $rows['harga_produk']*$rows['jumlah_beli'],
                            'total_berat_produk'        => $rows['berat_produk']*$rows['jumlah_beli'],
                            'total_tambahan_harga'      => $l_u['tambahan_harga']*$rows['jumlah_beli'],
                            'total_potongan_harga'      => $potongan_diskon*$rows['jumlah_beli'],
                            'total_diskon_all_produk'   => $global_diskon*$rows['jumlah_beli']
                        ];

                        if ($postjson['idcust']!=null) {
                            $res_trx_d = $this->db->insert('tx_transaksi_det', $dataSec);
                        }

                        // pengurangan stok
                        $dataTh = [
                            'kode_stok'           => $noTrx,
                            'status_stok'         => 2, // stok keluar ~~
                            'produk_id'           => $rows['produk_id'],
                            'produk_warna_id'     => $l_w['produk_warna_id'],
                            'produk_ukuran_id'    => $l_u['produk_ukuran_id'],
                            'admin_id'            => '0', // default untuk ecommer
                            'cust_id'             => $postjson['idcust'], // default untuk ecommer
                            'awal'                => $stok['akhir'],
                            'masuk'               => 0,
                            'keluar'              => $rows['jumlah_beli'],
                            'akhir'               => $stok['akhir']-$rows['jumlah_beli'],
                            'label_stok'          => 'WEB', // default untuk ecommer
                            'created_at'          => date("Y-m-d H:i:s")
                        ];

                        if ($postjson['idcust']!=null) {
                            $res_trx_t = $this->db->insert('tx_stok', $dataTh);
                        }

                        // hapus cart
                        $this->db->delete('tx_cart', ['cart_id' => $rows['cart_id'], 'cust_id' => $postjson['idcust']]);
                    }else{
                        $cek_cart_no_stok += 1;
                    }

                }

                if ($nums==$cek_cart_no_stok) { // jika yang dibeli 1 item dan stok habis maka transaksi dibatalkan.
                    $this->db->delete('tx_transaksi', ['no_transaksi' => $noTrx, 'cust_id' => $postjson['idcust']]);
                    return json_encode(array('success'=>false, 'msg'=>'Transaksi di batalkan, kamu telat 1 menit stok produk yang kamu beli kurang atau tidak tersedia.'));
                }else{

                    $update_kurir = array('status_submit' => 'y', 'no_transaksi' => $noTrx);
                    $w_kr = array('kurir_id' => $postjson['idkurir'], 'cust_id' => $postjson['idcust']);
                    $this->db->where($w_kr);
                    $this->db->update('tx_kurir', $update_kurir);

                    // jika yang dibeli lebih dari 1 dan ada item yg stok nya habis maka tidak di proses tapi item yg lain tetap diproses
                    if ($cek_cart_no_stok!=0) { 
                        $msg = 'Proses berhasil, transaksi sedang di proses namun ada '.$cek_cart_no_stok.' produk tidak kami proses di karnakan stok produk sudah tidak tersedia.';

                        $this->db->set([
                            'harga_total'             => $harga_total_default_det,
                            'berat_total'             => $berat_total_default_det,
                            'tambahan_harga_total'    => $total_tambahan_harga_det,
                            'potongan_total'          => $total_potongan_det,
                            'diskon_all_total'        => $total_global_diskon_det
                        ]);
                        $this->db->where('unique_id', $postjson['idunique']);
                        $this->db->update('tx_transaksi');
                    
                    }else{
                        $msg = 'Proses berhasil, transaksi sedang di proses.';
                    }

                    $bataswaktubyry = Indo($cenvertedtime)." ".substr($cenvertedtime,11,5);
                    $dataInfo = [
                        'cust_id'       => $postjson['idcust'],
                        'sync_id'       => $postjson['idunique'],
                        'tipe_notif'    => 'trx',
                        'judul_notif'   => 'Transaksi Baru #'.$noTrx,
                        'ket_notif'     => 'Segera lakukan pembayaran sebelum '.$bataswaktubyry,
                        'is_read'       => 'y',
                        'created_at'    => date("Y-m-d H:i:s")
                    ];

                    // ada di helper ya, ini kirim email detail pembeliannya
                    // kirimTransaksikeEmail($postjson['idunique'],$postjson['idcust'],$mail);

                    if ($postjson['idcust']!=null) {
                        $res_trx_t = $this->db->insert('tx_notifikasi', $dataInfo);
                    }

                    if ($postjson['metodepembayaran']=='saldo') {
                        $datasaldo = [
                            'cust_id'             => $postjson['idcust'],
                            'kode_saldo'          => $noTrx,
                            'status_saldo'        => 2, // keluar ~~
                            'tipe'                => 'trx',
                            'awal'                => $saldo['akhir'],
                            'masuk'               => 0,
                            'keluar'              => $totalbayar_benerbener,
                            'akhir'               => $saldo['akhir']-$totalbayar_benerbener,
                            'created_at'          => date("Y-m-d H:i:s")
                        ];

                        $res = $this->db->insert('tx_saldo', $datasaldo);
                    }
                }

                return json_encode(array('success'=>true, 'msg'=>$msg, 'uid'=>$postjson['idunique']));

            }else{
                $this->db->delete('tx_transaksi', ['no_transaksi' => $noTrx, 'cust_id' => $postjson['idcust']]);
                return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
            }
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, saldo tidak mencukupi.'));
        }

    }

    public function proses_update_transaksi_midtrans($postjson) {
        $this->db->set(['is_status' => $postjson['status']]);
        $this->db->where('unique_id', $postjson['idtrx']);
        $this->db->update('tx_transaksi');
    }

    public function proses_kirim_bukti_bayar($postjson) {

        $cekdata = cekDatarowarray('tx_transaksi','no_transaksi',$postjson['notrx']);

        if ($postjson['tipe']=='web') {

            $randname = date('Ymdhis').$postjson['idcust'];
            $pict = $postjson['gambar'];
            $temporari = $postjson['gambar_tmp'];
            $namagbr = "bukti_byr_".$randname."-".$postjson['gambar'];
            $filetujuan = "./../assets/uploaded/komponen/".$namagbr;
            $ukurangambar= $postjson['gambar_size'];
            $maxukuran = 2000000;
            $maxwidth = 2000;

            if ($postjson['gambar']=='') {
                $namagbr = $cekdata['bukti_pembayaran'];
            }else{
                if ($cekdata['bukti_pembayaran']!='n') {
                  unlink(FCPATH."./../assets/uploaded/komponen/".$cekdata['bukti_pembayaran']);
                }
                resizeImgv2($temporari, $filetujuan);
            }
        }else{
            if ($postjson['gambar']!='') {
                $ext = 'jpeg';
                $randname = date('Ymdhis').$postjson['idcust'];
                $imgstring = $postjson['gambar'];
                $imgstring = trim(str_replace('data:image/'.$ext.';base64,', "", $imgstring));
                $imgstring = str_replace(' ', '+', $imgstring);
                $data = base64_decode($imgstring);
                $namagbr  = "bukti_byr_".$postjson['idcust']."_".$randname.".jpg";  
                $directoryx = "./../assets/uploaded/komponen/".$namagbr;
                file_put_contents($directoryx, $data);
                if ($cekdata['bukti_pembayaran']!='n') {
                    unlink(FCPATH."./../assets/uploaded/komponen/".$cekdata['bukti_pembayaran']);
                }
            }else{
                $namagbr = $cekdata['bukti_pembayaran'];
            }
        }

        $update_trx = array(
            'bank_id'           => $postjson['idbank'],
            'bukti_pembayaran'  => $namagbr
        );

        $w_kr = array('no_transaksi' => $postjson['notrx'], 'cust_id' => $postjson['idcust']);
        $this->db->where($w_kr);
        $i = $this->db->update('tx_transaksi', $update_trx);

        if ($i==true) {
            return json_encode(array('success'=>true, 'msg'=>'Bukti pembayaran telah terkirim, proses pengecekan membutuhkan waktu hingga 1x24 jam.'));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
        }
    }

    public function proses_update_topup_midtrans($postjson) {

        $qtrx = $this->db->query("SELECT * FROM tx_topup WHERE unique_id='$postjson[idtrx]'")->row_array();

        if ($postjson['status']=='y') {
            
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
            $this->db->where('topup_id', $qtrx['topup_id']);
            $this->db->update('tx_topup');
        }else{
            $this->db->set([
                'is_status' => 'b',
                'if_cancel' => 'Transaksi telah dibatalkan pada <b>'.indo(date('Y-m-d')).' '.date('H:i').'</b> waktu setempat.<br>Oleh <b>Admin</b>.'
            ]);
            $this->db->where('topup_id', $qtrx['topup_id']);
            $this->db->update('tx_topup');
        }

    }

    public function proses_kirim_bukti_bayar_topup($postjson) {

        $cekdata = cekDatarowarray('tx_topup','kode_topup',$postjson['notrx']);

        if ($postjson['tipe']=='web') {

            $randname = date('Ymdhis').$postjson['idcust'];
            $pict = $postjson['gambar'];
            $temporari = $postjson['gambar_tmp'];
            $namagbr = "bukti_topup_".$randname."-".$postjson['gambar'];
            $filetujuan = "./../assets/uploaded/komponen/".$namagbr;
            $ukurangambar= $postjson['gambar_size'];
            $maxukuran = 2000000;
            $maxwidth = 2000;

            if ($postjson['gambar']=='') {
                $namagbr = $cekdata['bukti_pembayaran'];
            }else{
                if ($cekdata['bukti_pembayaran']!='n') {
                  unlink(FCPATH."./../assets/uploaded/komponen/".$cekdata['bukti_pembayaran']);
                }
                resizeImgv2($temporari, $filetujuan);
            }
        }else{
            if ($postjson['gambar']!='') {
                $ext = 'jpeg';
                $randname = date('Ymdhis').$postjson['idcust'];
                $imgstring = $postjson['gambar'];
                $imgstring = trim(str_replace('data:image/'.$ext.';base64,', "", $imgstring));
                $imgstring = str_replace(' ', '+', $imgstring);
                $data = base64_decode($imgstring);
                $namagbr  = "bukti_topup_".$postjson['idcust']."_".$randname.".jpg";  
                $directoryx = "./../assets/uploaded/komponen/".$namagbr;
                file_put_contents($directoryx, $data);
                if ($cekdata['bukti_pembayaran']!='n') {
                    unlink(FCPATH."./../assets/uploaded/komponen/".$cekdata['bukti_pembayaran']);
                }
            }else{
                $namagbr = $cekdata['bukti_pembayaran'];
            }
        }

        $update_trx = array(
            'bank_id'           => $postjson['idbank'],
            'bukti_pembayaran'  => $namagbr
        );

        $w_kr = array('kode_topup' => $postjson['notrx'], 'cust_id' => $postjson['idcust']);
        $this->db->where($w_kr);
        $i = $this->db->update('tx_topup', $update_trx);

        if ($i==true) {
            return json_encode(array('success'=>true, 'msg'=>'Bukti pembayaran telah terkirim, proses pengecekan membutuhkan waktu hingga 1x24 jam.'));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
        }
    }

    public function proses_batalkan_transaksi($postjson) {

        $bul = date('m'); $tahun = date('Y'); $tgl = date('mY');
        $nores = $this->db->query("SELECT max(substr(kode_stok,17,5))as no FROM tx_stok WHERE substr(kode_stok,5,4)='BTAL' AND substr(kode_stok,12,4)='$tahun'")->row_array();
        $has=intval($nores['no'])+1;
        $noTrx="TRX/BTAL/".$tgl."/".sprintf("%05d",$has);

        $query_stok = $this->db->query("SELECT * FROM tx_stok WHERE kode_stok='$postjson[notrx]' AND cust_id='$postjson[idcust]'")->result_array();
            
        foreach ($query_stok as $rows) {
            $stok = $this->produk->cekStok($rows['produk_id'],$rows['produk_warna_id'],$rows['produk_ukuran_id']);
            $data = [
                'kode_stok'           => $noTrx,
                'status_stok'         => 3, // stok kembali karna batal ~~
                'produk_id'           => $rows['produk_id'],
                'produk_warna_id'     => $rows['produk_warna_id'],
                'produk_ukuran_id'    => $rows['produk_ukuran_id'],
                'admin_id'            => '0', // default untuk ecommer
                'cust_id'             => $postjson['idcust'], // default untuk ecommer
                'awal'                => $stok['akhir'],
                'masuk'               => $rows['keluar'],
                'keluar'              => 0,
                'akhir'               => $stok['akhir']+$rows['keluar'],
                'label_stok'          => 'BTL', // default untuk batal
                'keterangan_stok'     => 'BATAL-'.$rows['kode_stok'],
                'created_at'          => date("Y-m-d H:i:s")
            ];

            if ($postjson['idcust']!=null) {
                $this->db->insert('tx_stok', $data);

                $this->db->set(['keterangan_stok' => 'BATAL-'.$rows['kode_stok']]);
                $this->db->where('kode_stok', $rows['kode_stok']);
                $this->db->update('tx_stok');
            }
        }

        $this->db->set([
            'is_status' => 'b',
            'if_cancel' => 'Transaksi telah dibatalkan pada <b>'.indo(date('Y-m-d')).' '.date('H:i').'</b> waktu setempat.<br>Oleh <b>Pembeli</b>.'
        ]);
        $this->db->where('no_transaksi', $postjson['notrx']);
        $this->db->update('tx_transaksi');

        $query_sec = $this->db->query("SELECT * FROM tx_transaksi a JOIN m_customer b ON a.cust_id=b.cust_id WHERE a.no_transaksi='$postjson[notrx]' AND a.cust_id='$postjson[idcust]'")->row_array();

        $dataInfo = [
            'cust_id'       => $postjson['idcust'],
            'sync_id'       => $query_sec['unique_id'],
            'tipe_notif'    => 'trx',
            'judul_notif'   => 'Transaksi '.$postjson['notrx'].' Telah Dibatalkan',
            'ket_notif'     => 'Transaksi telah dibatalkan oleh pembeli.',
            'is_read'       => 'y',
            'created_at'    => date("Y-m-d H:i:s")
        ];

        $res_trx_t = false;
        if ($postjson['idcust']!=null) {
            $res_trx_t = $this->db->insert('tx_notifikasi', $dataInfo);
        }

        if ($query_sec['metode_pembayaran']=='saldo') {
            $saldo = $this->db->query("SELECT * FROM tx_saldo WHERE cust_id='$query_sec[cust_id]' ORDER BY saldo_id DESC LIMIT 1")->row_array();

            $totalbayar_benerbener = $query_sec['harga_total']+$query_sec['ongkos_kirim']-$query_sec['potongan_total']-$query_sec['diskon_all_total']-$query_sec['potongan_voucher'];

            $datasaldo = [
                'cust_id'             => $query_sec['cust_id'],
                'kode_saldo'          => $query_sec['no_transaksi'],
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
            return json_encode(array('success'=>true, 'msg'=>'Transaksi telah dibatalkan.'));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
        }

    }

    public function proses_tiba_transaksi($postjson) {

        $qtrx = $this->db->query("SELECT * FROM tx_transaksi WHERE no_transaksi='$postjson[notrx]'")->row_array();

        $this->db->set(['is_status' => 's']);
        $this->db->where('transaksi_id', $qtrx['transaksi_id']);
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

        if ($postjson['idcust']!=null) {
            $res = $this->db->insert('tx_notifikasi', $dataInfo);
        }

        if ($res==true) {
            return json_encode(array('success'=>true, 'msg'=>'Transaksi telah selesai.'.$postjson['notrx']));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
        }

    }

    public function proses_simpan_ulasan_rating($postjson) {

        $pengaturanSistem = pengaturanSistem();

        if ($pengaturanSistem['metode_ulasan']=='auto') {
            $m_ulasan = 'y';
        }else{
            $m_ulasan = 'n';
        }

        if ($pengaturanSistem['metode_rating']=='auto') {
            $m_rat = 'y';
        }else{
            $m_rat = 'n';
        }

        $update = array(
            'rating_produk'     => $postjson['rating'],
            'publikasi_rating'  => $m_rat,
            'ulasan_produk'     => $postjson['ulasan'],
            'publikasi_ulasan'  => $m_ulasan,
            'tgl_ulasan'        => date("Y-m-d H:i:s")
        );

        $w_kr = array('transaksi_det_id' => $postjson['idtrx'], 'cust_id' => $postjson['idcust']);

        $this->db->where($w_kr);
        $res = $this->db->update('tx_transaksi_det', $update);

        if ($res==true) {
            return json_encode(array('success'=>true, 'msg'=>'Terima kasih sudah memberikan ulasan dan rating.'));
        }else{
            return json_encode(array('success'=>false, 'msg'=>'Proses gagal, silahkan coba lagi.'));
        }

    }

    public function proses_baca_notifikasi($postjson) {

        $update = array('is_read' => 'y');

        if ($postjson['opsi']=='id') {
            $w_kr = array('notifikasi_id' => $postjson['idnotif'], 'cust_id' => $postjson['idcust']);
        }else{
            $w_kr = array('sync_id' => $postjson['idnotif'], 'cust_id' => $postjson['idcust']);
        }

        $this->db->where($w_kr);
        $this->db->update('tx_notifikasi', $update);

        return json_encode(array('success'=>true));
    }

}

?>