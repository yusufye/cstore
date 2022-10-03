<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rest_load_model extends CI_Model {

    public function load_pengaturan($postjson){
        $query = $this->db->query("SELECT * FROM _setting WHERE setting_id=1")->row_array();
        return json_encode(array('result'=>$query));
    }

    public function load_customer($postjson){
        $result = cekDatarowarray('m_customer','cust_id',$postjson['idcust']);
        $saldo = $this->db->query("SELECT akhir FROM tx_saldo WHERE cust_id='$postjson[idcust]' ORDER BY saldo_id DESC LIMIT 1")->row_array();
        return json_encode(array('result'=>$result, 'saldo'=>formatRupiah($saldo['akhir']), 'saldo_num'=>$saldo['akhir']));
    }

    public function load_slider($postjson){
        $query = $this->db->query("SELECT * FROM m_slider WHERE is_active=1 AND is_hapus='n' ORDER BY slider_id ASC")->result_array();
        return json_encode(array('result'=>$query));
    }

    public function load_kategori($postjson){
        $query = $this->db->query("SELECT * FROM m_kategori WHERE is_active=1 AND is_hapus='n' ORDER BY nama_kategori ASC")->result_array();
        return json_encode(array('result'=>$query));
    }

    public function load_kategori_det($postjson){

        if ($postjson['tipeid']=='url') {
            $q = $this->db->query("SELECT * FROM m_kategori WHERE url_kategori='$postjson[idkategori]'")->row_array();
            $wh = " AND a.kategori_id='$q[kategori_id]'";
        }else{
            $q = $this->db->query("SELECT * FROM m_kategori WHERE kategori_id='$postjson[idkategori]'")->row_array();
            $wh = " AND a.kategori_id='$postjson[idkategori]'";
        }

        $query = $this->db->query("SELECT * FROM m_kategori_det a JOIN m_kategori_sub b ON a.kategori_sub_id=b.kategori_sub_id WHERE b.is_active=1 AND b.is_hapus='n' $wh ORDER BY b.nama_kategori ASC")->result_array();

        return json_encode(array(
            'result'=>$query, 
            'kategori_id'=>$q['kategori_id'], 
            'nama_kategori'=>$q['nama_kategori']
        ));
    }

    public function load_sub_kategori($postjson){
        $query = $this->db->query("SELECT a.*,d.url_kategori as url_k FROM m_kategori_sub a JOIN m_kategori_det c ON a.kategori_sub_id=c.kategori_sub_id JOIN m_kategori d ON c.kategori_id=d.kategori_id WHERE a.is_active=1 AND a.is_hapus='n' ORDER BY RAND() LIMIT 10")->result_array();
        return json_encode(array('result'=>$query));
    }

    public function load_sub_kategori_det($postjson){

        if ($postjson['tipeid']=='url') {
            $q = $this->db->query("SELECT * FROM m_kategori WHERE url_kategori='$postjson[idkategori]'")->row_array();
            $q2 = $this->db->query("SELECT * FROM m_kategori_sub WHERE url_kategori='$postjson[idsubkategori]'")->row_array();
            $wh = " AND a.kategori_sub_id='$q2[kategori_sub_id]'";
        }else{
            $q = $this->db->query("SELECT * FROM m_kategori WHERE kategori_id='$postjson[idkategori]'")->row_array();
            $q2 = $this->db->query("SELECT * FROM m_kategori_sub WHERE kategori_sub_id='$postjson[idsubkategori]'")->row_array();
            $wh = " AND a.kategori_sub_id='$postjson[idsubkategori]'";
        }

        // khusus sub v2
        if ($postjson['tipeid_v2']=='url') {
            $q3 = $this->db->query("SELECT * FROM m_kategori_sub_lv2 WHERE url_kategori='$postjson[idsubkategorilv2]'")->row_array();
        }else{
            $q3['kategori_sub_lv2_id'] = '';
            $q3['nama_kategori'] = '';
        }

        $query = $this->db->query("SELECT * FROM m_kategori_sub_det a JOIN m_kategori_sub_lv2 b ON a.kategori_sub_lv2_id=b.kategori_sub_lv2_id WHERE b.is_active=1 AND b.is_hapus='n' $wh ORDER BY b.nama_kategori ASC")->result_array();
        return json_encode(array(
            'result'=>$query, 
            'kategori_id'=>$q['kategori_id'], 
            'nama_kategori'=>$q['nama_kategori'],
            'kategori_sub_id'=>$q2['kategori_sub_id'], 
            'nama_kategori2'=>$q2['nama_kategori'],
            'kategori_sub_lv2_id'=>$q3['kategori_sub_lv2_id'], 
            'nama_kategori3'=>$q3['nama_kategori']
        ));
    }

    // belum kepakai
    public function load_sub_kategori_lv2($postjson){
        $query = $this->db->query("SELECT * FROM m_kategori_sub_lv2 WHERE is_active=1 AND is_hapus='n' ORDER BY RAND() LIMIT 12")->result_array();
        return json_encode(array('result'=>$query));
    }

    public function load_kategori_pilihan_only($postjson){
        $result = $this->db->query("SELECT * FROM ui_kategori a JOIN m_kategori b ON a.kategori_id=b.kategori_id WHERE b.is_active=1 AND b.is_hapus='n' LIMIT 5 ")->result_array();

        return json_encode(array('result'=>$result));
    }

    public function load_kategori_pilihan($postjson){

        $data = array();
        $pengaturanSistem = pengaturanSistem();

        if ($postjson['idkategori']=='all') {
            $wh = '';
        }else{
            $wh = ' AND a.kategori_id='.$postjson['idkategori'];
        }

        $result = $this->db->query("SELECT * FROM ui_kategori a JOIN m_kategori b ON a.kategori_id=b.kategori_id WHERE b.is_active=1 AND b.is_hapus='n' $wh LIMIT 5 ")->result_array();

        foreach ($result as $row) {

            $produk = $this->db->query("SELECT * FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_id='$row[kategori_id]' ORDER BY RAND() LIMIT 15 ")->result_array();
    
            $items = array();
            foreach ($produk as $rows) {
                $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

                $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
                foreach ($wrnstok['result'] as $wrnqukr) :
                $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
                foreach ($ukuranstok['result'] as $qukr) :
                $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
                if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
                $tstok += $stk;
                endforeach;
                endforeach;

                if ($tstok>0) {
                    $stk_lbl = 'Stok Tersedia';
                }else{
                    $stk_lbl = 'Stok Habis';
                }

                if ($rows['potongan_status']=='y') {
                    $today = strtotime(date('Y-m-d')); 
                    $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                    $jarakhari = $today - $tgl_mulai;
                    $selisihari = $jarakhari / 60 / 60 / 24;
                    $jarakhari_a = $tgl_akhir - $today;
                    $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                    if ($selisihari>=0 && $selisihari_a>=0) {
                        $potongan_diskon = $rows['potongan_diskon'];
                        $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'y';
                    }else{
                        $potongan_diskon = 0;
                        $harga_p_new = $rows['harga_produk'];
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'n';
                    }
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }

                if ($pengaturanSistem['global_diskon']>0) {
                    $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                    $potongan_status = 'y';
                }else{
                    $hrga_awal = formatRupiah($rows['harga_produk']);
                }
                
                $items[] = array(
                    'produk_id'         => $rows['produk_id'],
                    'nama_produk'       => $rows['nama_produk'],
                    'url_produk'        => $rows['url_produk'],
                    'harga_produk_awal' => $hrga_awal,
                    'harga_produk'      => $harga_p_new,
                    'potongan_status'   => $potongan_status,
                    'is_new'            => $rows['is_new'],
                    'logo_image'        => $gambar['logo_image'],
                    'tstok'             => $stk_lbl,
                    'stok'              => $tstok
                );
            }


            $data[] = array(
                'kategori_id'       => $row['kategori_id'],
                'nama_kategori'     => $row['nama_kategori'],
                'url_kategori'      => $row['url_kategori'],
                'logo_image'        => $row['logo_image'],
                'items'             => $items
            );
        }

        return json_encode(array('result'=>$data));
    }

    public function load_sub_kategori_pilihan_satu($postjson){

        $data = array();

        $result = $this->db->query("SELECT a.kategori_sub_lv2_id,b.nama_kategori,b.url_kategori,b.logo_image,d.url_kategori as url_l,f.url_kategori as url_k FROM ui_kategori_sub_lv2 a JOIN m_kategori_sub_lv2 b ON a.kategori_sub_lv2_id=b.kategori_sub_lv2_id JOIN m_kategori_sub_det c ON a.kategori_sub_lv2_id=c.kategori_sub_lv2_id JOIN m_kategori_sub d ON c.kategori_sub_id=d.kategori_sub_id JOIN m_kategori_det e ON c.kategori_sub_id=e.kategori_sub_id JOIN m_kategori f ON e.kategori_id=f.kategori_id WHERE b.is_active=1 AND b.is_hapus='n' ORDER BY RAND() LIMIT 5 ");

        $val = $result->result_array();
        $rows = $result->num_rows();

        foreach ($val as $row) {

            $data[] = array(
                'kategori_id'           => $row['kategori_sub_lv2_id'],
                'nama_kategori'         => $row['nama_kategori'],
                'url_k'                 => $row['url_k'],
                'url_kategori'          => $row['url_kategori'],
                'url_l'                 => $row['url_l'],
                'logo_image'            => $row['logo_image'],
                'tipe_k'                => 'lv2'
            );
        }

        if ($rows>0) {
            $limitk = " LIMIT 0 ";
        }else{
            $limitk = " LIMIT 5 ";
        }

        $result2 = $this->db->query("SELECT a.kategori_sub_id,b.nama_kategori,b.url_kategori,b.logo_image,d.url_kategori as url_k FROM ui_kategori_sub a JOIN m_kategori_sub b ON a.kategori_sub_id=b.kategori_sub_id JOIN m_kategori_det c ON a.kategori_sub_id=c.kategori_sub_id JOIN m_kategori d ON c.kategori_id=d.kategori_id WHERE b.is_active=1 AND b.is_hapus='n' ORDER BY RAND() $limitk ");

        $val2 = $result2->result_array();
        $rows2 = $result2->num_rows();

        foreach ($val2 as $row) {

            $data[] = array(
                'kategori_id'           => $row['kategori_sub_id'],
                'nama_kategori'         => $row['nama_kategori'],
                'url_k'                 => $row['url_k'],
                'url_kategori'          => $row['url_kategori'],
                'url_l'                 => '',
                'logo_image'            => $row['logo_image'],
                'tipe_k'                => 'lv1'
            );
        }


        return json_encode(array('result'=>$data, 'rows'=>$rows+$rows2));
    }

    public function load_sub_kategori_pilihan_zero($postjson){

        $result = array();
        $data = array();
        $produk_count = 0;

        $pengaturanSistem = pengaturanSistem();

        $result = $this->db->query("SELECT * FROM m_kategori WHERE kategori_id='$postjson[idkategori]'")->result_array();

        foreach ($result as $row) {

            if ($postjson['search']!='') {
                $whr = " AND (a.nama_produk LIKE '%$postjson[search]%') ";
            }else{
                $whr = " ";
            }

            if ($postjson['price']!='0~0') {
                $explprice = explode("~", $postjson['price']);
                if ($explprice[0]!=0 && $explprice[1]==0) {
                    $explprice[1] = 1000000000; // 1M
                }
                $whr_p = " AND a.harga_produk BETWEEN '$explprice[0]' AND '$explprice[1]' ";
            }else{
                $whr_p = " ";
            }

            if ($postjson['sortby']==1) {
                $orderby = " ORDER BY a.produk_id DESC ";
            }else if ($postjson['sortby']==2) {
                $orderby = " ORDER BY a.nama_produk ASC ";
            }else if ($postjson['sortby']==3) {
                $orderby = " ORDER BY a.harga_produk DESC ";
            }else if ($postjson['sortby']==4) {
                $orderby = " ORDER BY a.harga_produk ASC ";
            }else{
                $orderby = " ORDER BY a.produk_id DESC ";
            }

            if ($postjson['tipe']=='limit') {
                $limit = " LIMIT $postjson[start],$postjson[limit] ";
            }else{
                $limit = " ";
            }

            $produk = $this->db->query("SELECT distinct(b.kategori_id) as kategori_id, a.* FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_id='$row[kategori_id]' $whr $whr_p $orderby $limit ")->result_array();

            $produk_count = $this->db->query("SELECT distinct(b.kategori_id) as kategori_id, a.produk_id FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_id='$row[kategori_id]' $whr $whr_p ")->num_rows();
    
            $items = array();
            foreach ($produk as $rows) {
                $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

                $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
                foreach ($wrnstok['result'] as $wrnqukr) :
                $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
                foreach ($ukuranstok['result'] as $qukr) :
                $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
                if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
                $tstok += $stk;
                endforeach;
                endforeach;

                if ($tstok>0) {
                    $stk_lbl = 'Stok Tersedia';
                }else{
                    $stk_lbl = 'Stok Habis';
                }

                if ($rows['potongan_status']=='y') {
                    $today = strtotime(date('Y-m-d')); 
                    $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                    $jarakhari = $today - $tgl_mulai;
                    $selisihari = $jarakhari / 60 / 60 / 24;
                    $jarakhari_a = $tgl_akhir - $today;
                    $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                    if ($selisihari>=0 && $selisihari_a>=0) {
                        $potongan_diskon = $rows['potongan_diskon'];
                        $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'y';
                    }else{
                        $potongan_diskon = 0;
                        $harga_p_new = $rows['harga_produk'];
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'n';
                    }
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }

                if ($pengaturanSistem['global_diskon']>0) {
                    $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                    $potongan_status = 'y';
                }else{
                    $hrga_awal = formatRupiah($rows['harga_produk']);
                }
                
                $items[] = array(
                    'produk_id'         => $rows['produk_id'],
                    'nama_produk'       => $rows['nama_produk'],
                    'url_produk'        => $rows['url_produk'],
                    'harga_produk_awal' => $hrga_awal,
                    'harga_produk'      => $harga_p_new,
                    'potongan_status'   => $potongan_status,
                    'is_new'            => $rows['is_new'],
                    'logo_image'        => $gambar['logo_image'],
                    'tstok'             => $stk_lbl,
                    'stok'              => $tstok
                );
            }


            $data[] = array(
                'kategori_id'           => $row['kategori_id'],
                'nama_kategori'         => $row['nama_kategori'],
                'url_k'                 => $row['url_kategori'],
                'url_kategori'          => $row['url_kategori'],
                'logo_image'            => $row['logo_image'],
                'items'                 => $items,
                'items_count'           => $produk_count
            );
        }

        return json_encode(array('result'=>$data));
    }

    public function load_sub_kategori_pilihan_dua($postjson){

        $result = array();
        $data = array();
        $produk_count = 0;

        $pengaturanSistem = pengaturanSistem();

        if ($postjson['idsubkategori']=='all') {
            $wh = '';
        }else{
            $wh = ' AND b.kategori_sub_id='.$postjson['idsubkategori'];
        }

        if ($postjson['is']=='pilihan') {
            $result = $this->db->query("SELECT a.kategori_sub_id,b.nama_kategori,b.url_kategori,b.logo_image,d.url_kategori as url_k FROM ui_kategori_sub a JOIN m_kategori_sub b ON a.kategori_sub_id=b.kategori_sub_id JOIN m_kategori_det c ON b.kategori_sub_id=c.kategori_sub_id JOIN m_kategori d ON c.kategori_id=d.kategori_id WHERE b.is_active=1 AND b.is_hapus='n' $wh ORDER BY RAND() LIMIT 3 ")->result_array();
        }else if ($postjson['is']=='n') {
            $result = $this->db->query("SELECT b.kategori_sub_id,b.nama_kategori,b.url_kategori,b.logo_image,d.url_kategori as url_k FROM m_kategori_sub b JOIN m_kategori_det c ON b.kategori_sub_id=c.kategori_sub_id JOIN m_kategori d ON c.kategori_id=d.kategori_id WHERE b.is_active=1 AND b.is_hapus='n' $wh")->result_array();
        }


        foreach ($result as $row) {

            if ($postjson['is']=='n') {

                if ($postjson['search']!='') {
                    $whr = " AND (a.nama_produk LIKE '%$postjson[search]%') ";
                }else{
                    $whr = " ";
                }

                if ($postjson['price']!='0~0') {
                    $explprice = explode("~", $postjson['price']);
                    if ($explprice[0]!=0 && $explprice[1]==0) {
                        $explprice[1] = 1000000000; // 1M
                    }
                    $whr_p = " AND a.harga_produk BETWEEN '$explprice[0]' AND '$explprice[1]' ";
                }else{
                    $whr_p = " ";
                }

                if ($postjson['sortby']==1) {
                    $orderby = " ORDER BY a.produk_id DESC ";
                }else if ($postjson['sortby']==2) {
                    $orderby = " ORDER BY a.nama_produk ASC ";
                }else if ($postjson['sortby']==3) {
                    $orderby = " ORDER BY a.harga_produk DESC ";
                }else if ($postjson['sortby']==4) {
                    $orderby = " ORDER BY a.harga_produk ASC ";
                }else{
                    $orderby = " ORDER BY a.produk_id DESC ";
                }

                if ($postjson['tipe']=='limit') {
                    $limit = " LIMIT $postjson[start],$postjson[limit] ";
                }else{
                    $limit = " ";
                }

            }else{
                $orderby = " ORDER BY RAND() ";
                $limit = " LIMIT 15 ";
                $whr = " ";
                $whr_p = " ";
            }

            $produk = $this->db->query("SELECT distinct(b.kategori_sub_id) as kategori_sub_id, a.* FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_id='$row[kategori_sub_id]' $whr $whr_p $orderby $limit ")->result_array();

            $produk_count = $this->db->query("SELECT distinct(b.kategori_sub_id) as kategori_sub_id, a.produk_id FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_id='$row[kategori_sub_id]' $whr $whr_p ")->num_rows();
    
            $items = array();
            foreach ($produk as $rows) {
                $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

                $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
                foreach ($wrnstok['result'] as $wrnqukr) :
                $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
                foreach ($ukuranstok['result'] as $qukr) :
                $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
                if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
                $tstok += $stk;
                endforeach;
                endforeach;

                if ($tstok>0) {
                    $stk_lbl = 'Stok Tersedia';
                }else{
                    $stk_lbl = 'Stok Habis';
                }

                if ($rows['potongan_status']=='y') {
                    $today = strtotime(date('Y-m-d')); 
                    $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                    $jarakhari = $today - $tgl_mulai;
                    $selisihari = $jarakhari / 60 / 60 / 24;
                    $jarakhari_a = $tgl_akhir - $today;
                    $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                    if ($selisihari>=0 && $selisihari_a>=0) {
                        $potongan_diskon = $rows['potongan_diskon'];
                        $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'y';
                    }else{
                        $potongan_diskon = 0;
                        $harga_p_new = $rows['harga_produk'];
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'n';
                    }
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }

                if ($pengaturanSistem['global_diskon']>0) {
                    $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                    $potongan_status = 'y';
                }else{
                    $hrga_awal = formatRupiah($rows['harga_produk']);
                }
                
                $items[] = array(
                    'produk_id'         => $rows['produk_id'],
                    'nama_produk'       => $rows['nama_produk'],
                    'url_produk'        => $rows['url_produk'],
                    'harga_produk_awal' => $hrga_awal,
                    'harga_produk'      => $harga_p_new,
                    'potongan_status'   => $potongan_status,
                    'is_new'            => $rows['is_new'],
                    'logo_image'        => $gambar['logo_image'],
                    'tstok'             => $stk_lbl,
                    'stok'              => $tstok
                );
            }


            $data[] = array(
                'kategori_id'           => $row['kategori_sub_id'],
                'nama_kategori'         => $row['nama_kategori'],
                'url_k'                 => $row['url_k'],
                'url_kategori'          => $row['url_kategori'],
                'logo_image'            => $row['logo_image'],
                'items'                 => $items,
                'items_count'           => $produk_count
            );
        }

        return json_encode(array('result'=>$data));
    }

    public function load_sub_kategori_pilihan_tiga($postjson){

        $produk = array();
        $items = array();
        $produk_count = 0;

        $pengaturanSistem = pengaturanSistem();

        if ($postjson['search']!='') {
            $whr = " AND (a.nama_produk LIKE '%$postjson[search]%') ";
        }else{
            $whr = " ";
        }

        if ($postjson['price']!='0~0') {
            $explprice = explode("~", $postjson['price']);
            if ($explprice[0]!=0 && $explprice[1]==0) {
                $explprice[1] = 1000000000; // 1M
            }
            $whr_p = " AND a.harga_produk BETWEEN '$explprice[0]' AND '$explprice[1]' ";
        }else{
            $whr_p = " ";
        }

        if ($postjson['sortby']==1) {
            $orderby = " ORDER BY a.produk_id DESC ";
        }else if ($postjson['sortby']==2) {
            $orderby = " ORDER BY a.nama_produk ASC ";
        }else if ($postjson['sortby']==3) {
            $orderby = " ORDER BY a.harga_produk DESC ";
        }else if ($postjson['sortby']==4) {
            $orderby = " ORDER BY a.harga_produk ASC ";
        }else{
            $orderby = " ORDER BY a.produk_id DESC ";
        }

        if ($postjson['tipe']=='limit') {
            $limit = " LIMIT $postjson[start],$postjson[limit] ";
        }else{
            $limit = " ";
        }

        $produk = $this->db->query("SELECT distinct(b.kategori_sub_lv2_id) as kategori_sub_lv2_id, a.* FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_lv2_id='$postjson[idsubkategorilv2]' $whr $whr_p $orderby $limit ")->result_array();

        $produk_count = $this->db->query("SELECT distinct(b.kategori_sub_lv2_id) as kategori_sub_lv2_id, a.produk_id FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_lv2_id='$postjson[idsubkategorilv2]' $whr $whr_p ")->num_rows();
    
        foreach ($produk as $rows) {
            $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

            $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
            foreach ($wrnstok['result'] as $wrnqukr) :
            $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
            foreach ($ukuranstok['result'] as $qukr) :
            $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
            if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
            $tstok += $stk;
            endforeach;
            endforeach;

            if ($tstok>0) {
                $stk_lbl = 'Stok Tersedia';
            }else{
                $stk_lbl = 'Stok Habis';
            }

            if ($rows['potongan_status']=='y') {
                $today = strtotime(date('Y-m-d')); 
                $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                $jarakhari = $today - $tgl_mulai;
                $selisihari = $jarakhari / 60 / 60 / 24;
                $jarakhari_a = $tgl_akhir - $today;
                $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                if ($selisihari>=0 && $selisihari_a>=0) {
                    $potongan_diskon = $rows['potongan_diskon'];
                    $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'y';
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }
            }else{
                $potongan_diskon = 0;
                $harga_p_new = $rows['harga_produk'];
                $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                $potongan_status = 'n';
            }

            if ($pengaturanSistem['global_diskon']>0) {
                $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                $potongan_status = 'y';
            }else{
                $hrga_awal = formatRupiah($rows['harga_produk']);
            }
                
            $items[] = array(
                'produk_id'         => $rows['produk_id'],
                'nama_produk'       => $rows['nama_produk'],
                'url_produk'        => $rows['url_produk'],
                'harga_produk_awal' => $hrga_awal,
                'harga_produk'      => $harga_p_new,
                'potongan_status'   => $potongan_status,
                'is_new'            => $rows['is_new'],
                'logo_image'        => $gambar['logo_image'],
                'tstok'             => $stk_lbl,
                'stok'              => $tstok
            );
        }
        

        return json_encode(array('result'=>$items, 'items_count'=>$produk_count));
    }

    public function load_kategori_lv1($postjson){
        $data = array();
        $pengaturanSistem = pengaturanSistem();

        $result = $this->db->query("SELECT * FROM m_kategori_det a JOIN m_kategori_sub b ON a.kategori_sub_id=b.kategori_sub_id WHERE b.is_active=1 AND b.is_hapus='n' AND a.kategori_id='$postjson[idkategori]' ORDER BY RAND() LIMIT 5 ")->result_array();

        foreach ($result as $row) {

            $produk = $this->db->query("SELECT * FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_id='$row[kategori_sub_id]' ORDER BY RAND() LIMIT 15 ")->result_array();
    
            $items = array();
            foreach ($produk as $rows) {
                $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

                $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
                foreach ($wrnstok['result'] as $wrnqukr) :
                $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
                foreach ($ukuranstok['result'] as $qukr) :
                $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
                if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
                $tstok += $stk;
                endforeach;
                endforeach;

                if ($tstok>0) {
                    $stk_lbl = 'Stok Tersedia';
                }else{
                    $stk_lbl = 'Stok Habis';
                }

                if ($rows['potongan_status']=='y') {
                    $today = strtotime(date('Y-m-d')); 
                    $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                    $jarakhari = $today - $tgl_mulai;
                    $selisihari = $jarakhari / 60 / 60 / 24;
                    $jarakhari_a = $tgl_akhir - $today;
                    $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                    if ($selisihari>=0 && $selisihari_a>=0) {
                        $potongan_diskon = $rows['potongan_diskon'];
                        $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'y';
                    }else{
                        $potongan_diskon = 0;
                        $harga_p_new = $rows['harga_produk'];
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'n';
                    }
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }

                if ($pengaturanSistem['global_diskon']>0) {
                    $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                    $potongan_status = 'y';
                }else{
                    $hrga_awal = formatRupiah($rows['harga_produk']);
                }
                
                $items[] = array(
                    'produk_id'         => $rows['produk_id'],
                    'nama_produk'       => $rows['nama_produk'],
                    'url_produk'        => $rows['url_produk'],
                    'harga_produk_awal' => $hrga_awal,
                    'harga_produk'      => $harga_p_new,
                    'potongan_status'   => $potongan_status,
                    'is_new'            => $rows['is_new'],
                    'logo_image'        => $gambar['logo_image'],
                    'tstok'             => $stk_lbl,
                    'stok'              => $tstok
                );
            }


            $data[] = array(
                'kategori_sub_id'       => $row['kategori_sub_id'],
                'nama_kategori'         => $row['nama_kategori'],
                'url_kategori'          => $row['url_kategori'],
                'logo_image'            => $row['logo_image'],
                'items'                 => $items
            );
        }

        return json_encode(array('result'=>$data));
    }

    public function load_kategori_lv2($postjson){
        $data = array();
        $pengaturanSistem = pengaturanSistem();

        $result = $this->db->query("SELECT * FROM m_kategori_sub_det a JOIN m_kategori_sub_lv2 b ON a.kategori_sub_lv2_id=b.kategori_sub_lv2_id WHERE b.is_active=1 AND b.is_hapus='n' AND a.kategori_sub_id='$postjson[idsubkategori]' ORDER BY RAND() LIMIT 5 ")->result_array();

        foreach ($result as $row) {

            $produk = $this->db->query("SELECT * FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_lv2_id='$row[kategori_sub_lv2_id]' ORDER BY RAND() LIMIT 15 ")->result_array();
    
            $items = array();
            foreach ($produk as $rows) {
                $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

                $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
                foreach ($wrnstok['result'] as $wrnqukr) :
                $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
                foreach ($ukuranstok['result'] as $qukr) :
                $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
                if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
                $tstok += $stk;
                endforeach;
                endforeach;

                if ($tstok>0) {
                    $stk_lbl = 'Stok Tersedia';
                }else{
                    $stk_lbl = 'Stok Habis';
                }

                if ($rows['potongan_status']=='y') {
                    $today = strtotime(date('Y-m-d')); 
                    $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                    $jarakhari = $today - $tgl_mulai;
                    $selisihari = $jarakhari / 60 / 60 / 24;
                    $jarakhari_a = $tgl_akhir - $today;
                    $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                    if ($selisihari>=0 && $selisihari_a>=0) {
                        $potongan_diskon = $rows['potongan_diskon'];
                        $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'y';
                    }else{
                        $potongan_diskon = 0;
                        $harga_p_new = $rows['harga_produk'];
                        $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                        $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                        $potongan_status = 'n';
                    }
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }

                if ($pengaturanSistem['global_diskon']>0) {
                    $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                    $potongan_status = 'y';
                }else{
                    $hrga_awal = formatRupiah($rows['harga_produk']);
                }
                
                $items[] = array(
                    'produk_id'         => $rows['produk_id'],
                    'nama_produk'       => $rows['nama_produk'],
                    'url_produk'        => $rows['url_produk'],
                    'harga_produk_awal' => $hrga_awal,
                    'harga_produk'      => $harga_p_new,
                    'potongan_status'   => $potongan_status,
                    'is_new'            => $rows['is_new'],
                    'logo_image'        => $gambar['logo_image'],
                    'tstok'             => $stk_lbl,
                    'stok'              => $tstok
                );
            }


            $data[] = array(
                'kategori_sub_lv2_id'       => $row['kategori_sub_lv2_id'],
                'nama_kategori'             => $row['nama_kategori'],
                'url_kategori'              => $row['url_kategori'],
                'logo_image'                => $row['logo_image'],
                'items'                     => $items
            );
        }

        return json_encode(array('result'=>$data));
    }

    public function load_kategori_lv2_all($postjson){
        $data = array();
        $pengaturanSistem = pengaturanSistem();

        $produk = $this->db->query("SELECT * FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.kategori_sub_id='$postjson[idsubkategori]' ORDER BY RAND() LIMIT 15 ")->result_array();
    
        foreach ($produk as $rows) {
            $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

            $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
            foreach ($wrnstok['result'] as $wrnqukr) :
            $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
            foreach ($ukuranstok['result'] as $qukr) :
            $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
            if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
            $tstok += $stk;
            endforeach;
            endforeach;

            if ($tstok>0) {
                $stk_lbl = 'Stok Tersedia';
            }else{
                $stk_lbl = 'Stok Habis';
            }

            if ($rows['potongan_status']=='y') {
                $today = strtotime(date('Y-m-d')); 
                $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                $jarakhari = $today - $tgl_mulai;
                $selisihari = $jarakhari / 60 / 60 / 24;
                $jarakhari_a = $tgl_akhir - $today;
                $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                if ($selisihari>=0 && $selisihari_a>=0) {
                    $potongan_diskon = $rows['potongan_diskon'];
                    $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'y';
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }
            }else{
                $potongan_diskon = 0;
                $harga_p_new = $rows['harga_produk'];
                $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                $potongan_status = 'n';
            }

            if ($pengaturanSistem['global_diskon']>0) {
                $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                $potongan_status = 'y';
            }else{
                $hrga_awal = formatRupiah($rows['harga_produk']);
            }
                
            $data[] = array(
                'produk_id'         => $rows['produk_id'],
                'nama_produk'       => $rows['nama_produk'],
                'url_produk'        => $rows['url_produk'],
                'harga_produk_awal' => $hrga_awal,
                'harga_produk'      => $harga_p_new,
                'potongan_status'   => $potongan_status,
                'is_new'            => $rows['is_new'],
                'logo_image'        => $gambar['logo_image'],
                'tstok'             => $stk_lbl,
                'stok'              => $tstok
            );
        }

        return json_encode(array('result'=>$data));
    }

    public function load_produk($postjson){

        $data = array();
        $q_k = array();
        $pengaturanSistem = pengaturanSistem();

        if ($postjson['tipe']=='limit') {
            $limit = " LIMIT $postjson[start],$postjson[limit] ";
        }else{
            $limit = " ";
        }

        if ($postjson['new']=='y') {
            $wh = " AND is_new='y'";
        }else{
            $wh = " ";
        }

        if ($postjson['idproduk']!='n') {
            $p_id = $this->db->query("SELECT produk_id FROM m_produk WHERE url_produk='$postjson[idproduk]'")->row_array();
            $q_k = $this->db->query("SELECT b.kategori_id, b.is_active as s_f, b.is_hapus as h_f, b.url_kategori as url_k, b.nama_kategori as nama_k, c.kategori_sub_id, c.url_kategori as url_k1, c.nama_kategori as nama_k1, d.kategori_sub_lv2_id, d.url_kategori as url_k2, d.nama_kategori as nama_k2 FROM m_produk_kategori a JOIN m_kategori b ON a.kategori_id=b.kategori_id LEFT JOIN m_kategori_sub c ON a.kategori_sub_id=c.kategori_sub_id LEFT JOIN m_kategori_sub_lv2 d ON a.kategori_sub_lv2_id=d.kategori_sub_lv2_id WHERE a.produk_id='$p_id[produk_id]'")->row_array();
            $wh_p = " AND url_produk='$postjson[idproduk]'";

            $ratingProduk = hitungRating($p_id['produk_id']);

            // if ($q_k['s_f']!=0 && $q_k['h_f']!='y') {
            //     $ishow = 'y';
            // }else{
            //     $ishow = 'n';
            // }

        }else{
            $wh_p = " ";
            $ratingProduk = 0;
        }


        if ($postjson['wishlist']=='y') {
            $produk = $this->db->query("SELECT * FROM m_produk a JOIN tx_wishlist b ON a.produk_id=b.produk_id WHERE a.is_active=1 AND a.is_hapus='n' AND b.cust_id='$postjson[idcust]' $wh $wh_p ORDER BY b.wishlist_id DESC $limit ")->result_array();
        }else if ($postjson['wishlist']=='bestseller') {
            $produk = $this->db->query("SELECT a.*, sum(d.keluar) as best FROM m_produk a LEFT JOIN tx_stok d ON a.produk_id=d.produk_id WHERE a.is_active=1 AND a.is_hapus='n' GROUP BY a.produk_id ORDER BY best DESC $limit ")->result_array();
        }else{
            $produk = $this->db->query("SELECT * FROM m_produk WHERE is_active=1 AND is_hapus='n' $wh $wh_p ORDER BY nama_produk ASC $limit ")->result_array();
        }
    
        foreach ($produk as $rows) {
            $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();
            $gambar_all = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->result_array();

            $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
            foreach ($wrnstok['result'] as $wrnqukr) :
            $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
            foreach ($ukuranstok['result'] as $qukr) :
            $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
            if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
            $tstok += $stk;
            endforeach;
            endforeach;

            if ($tstok>0) {
                $stk_lbl = 'Stok Tersedia';
            }else{
                $stk_lbl = 'Stok Habis';
            }

            if ($rows['potongan_status']=='y') {
                $today = strtotime(date('Y-m-d')); 
                $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                $jarakhari = $today - $tgl_mulai;
                $selisihari = $jarakhari / 60 / 60 / 24;
                $jarakhari_a = $tgl_akhir - $today;
                $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                if ($selisihari>=0 && $selisihari_a>=0) {
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
                
            $data[] = array(
                'produk_id'             => $rows['produk_id'],
                'nama_produk'           => $rows['nama_produk'],
                'url_produk'            => $rows['url_produk'],
                'harga_produk_awal'     => formatRupiah($hrga_awal),
                'harga_produk'          => formatRupiah($harga_p_new),
                'harga_produk_awal_num' => $hrga_awal,
                'harga_produk_num'      => $harga_p_new,
                'potongan_status'       => $potongan_status,
                'berat_produk'          => $rows['berat_produk'],
                'keterangan_produk'     => $rows['keterangan_produk'],
                'is_new'                => $rows['is_new'],
                'logo_image'            => $gambar['logo_image'],
                'all_image'             => $gambar_all,
                'tstok'                 => $stk_lbl,
                'stok'                  => $tstok
            );
        }

        return json_encode(array('success'=>true, 'result'=>$data, 'q_k'=>$q_k, 'rat_produk'=>$ratingProduk));
    }

    public function load_produk_varian($postjson){

        $data = array();
        $warna = array();
        $ukuran = array();

        $q_warna = $this->db->query("SELECT * FROM m_produk_warna a JOIN m_warna b ON a.warna_id=b.warna_id WHERE a.produk_id='$postjson[idproduk]' AND a.is_status='y' AND b.is_hapus='n' AND a.warna_id>1");

        $res_w = $q_warna->result_array();
        $row_w = $q_warna->num_rows();
   
        foreach ($res_w as $row) {
            $warna[] = array(
                'warna_id'             => $row['warna_id'],
                'nama_warna'           => $row['nama_warna']
            );
        }

        $q_ukuran = $this->db->query("SELECT * FROM m_produk_ukuran a JOIN m_ukuran b ON a.ukuran_id=b.ukuran_id WHERE a.produk_id='$postjson[idproduk]' AND a.is_status='y' AND b.is_hapus='n' AND a.ukuran_id>1");

        $res_u = $q_ukuran->result_array();
        $row_u = $q_ukuran->num_rows();
   
        foreach ($res_u as $rows) {

            if ($rows['tambahan_harga']>0) {
                $tmbhn_hrga = " &nbsp;+&nbsp; ".formatRupiah($rows['tambahan_harga']);
            }else{
                $tmbhn_hrga = '';
            }

            $ukuran[] = array(
                'ukuran_id'        => $rows['ukuran_id'].'~'.$rows['tambahan_harga'],
                'ukuran'           => $rows['ukuran_size'].$tmbhn_hrga
            );
        }

        $data = array(
            'p_warna'       => $warna,
            'row_w'         => $row_w,
            'p_ukuran'      => $ukuran,
            'row_u'         => $row_u
        );

        return json_encode(array('success'=>true, 'result'=>$data));

    }

    public function load_produk_stok($postjson){

        if ($postjson['idwarna']==0) {
            $idw = 1;
        }else{
            $idw = $postjson['idwarna'];
        }

        $c_w = $this->db->query("SELECT a.produk_warna_id,b.nama_warna FROM m_produk_warna a JOIN m_warna b ON a.warna_id=b.warna_id WHERE a.produk_id='$postjson[idproduk]' AND a.warna_id='$idw'")->row_array();

        if ($postjson['idukuran']==0) {
            $idu = 1;
        }else{
            $idu = $postjson['idukuran'];
        }

        $c_u = $this->db->query("SELECT a.produk_ukuran_id,b.ukuran_size FROM m_produk_ukuran a JOIN m_ukuran b ON a.ukuran_id=b.ukuran_id WHERE a.produk_id='$postjson[idproduk]' AND a.ukuran_id='$idu'")->row_array();

        if ($c_w['nama_warna']!='') {
            if ($c_w['nama_warna']=='Default') {
                $c_w['nama_warna'] = "";
            }else{
                $c_w['nama_warna'] = $c_w['nama_warna'].", ";
            }
        }

        if ($c_u['ukuran_size']!='') {
            if ($c_u['ukuran_size']=='Default') {
                $c_u['ukuran_size'] = "";
            }else{
                $c_u['ukuran_size'] = $c_u['ukuran_size'].", ";
            }
        }

        if ($c_w['nama_warna']=='' && $c_u['ukuran_size']=='') {
            $varian = ' ';
        }else{
            $varian = substr($c_w['nama_warna'].$c_u['ukuran_size'], 0,-2);
        }

        $stok = $this->produk->cekStok($postjson['idproduk'],$c_w['produk_warna_id'],$c_u['produk_ukuran_id']);

        return json_encode(array('success'=>true, 'stok'=>$stok['akhir'], 'stok'=>$stok['akhir'], 'varian'=>$varian));

    }

    public function load_produk_search($postjson){

        $data = array();
        $produk_count = 0;

        $pengaturanSistem = pengaturanSistem();

        if ($postjson['search']!='') {
            $whr = " AND (a.nama_produk LIKE '%$postjson[search]%') ";
        }else{
            $whr = " ";
        }

        if ($postjson['price']!='0~0') {
            $explprice = explode("~", $postjson['price']);
            if ($explprice[0]!=0 && $explprice[1]==0) {
                $explprice[1] = 1000000000; // 1M
            }
            $whr_p = " AND a.harga_produk BETWEEN '$explprice[0]' AND '$explprice[1]' ";
        }else{
            $whr_p = " ";
        }

        if ($postjson['sortby']==1) {
            $orderby = " ORDER BY a.produk_id DESC ";
        }else if ($postjson['sortby']==2) {
            $orderby = " ORDER BY a.nama_produk ASC ";
        }else if ($postjson['sortby']==3) {
            $orderby = " ORDER BY a.harga_produk DESC ";
        }else if ($postjson['sortby']==4) {
            $orderby = " ORDER BY a.harga_produk ASC ";
        }else{
            $orderby = " ORDER BY a.produk_id DESC ";
        }

        if ($postjson['tipe']=='limit') {
            $limit = " LIMIT $postjson[start],$postjson[limit] ";
        }else{
            $limit = " ";
        }

        $produk = $this->db->query("SELECT distinct(b.produk_id), a.*, c.is_active, c.is_hapus FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id JOIN m_kategori c ON b.kategori_id=c.kategori_id WHERE a.is_active=1 AND a.is_hapus='n' AND c.is_active=1 AND c.is_hapus='n' $whr $whr_p $orderby $limit ")->result_array();

        $produk_count = $this->db->query("SELECT distinct(b.produk_id), a.produk_id, c.is_active, c.is_hapus FROM m_produk a JOIN m_produk_kategori b ON a.produk_id=b.produk_id JOIN m_kategori c ON b.kategori_id=c.kategori_id WHERE a.is_active=1 AND a.is_hapus='n' AND c.is_active=1 AND c.is_hapus='n' $whr $whr_p ")->num_rows();
    
        foreach ($produk as $rows) {

            $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

            $tstok = 0; $wrnstok = $this->produk->dataprodWarna($rows['produk_id'],'y');
            foreach ($wrnstok['result'] as $wrnqukr) :
            $ukuranstok = $this->produk->dataprodUkuran($rows['produk_id'],'y');
            foreach ($ukuranstok['result'] as $qukr) :
            $stok = $this->produk->cekStok($rows['produk_id'],$wrnqukr['produk_warna_id'],$qukr['produk_ukuran_id']);
            if(!isset($stok['akhir'])) $stk = 0; else $stk = $stok['akhir'];
            $tstok += $stk;
            endforeach;
            endforeach;

            if ($tstok>0) {
                $stk_lbl = 'Stok Tersedia';
            }else{
                $stk_lbl = 'Stok Habis';
            }

            if ($rows['potongan_status']=='y') {
                $today = strtotime(date('Y-m-d')); 
                $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                $jarakhari = $today - $tgl_mulai;
                $selisihari = $jarakhari / 60 / 60 / 24;
                $jarakhari_a = $tgl_akhir - $today;
                $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                if ($selisihari>=0 && $selisihari_a>=0) {
                    $potongan_diskon = $rows['potongan_diskon'];
                    $harga_p_new = $rows['harga_produk']-$potongan_diskon;
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'y';
                }else{
                    $potongan_diskon = 0;
                    $harga_p_new = $rows['harga_produk'];
                    $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                    $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                    $potongan_status = 'n';
                }
            }else{
                $potongan_diskon = 0;
                $harga_p_new = $rows['harga_produk'];
                $global_diskon = ($harga_p_new*$pengaturanSistem['global_diskon'])/100;
                $harga_p_new = formatRupiah($harga_p_new-$global_diskon);
                $potongan_status = 'n';
            }

            if ($pengaturanSistem['global_diskon']>0) {
                $hrga_awal = formatRupiah($rows['harga_produk']-$potongan_diskon);
                $potongan_status = 'y';
            }else{
                $hrga_awal = formatRupiah($rows['harga_produk']);
            }
                
            $data[] = array(
                'produk_id'         => $rows['produk_id'],
                'nama_produk'       => $rows['nama_produk'],
                'url_produk'        => $rows['url_produk'],
                'harga_produk_awal' => $hrga_awal,
                'harga_produk'      => $harga_p_new,
                'potongan_status'   => $potongan_status,
                'is_new'            => $rows['is_new'],
                'logo_image'        => $gambar['logo_image'],
                'tstok'             => $stk_lbl,
                'stok'              => $tstok
            );
        }

        return json_encode(array('result'=>$data, 'items_count'=>$produk_count));
    }

    public function load_cart($postjson){

        $data = array();
        $pengaturanSistem = pengaturanSistem();
        $total_bayar = 0;

        $query = $this->db->query("SELECT a.*,b.*,c.nama_warna,d.ukuran_size FROM tx_cart a JOIN m_produk b ON a.produk_id=b.produk_id LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE b.is_active=1 AND b.is_hapus='n' AND a.cust_id='$postjson[idcust]'");

        $mcart = $query->result_array();
        $mcart_count = $query->num_rows();
    
        foreach ($mcart as $rows) {
            $gambar = $this->db->query("SELECT * FROM m_produk_img WHERE produk_id='$rows[produk_id]'")->row_array();

            $l_w = $this->db->query("SELECT * FROM m_produk_warna WHERE produk_id='$rows[produk_id]' AND warna_id='$rows[warna_id]'")->row_array();
            $l_u = $this->db->query("SELECT * FROM m_produk_ukuran WHERE produk_id='$rows[produk_id]' AND ukuran_id='$rows[ukuran_id]'")->row_array();

            $stok = $this->produk->cekStok($rows['produk_id'],$l_w['produk_warna_id'],$l_u['produk_ukuran_id']);

            if ($stok['akhir']>=$rows['jumlah_beli']) {
                $stk_lbl = 'y';
            }else{
                $stk_lbl = 'Tidak dapat di proses, stok tidak tersedia.';
            }

            if ($rows['potongan_status']=='y') {
                $today = strtotime(date('Y-m-d')); 
                $tgl_mulai = strtotime($rows['potongan_mulai']); $tgl_akhir = strtotime($rows['potongan_akhir']);
                $jarakhari = $today - $tgl_mulai;
                $selisihari = $jarakhari / 60 / 60 / 24;
                $jarakhari_a = $tgl_akhir - $today;
                $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                if ($selisihari>=0 && $selisihari_a>=0) {
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

            if ($stk_lbl=='y') {
                $total_bayar += ($harga_p_new*$rows['jumlah_beli']);
            }

   
            $data[] = array(
                'cart_id'               => $rows['cart_id'],
                'produk_id'             => $rows['produk_id'],
                'nama_produk'           => $rows['nama_produk'],
                'url_produk'            => $rows['url_produk'],
                'harga_produk_awal'     => formatRupiah($hrga_awal),
                'harga_produk_awal_q'   => formatRupiah($hrga_awal*$rows['jumlah_beli']),
                'harga_produk'          => formatRupiah($harga_p_new),
                'harga_produk_num'      => $harga_p_new,
                'harga_produk_q'        => formatRupiah($harga_p_new*$rows['jumlah_beli']),
                'potongan_status'       => $potongan_status,
                'is_new'                => $rows['is_new'],
                'logo_image'            => $gambar['logo_image'],
                'tstok'                 => $stk_lbl,
                'varian'                => $varian,
                'jumlah_beli'           => $rows['jumlah_beli']
            );
        }

        return json_encode(array('result'=>$data, 'items_count'=>$mcart_count, 'total_bayar'=>formatRupiah($total_bayar), 'total_bayar_num'=>$total_bayar));
    }

    public function load_item_cart($postjson){

        $query = $this->db->query("SELECT a.*,b.url_produk FROM tx_cart a JOIN m_produk b ON a.produk_id=b.produk_id WHERE a.cart_id='$postjson[idcart]' AND a.cust_id='$postjson[idcust]'")->row_array();

        return json_encode(array('result'=>$query));

    }

    public function load_jumlah_cart($postjson){

        $query = $this->db->query("SELECT cart_id FROM tx_cart WHERE cust_id='$postjson[idcust]'")->num_rows();

        return json_encode(array('jumlah_cart'=>$query));

    }

    public function load_alamat_customer($postjson){

        $res = array();

        if ($postjson['tipe']=='all') {
            $qs = $this->db->query("SELECT * FROM m_customer_det WHERE cust_id='$postjson[idcust]' AND is_selected=1")->row_array();
            $query = $this->db->query("SELECT * FROM m_customer_det WHERE cust_id='$postjson[idcust]'");
            $res = $query->result_array(); 
            $nums = $query->num_rows(); 
            return json_encode(array('result'=>$res, 'iselected'=>$qs, 'nums'=>$nums));
        }else if ($postjson['tipe']=='edit') {
            $res = $this->db->query("SELECT a.*,b.cust_nama,b.cust_ponsel,b.is_token FROM m_customer_det a JOIN m_customer b ON a.cust_id=b.cust_id WHERE a.cust_id='$postjson[idcust]' AND a.cust_det_id='$postjson[idalamat]'")->row_array();
            return json_encode(array('result'=>$res));
        }else{
            return json_encode(array('result'=>$res));
        }

    }

    public function load_provinsi($postjson){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "key:674d41545a7e782d6d5afdcea1f9d412"
            ),
        ));

        $response = curl_exec($curl); $err = curl_error($curl); curl_close($curl);

        if ($err) {
            $data_p = "cURL Error #:" . $err;
            return json_encode(array('success'=>false, 'result'=>$data_p));
        } else {
            $data_p = json_decode($response, true);
            return json_encode(array('success'=>true, 'result'=>$data_p['rajaongkir']['results']));
        }

    }

    public function load_kabkot($postjson){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/city?province=".$postjson['idkabkot'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "key:674d41545a7e782d6d5afdcea1f9d412"
            ),
        ));

        $response = curl_exec($curl); $err = curl_error($curl); curl_close($curl);

        if ($err) {
            $data_p = "cURL Error #:" . $err;
            return json_encode(array('success'=>false, 'result'=>$data_p));
        } else {
            $data_p = json_decode($response, true);
            return json_encode(array('success'=>true, 'result'=>$data_p['rajaongkir']['results']));
        }

    }

    public function load_kurir_cost($postjson){
        $berat = 0;
        $res = array();
        $q = $this->db->query("SELECT * FROM tx_cart a JOIN m_produk b ON a.produk_id=b.produk_id WHERE a.cust_id='$postjson[idcust]'")->result_array();
        foreach ($q as $row) {
            $berat += $row['berat_produk']*$row['jumlah_beli'];
        }

        $this->db->delete('tx_kurir', ['cust_id' => $postjson['idcust'], 'status_submit' => 'n']);
        
        $origin_kotaasal = 151; // Jakarta Barat
        $lblorigin_kotaasal = 'Jakarta Barat';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=".$origin_kotaasal."&originType=city&destination=".$postjson['kabkot_id']."&destinationType=city&weight=".$berat."&courier=".$postjson['kurir_id']."",
            CURLOPT_HTTPHEADER => array(
              "content-type: application/x-www-form-urlencoded",
              "key:674d41545a7e782d6d5afdcea1f9d412"
            ),
        ));

        $response = curl_exec($curl); $err = curl_error($curl); curl_close($curl);

        if ($err) {
            $data_p = "cURL Error #:" . $err;
            return json_encode(array('success'=>false, 'result'=>$data_p));
        } else {
            $data_p = json_decode($response, true);

            if ($postjson['kurir_id']=='jne') {
                $nama_kurir = 'JNE';
            }else if ($postjson['kurir_id']=='jnt') {
                $nama_kurir = 'J&T Express';
            }else if ($postjson['kurir_id']=='sicepat') {
                $nama_kurir = 'SiCepat Express';
            }else if ($postjson['kurir_id']=='anteraja') {
                $nama_kurir = 'AnterAja';
            }else if ($postjson['kurir_id']=='lion') {
                $nama_kurir = 'Lion Parcel';
            }else if ($postjson['kurir_id']=='pos') {
                $nama_kurir = 'POS Indonesia';
            }else{
                $nama_kurir = $postjson['kurir_id'];
            }

            for($i=0; $i<count($data_p['rajaongkir']['results'][0]['costs']); $i++){
                for($ix=0; $ix<count($data_p['rajaongkir']['results'][0]['costs'][$i]['cost']); $ix++){

                    $idnya = urutId('tx_kurir',"kurir_id");

                    if ($data_p['rajaongkir']['results'][0]['costs'][$i]['cost'][$ix]['etd']=='' || $data_p['rajaongkir']['results'][0]['costs'][$i]['cost'][$ix]['etd']==0) {
                        $esimasi = 1;
                    }else{
                        $esimasi = $data_p['rajaongkir']['results'][0]['costs'][$i]['cost'][$ix]['etd'];
                    }

                    $data = [
                        'kurir_id'          => $idnya,
                        'cust_id'           => $postjson['idcust'],
                        'pusat_pengiriman'  => $lblorigin_kotaasal,
                        'provinsi_tujuan'   => $data_p['rajaongkir']['destination_details']['province'],
                        'kota_tujuan'       => $data_p['rajaongkir']['destination_details']['city_name'],
                        'kurir'             => $postjson['kurir_id'],
                        'nama_kurir'        => $nama_kurir,
                        'level_kurir'       => $data_p['rajaongkir']['results'][0]['costs'][$i]['service'],
                        'lama_pengiriman'   => $esimasi,
                        'ongkos_kirim'      => $data_p['rajaongkir']['results'][0]['costs'][$i]['cost'][$ix]['value'],
                        'status_submit'     => 'n',
                        'berat_produk'      => $berat
                    ];

                    if ($postjson['idcust']!=null) {
                        $this->db->insert('tx_kurir', $data);
                    }
                }
            }

            $res = $this->db->query("SELECT * FROM tx_kurir WHERE cust_id='$postjson[idcust]' AND status_submit='n'")->result_array();

            return json_encode(array('success'=>true, 'result'=>$res));
        }

    }

    public function load_kurir_pilihan($postjson){
        $res = $this->db->query("SELECT * FROM tx_kurir WHERE cust_id='$postjson[idcust]' AND kurir_id='$postjson[idkurir]'")->row_array();
        return json_encode(array('success'=>true, 'result'=>$res));
    }

    public function load_riwayat_transaksi($postjson){
        $data = array();

        if ($postjson['idtrx']!='n') {
            $wh = " AND a.unique_id='$postjson[idtrx]' ";
        }else{
            $wh = "";
        }

        $res = $this->db->query("SELECT a.*,b.kurir,b.nama_kurir,b.level_kurir,b.lama_pengiriman FROM tx_transaksi a JOIN tx_kurir b ON a.no_transaksi=b.no_transaksi WHERE a.cust_id='$postjson[idcust]' $wh ORDER BY a.transaksi_id DESC ")->result_array();
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

            if ($postjson['idtrx']!='n') {
                $cara_bayar = array();
                $q_cb = $this->db->query("SELECT * FROM m_cara_bayar WHERE biller_code='$rows[biller_code]'")->result_array();
                foreach ($q_cb as $qc) {
                    $q_cbs = $this->db->query("SELECT * FROM m_cara_bayar_det WHERE cara_bayar_id='$qc[cara_bayar_id]' ORDER BY no_urutan ASC")->result_array();
                    $cara_bayar[] = array(
                        'cara_bayar_id'  => $qc['cara_bayar_id'],
                        'biller_code'    => $qc['biller_code'],
                        'jenis_bayar'    => $qc['jenis_bayar'],
                        'cara_bayar'     => $q_cbs
                    );
                }

            }else{
                $cara_bayar = array();
            }

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
                'metode_pembayaran' => $rows['metode_pembayaran'],
                'bukti_pembayaran'  => $rows['bukti_pembayaran'],
                'if_cancel'         => $rows['if_cancel'],
                'payment_type'      => $rows['payment_type'],
                'biller_code'       => $rows['biller_code'],
                'bill_key'          => $rows['bill_key'],
                'nomor_resi'        => $rows['nomor_resi'],
                'cara_bayar'        => $cara_bayar,
                'm_bayar'           => $m_bayar,
                'kurir'             => $rows['kurir'],
                'nama_kurir'        => $rows['nama_kurir'],
                'level_kurir'       => $rows['level_kurir'],
                'lama_pengiriman'   => $rows['lama_pengiriman'],
                'tgl_transaksi'     => indo($rows['tgl_transaksi'])
            );
        }

        if ($postjson['idtrx']!='n') {
            $m_cart = array();
            $q_cart = $this->db->query("SELECT b.*,c.nama_warna,d.ukuran_size FROM tx_transaksi a JOIN tx_transaksi_det b ON a.no_transaksi=b.no_transaksi LEFT JOIN m_warna c ON b.warna_id=c.warna_id LEFT JOIN m_ukuran d ON b.ukuran_id=d.ukuran_id WHERE a.cust_id='$postjson[idcust]' AND a.unique_id='$postjson[idtrx]' ")->result_array();
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
                    'nama_produk'               => $rows['nama_produk'],
                    'harga_produk'              => formatRupiahnorp($harga_produk),
                    'hs_diskon'                 => formatRupiahnorp($hs_diskon),
                    'jumlah_beli'               => $rows['jumlah_beli'],
                    'total_harga_produk'        => formatRupiahnorp($tharga_produk),
                    'hst_diskon'                => formatRupiahnorp($hst_diskon),
                    'catatan_produk'            => $rows['catatan_produk'],
                    'varian'                    => $varian
                );
            }
            $m_bank = $this->db->query("SELECT * FROM m_bank WHERE is_active='1' AND is_hapus='n'")->result_array();
            $m_alamat = $this->db->query("SELECT b.* FROM tx_transaksi a JOIN m_customer_det b ON a.cust_det_id=b.cust_det_id WHERE a.cust_id='$postjson[idcust]' AND a.unique_id='$postjson[idtrx]' ")->row_array();
        }else{
            $m_cart = array();
            $m_bank = array();
            $m_alamat = array();
        }
        
        return json_encode(array('success'=>true, 'result'=>$data, 'm_cart'=>$m_cart, 'm_bank'=>$m_bank, 'm_alamat'=>$m_alamat));
    }

    public function load_cek_resi($postjson){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "waybill=".$postjson['noresi']."&courier=".$postjson['kurir'],
            CURLOPT_HTTPHEADER => array(
              "content-type: application/x-www-form-urlencoded",
              "key:674d41545a7e782d6d5afdcea1f9d412"
            ),
        ));

        $response = curl_exec($curl); $err = curl_error($curl); curl_close($curl);

        if ($err) {
            $data_p = "cURL Error #:" . $err;
            return json_encode(array('success'=>false, 'result'=>$data_p));
        } else {
            $data_p = json_decode($response, true);
            return json_encode(array('success'=>true, 'result'=>$data_p['rajaongkir']));
        }

    }

    public function load_ulasan_transaksi($postjson){
        $data = array();
        $query = $this->db->query("SELECT a.*,c.nama_warna,d.ukuran_size FROM tx_transaksi_det a LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE a.cust_id='$postjson[idcust]' AND a.unique_id='$postjson[idtrx]'")->result_array();
    
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
                'publikasi_rating'          => $rows['publikasi_rating'],
                'ulasan_produk'             => $rows['ulasan_produk'],
                'publikasi_ulasan'          => $rows['publikasi_ulasan'],
                'varian'                    => $varian
            );

        }

        return json_encode(array('result'=>$data));
    }

    public function load_notifikasi($postjson){

        if ($postjson['idnotif']!='n') {
            $wh = ' AND notifikasi_id='.$postjson['idnotif'];
        }else{
            $wh = '';
        }

        $query = $this->db->query("SELECT * FROM tx_notifikasi WHERE cust_id='$postjson[idcust]' $wh ORDER BY notifikasi_id DESC");
        $res = $query->result_array();
        $nums = $query->num_rows();
        return json_encode(array('result'=>$res, 'nums'=>$nums));
    }

    public function load_auto_cek_notifikasi($postjson){
        $res = $this->db->query("SELECT * FROM tx_notifikasi WHERE cust_id='$postjson[idcust]' AND is_read='n'")->num_rows();
        return json_encode(array('msg'=>$res));
    }

    public function load_ulasan_produk($postjson){
        $data = array();

        if ($postjson['tipe']=='limit') {
            $limit = " LIMIT $postjson[start],$postjson[limit] ";
        }else if ($postjson['tipe']=='limitweb') {
            $limit = " LIMIT 3 ";
        }else{
            $limit = " ";
        }

        $res = $this->db->query("SELECT a.rating_produk,a.ulasan_produk,a.tgl_ulasan,b.cust_nama,c.nama_warna,d.ukuran_size FROM tx_transaksi_det a JOIN m_customer b ON a.cust_id=b.cust_id LEFT JOIN m_warna c ON a.warna_id=c.warna_id LEFT JOIN m_ukuran d ON a.ukuran_id=d.ukuran_id WHERE a.produk_id='$postjson[idproduk]' AND a.ulasan_produk!='' AND a.publikasi_ulasan='y' ORDER BY transaksi_det_id DESC $limit ")->result_array();

        $nums = $this->db->query("SELECT transaksi_det_id FROM tx_transaksi_det WHERE produk_id='$postjson[idproduk]' AND ulasan_produk!='' AND publikasi_ulasan='y'")->num_rows();
    
        foreach ($res as $rows) { 

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
                'cust_nama'                 => $rows['cust_nama'],
                'rating_produk'             => $rows['rating_produk'],
                'ulasan_produk'             => $rows['ulasan_produk'],
                'tgl_ulasan'                => indo($rows['tgl_ulasan']),
                'varian'                    => $varian
            );

        }

        return json_encode(array('result'=>$data, 'nums'=>$nums));
    }

    public function load_voucher($postjson){
        $data = array();
        $query = $this->db->query("SELECT * FROM m_voucher WHERE status_voucher='y' AND is_hapus='n'");
        $res = $query->result_array(); $items_count = $query->num_rows();

        foreach ($res as $rows) {

            if ($rows['tipe_voucher']==1) {
                $hlbl = formatRupiah($rows['nominal_voucher']);
            }else{
                $hlbl = $rows['nominal_voucher'].'%';
            }

            if ($rows['maksimal_diskon']==0) {
                $xlbl = 'Tidak ada batasan';
            }else{
                $xlbl = formatRupiah($rows['maksimal_diskon']);
            }

            if ($rows['khusus_cust_baru']=='y') {
                $llbl = 'Tidak ada batasan waktu';
            }else{
                $llbl = indo($rows['tgl_mulai'])." s/d ".indo($rows['tgl_akhir']);
            }

            $data[] = array(
                'nama_voucher'     => $rows['nama_voucher'],
                'kode_voucher'     => $rows['kode_voucher'],
                'minimal_belanja'  => formatRupiah($rows['minimal_belanja']),
                'hlbl'             => $hlbl,
                'xlbl'             => $xlbl,
                'llbl'             => $llbl
            );

        }

        return json_encode(array('result'=>$data, 'items_count'=>$items_count));
    }

    public function check_voucher($postjson){
        $query = $this->db->query("SELECT * FROM m_voucher WHERE status_voucher='y' AND is_hapus='n' AND kode_voucher='$postjson[kode]'")->row_array();
        $nominal = 0;
        if ($query) {
            if ($query['khusus_cust_baru']=='y') {
                $limit = $this->db->query("SELECT * FROM m_voucher_det WHERE kode_voucher='$postjson[kode]' AND cust_id='$postjson[idcust]'")->num_rows();
                if ($limit==0) {
                    if ($postjson['total_bayar']>=$query['minimal_belanja']) {
                        $st = 'y';
                        $nominal = $postjson['total_bayar']*$query['nominal_voucher']/100;
                        if ($query['tipe_voucher']==1) {
                            $nominal = $query['nominal_voucher'];
                        }else{
                            $nominal = $postjson['total_bayar']*$query['nominal_voucher']/100;
                            if ($query['maksimal_diskon']!=0) {
                                if ($nominal>$query['maksimal_diskon']) {
                                    $nominal = $query['maksimal_diskon'];
                                }
                            }
                        }
                    }else{
                        $st = 'Minimal belanja '.formatRupiah($query['minimal_belanja']);
                    }
                }else{
                    $st = 'Voucher ini hanya untuk pembelian pertama atau pengguna baru.';
                }
            }else{
                $limit = $this->db->query("SELECT * FROM m_voucher_det WHERE kode_voucher='$postjson[kode]'")->num_rows();
                if ($query['jumlah_voucher']>$limit) {
                    $today = strtotime(date('Y-m-d')); 
                    $tgl_mulai = strtotime($query['tgl_mulai']); $tgl_akhir = strtotime($query['tgl_akhir']);
                    $jarakhari = $today - $tgl_mulai;
                    $selisihari = $jarakhari / 60 / 60 / 24;
                    $jarakhari_a = $tgl_akhir - $today;
                    $selisihari_a = $jarakhari_a / 60 / 60 / 24;

                    if ($selisihari>=0 && $selisihari_a>=0) {
                        if ($postjson['total_bayar']>=$query['minimal_belanja']) {
                            $st = 'y';
                            if ($query['tipe_voucher']==1) {
                                $nominal = $query['nominal_voucher'];
                            }else{
                                $nominal = $postjson['total_bayar']*$query['nominal_voucher']/100;
                                if ($query['maksimal_diskon']!=0) {
                                    if ($nominal>$query['maksimal_diskon']) {
                                        $nominal = $query['maksimal_diskon'];
                                    }
                                }
                            }
                        }else{
                            $st = 'Minimal belanja '.formatRupiah($query['minimal_belanja']);
                        }
                    }else{
                        $st = 'Voucher sudah tidak berlaku.';
                    }
                }else{
                    $st = 'Limit voucher sudah habis.';
                }
            }
        }else{
            $st = 'Voucher tidak tersedia.';
        }

        return json_encode(array('st'=>$st, 'nominal'=>$nominal));
    }

    public function load_riwayat_topup($postjson){

        $data = array();

        $query = $this->db->query("SELECT * FROM tx_topup WHERE cust_id='$postjson[idcust]' ORDER BY topup_id DESC")->result_array();
   
        foreach ($query as $rows) {

            if ($rows['is_status']=='y') {
                $st = 'Selesai';
                $stx = 'success';
            }else if ($rows['is_status']=='p') {
                $st = 'Pending';
                $stx = 'warning';
            }else if ($rows['is_status']=='b') {
                $st = 'Dibatalkan';
                $stx = 'danger';
            }else{
                $st = 'Unknown';
                $stx = '-';
            }

            $data[] = array(
                'uid'           => $rows['unique_id'],
                'kode'          => $rows['kode_topup'],
                'nominal'       => formatRupiah($rows['nominal_topup']),
                'status'        => $st,
                'status_clr'    => $stx,
                'tanggal'       => indo($rows['created_at'])
            );
        
        }

        return json_encode(array('success'=>true, 'result'=>$data));

    }

    public function load_riwayat_topup_det($postjson){

        $result = $this->db->query("SELECT * FROM tx_topup WHERE cust_id='$postjson[idcust]' AND unique_id='$postjson[idtrx]'")->row_array();

        $cara_bayar = array();
        $q_cb = $this->db->query("SELECT * FROM m_cara_bayar WHERE biller_code='$result[biller_code]'")->result_array();
        foreach ($q_cb as $qc) {
            $q_cbs = $this->db->query("SELECT * FROM m_cara_bayar_det WHERE cara_bayar_id='$qc[cara_bayar_id]' ORDER BY no_urutan ASC")->result_array();
            $cara_bayar[] = array(
                'cara_bayar_id'  => $qc['cara_bayar_id'],
                'biller_code'    => $qc['biller_code'],
                'jenis_bayar'    => $qc['jenis_bayar'],
                'cara_bayar'     => $q_cbs
            );
        }

        if ($result['biller_code']=='bca') {
            $m_bayar = 'Bank BCA (VA)';
        }else if ($result['biller_code']=='70012') {
            $m_bayar = 'Bank Mandiri (VA)';
        }else if ($result['biller_code']=='bri') {
            $m_bayar = 'Bank BRI (VA)';
        }else if ($result['biller_code']=='bni') {
            $m_bayar = 'Bank BNI (VA)';
        }else if ($result['biller_code']=='permata') {
            $m_bayar = 'Bank Permata (VA)';
        }else{
            if ($result['payment_type']=='gopay') {
                $m_bayar = 'Gopay';
            }else if ($result['payment_type']=='qris') {
                $m_bayar = 'QRIS';
            }else{
                $m_bayar = 'Layanan Transfer Bank';
            }
        }

        $data = array(
            'uid'                       => $result['unique_id'],
            'kode'                      => $result['kode_topup'],
            'nominal'                   => formatRupiah($result['nominal_topup']),
            'is_status'                 => $result['is_status'],
            'batas_waktu_pembayaran'    => $result['batas_waktu_pembayaran'],
            'bukti_pembayaran'          => $result['bukti_pembayaran'],
            'payment_type'              => $result['payment_type'],
            'biller_code'               => $result['biller_code'],
            'bill_key'                  => $result['bill_key'],
            'cara_pembayaran'           => $result['cara_pembayaran'],
            'cara_bayar'                => $cara_bayar,
            'm_bayar'                   => $m_bayar,
            'if_cancel'                 => $result['if_cancel'],
            'tanggal'                   => indo($result['created_at'])
        );

        $m_bank = $this->db->query("SELECT * FROM m_bank WHERE is_active='1' AND is_hapus='n'")->result_array();
        
        return json_encode(array('success'=>true, 'result'=>$data, 'm_bank'=>$m_bank));

    }

    public function load_riwayat_saldo($postjson){

        $data = array();

        $query = $this->db->query("SELECT a.*,b.unique_id FROM tx_saldo a LEFT JOIN tx_topup b ON a.kode_saldo=b.kode_topup WHERE a.cust_id='$postjson[idcust]' ORDER BY a.saldo_id DESC")->result_array();
   
        foreach ($query as $rows) {

    
            if ($rows['tipe']=='topup') {
                $st = 'Topup';
                $trxid = '';
            }else if ($rows['tipe']=='trx') {
                $trx = $this->db->query("SELECT unique_id FROM tx_transaksi WHERE cust_id='$rows[cust_id]' AND no_transaksi='$rows[kode_saldo]'")->row_array();
                $st = 'Transaksi';
                $trxid = $trx['unique_id'];
            }else{
                $st = 'Unknown';
                $trxid = '';
            }

            if ($rows['status_saldo']==1) {
                $nominal = "+ ".formatRupiah($rows['masuk']);
                $clr_ft = 'success';
            }else if ($rows['status_saldo']==2) {
                $nominal = "- ".formatRupiah($rows['keluar']);
                $clr_ft = 'danger';
            }else {
                $nominal = 0;
            }

            $data[] = array(
                'uid'           => $trxid,
                'uidtopup'      => $rows['unique_id'],
                'kode'          => $rows['kode_saldo'],
                'nominal'       => $nominal,
                'status_saldo'  => $rows['status_saldo'],
                'tipe'          => $rows['tipe'],
                'tipelbl'       => $st,
                'clr_ft'        => $clr_ft,
                'tanggal'       => indo($rows['created_at'])
            );
        
        }

        return json_encode(array('success'=>true, 'result'=>$data));

    }

}
