<?php

function is_logged_in() {
    $CI = get_instance();
    if (!$CI->session->userdata('p_id')) {
        redirect('auth');
    }
}

function cek_menu_access() {
    $CI = get_instance();
    if ($CI->uri->segment(2)!='') {
        $seg2 = '/'.$CI->uri->segment(2).'/';
    }else{
        $seg2 = '';
    }

    $urltujuan = $CI->uri->segment(1).$seg2;
    $query = $CI->db->query("SELECT * FROM m_role_access a JOIN m_menu b ON a.id_menu=b.menu_id WHERE b.link_url='$urltujuan' AND a.id_role=".$CI->session->userdata('role_id'))->num_rows();

    if ($query<1) {
        redirect('master');
    }

}

function pengaturanSistem(){
    $CI = get_instance();
    $query = $CI->db->query("SELECT * FROM _setting WHERE setting_id='1'")->row_array();
    return ($query);
}

function cekDatarowarray($table,$field,$where,$fieldpasang = "*",$orderby = null,$fields = null,$wheres = null){
    $CI = get_instance();

    if ($orderby==null || $orderby=='') {
        $orderby = '';
    }else{
        $orderby = $orderby;
    }

    if ($fields==null || $fields=='') {
        $and = "";
    }else{
        $and = " AND ".$fields."='".$wheres."'";
    }

    $query = $CI->db->query("SELECT $fieldpasang FROM $table WHERE $field='$where' $and $orderby ")->row_array();
    return ($query);
}

function Osignal_single($ket_notif,$id,$heading_notif,$idcust = 0,$tipe,$paramsync = 0){
    $content = array("en" => $ket_notif);
    $fields = array(
      'app_id' => "4cae4dba-4aab-4a0b-849a-ea508d541913",
      'include_player_ids' => array($id),
      'data' => array("tipenotifpush" => $tipe, "namatitle" => $heading_notif, "idcust" => $idcust, "paramid" => $paramsync),
      'headings' => array("en" => $heading_notif),
      'contents' => $content
    );
                            
    $fields = json_encode($fields);    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
}

function Osignal_multi($ket_notif,$id,$heading_notif,$idcust = 0,$tipe,$paramsync = 0){
    $content = array("en" => $ket_notif);
    $fields = array(
      'app_id' => "4cae4dba-4aab-4a0b-849a-ea508d541913",
      'include_player_ids' => $id,
      'data' => array("tipenotifpush" => $tipe, "namatitle" => $heading_notif, "idcust" => $idcust, "paramid" => $paramsync),
      'headings' => array("en" => $heading_notif),
      'contents' => $content
    );
                            
    $fields = json_encode($fields);    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
}

function emailTestSmtp($emailto,$mail){
    $mail->isSMTP();
    $mail->Host = "mail.carvellonic.com"; //host masing2 provider email
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@carvellonic.com'; // email domain
    $mail->Password = 'noreplycarvellonic!'; // pass email domain
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );

    $mail->setFrom('noreply@carvellonic.com', 'Carvellonic');

    $mail->addAddress($emailto);
    $mail->Subject = 'Test Email SMTP';
    $mail->isHTML(true);

    $mailContent = 'Testing email smtp success...';
    $mail->Body = $mailContent;

    if(!$mail->send()){
        return 'Send OTP Error: ' . $mail->ErrorInfo;
    }else{
        return 'y';
    }
}

function emailOtpSmtp($emailto,$code,$mail){

    $mail->isSMTP();
    $mail->Host = "mail.carvellonic.com"; //host masing2 provider email
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@carvellonic.com'; // email domain
    $mail->Password = 'noreplycarvellonic!'; // pass email domain
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );

    $mail->setFrom('noreply@carvellonic.com', 'Carvellonic');

    $mail->addAddress($emailto);
    // menambahkan beberapa penerima dengan email yg berbeda
    // $mail->addAddress('penerima2@contoh.com');
    // $mail->addAddress('penerima3@contoh.com');
    // Menambahkan cc atau bcc 
    // $mail->addCC('cc@contoh.com');
    // $mail->addBCC('bcc@contoh.com');
    $mail->Subject = 'Kode Verifikasi OTP Login';
    // mengatur format email ke HTML
    $mail->isHTML(true);

    $mailContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Login activation code</title>
            <style type="text/css">
            body {margin: 0; padding: 0; min-width: 100%!important;}
            img {height: auto;}
            .content { width: 100%; max-width: 600px; }
            @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .hide {display: none!important;}
            body[yahoo] .buttonwrapper {background-color: transparent!important;}
            }
            /*@media only screen and (min-device-width: 601px) {
              .content {width: 600px !important;}
              .col425 {width: 425px!important;}
              .col380 {width: 380px!important;}
              }*/

            </style>
        </head>

        <body yahoo bgcolor="#f6f8f1">
          <table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td>
              <![endif]-->
              <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td bgcolor="#fff" style="border-bottom: 2px solid #37b464; padding: 10px 30px 10px 30px;">
                    <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="70">
                          <!-- <img src="https://admin.novelhub.id/assets/temp/img/logo/logo_novelhub_fit-02.png" width="170" height="170" border="0" alt="" /> -->
                          <span style="font-size: 24px; font-weight: bold; font-family: sans-serif;">Carvellonic</span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="color: #153643; font-family: sans-serif;padding: 0 0 15px 0; font-size: 18px; line-height: 28px; font-weight: bold;">
                          Hi '.$emailto.'.
                        </td>
                      </tr>
                      <tr>
                        <td style="color: #153643; font-family: sans-serif; ">
                          Kode Aktivasi Login Store.
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 20px 0 0 0;">
                          <table class="buttonwrapper" bgcolor="#37b464" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td height="45" style="text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;">
                                <a style="color: #ffffff; text-decoration: none;">'.$code.'</a>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 13px; padding: 30px 30px 30px 30px;color: #153643; font-family: sans-serif;">
                    Tolong jangan balas email ini. Karena email ini dibuat secara otomatis. Jika Anda memiliki keluhan atau masalah, silakan laporkan kepada kami dengan membuka halaman kontak kami.
                  </td>
                </tr>
                <tr>
                  <td bgcolor="#44525f" style="padding: 20px 30px 15px 30px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">
                          Copyright &copy; 2022 Carvellonic All Right Reserved
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <!--[if (gte mso 9)|(IE)]>
                    </td>
                  </tr>
              </table>
              <![endif]-->
              </td>
            </tr>
        </table>
      </body>
    </html>';
    $mail->Body = $mailContent;

    // Menambahakn lampiran
    // $mail->addAttachment('attachment/namafile.pdf'); // pdf doc dll,
    // $mail->addAttachment('attachment/namafile.png', 'nama-baru-file2.png'); //atur nama baru

    if(!$mail->send()){
        return 'Send OTP Error: ' . $mail->ErrorInfo;
    }else{
        return 'y';
    }
}

function kirimTransaksikeEmail($idtrx,$idcust,$mail = null){
    $CI = get_instance();

    $res = $CI->db->query("SELECT a.*,b.kurir,b.nama_kurir,b.level_kurir,b.lama_pengiriman FROM tx_transaksi a JOIN tx_kurir b ON a.no_transaksi=b.no_transaksi WHERE a.cust_id='$idcust' AND a.unique_id='$idtrx' ORDER BY a.transaksi_id DESC ")->result_array();
    
    foreach ($res as $rows) {

        $t_byr = $rows['harga_total']+$rows['ongkos_kirim']-$rows['potongan_total']-$rows['diskon_all_total'];
        $total_bayar = formatRupiah($t_byr);
        $subtotal_bayar = formatRupiahnorp($t_byr-$rows['ongkos_kirim']);
        $ongkos_kirim = formatRupiahnorp($rows['ongkos_kirim']);

        if ($rows['biller_code']=='bca') {
            $m_bayar = 'Bank BCA (VA) : '.$rows['bill_key'];
        }else if ($rows['biller_code']=='70012') {
            $m_bayar = 'Bank Mandiri (VA) : '.$rows['bill_key'];
        }else if ($rows['biller_code']=='bri') {
            $m_bayar = 'Bank BRI (VA) : '.$rows['bill_key'];
        }else if ($rows['biller_code']=='bni') {
            $m_bayar = 'Bank BNI (VA) : '.$rows['bill_key'];
        }else if ($rows['biller_code']=='permata') {
            $m_bayar = 'Bank Permata (VA) : '.$rows['bill_key'];
        }else{
            if ($rows['payment_type']=='gopay') {
                $m_bayar = 'Gopay';
            }else if ($rows['payment_type']=='qris') {
                $m_bayar = 'QRIS';
            }else{
                $m_bayar = 'Layanan Transfer Bank';
            }
        }

        $nama_kurir = $rows['nama_kurir'];
        $tgl_transaksi = indo($rows['tgl_transaksi']);
    }


    $m_cart = array();
    $q_cart = $CI->db->query("SELECT b.*,c.nama_warna,d.ukuran_size FROM tx_transaksi a JOIN tx_transaksi_det b ON a.no_transaksi=b.no_transaksi LEFT JOIN m_warna c ON b.warna_id=c.warna_id LEFT JOIN m_ukuran d ON b.ukuran_id=d.ukuran_id WHERE a.cust_id='$idcust' AND a.unique_id='$idtrx' ")->result_array();
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

    $m_alamat = array();
    $m_alamat = $CI->db->query("SELECT b.*, c.* FROM tx_transaksi a JOIN m_customer_det b ON a.cust_det_id=b.cust_det_id JOIN m_customer c ON a.cust_id=c.cust_id WHERE a.cust_id='$idcust' AND a.unique_id='$idtrx' ")->row_array();

    $mail->isSMTP();
    $mail->Host = "mail.carvellonic.com"; //host masing2 provider email
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@carvellonic.com'; // email domain
    $mail->Password = 'noreplycarvellonic!'; // pass email domain
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('noreply@carvellonic.com', 'Carvellonic');

    $mail->addAddress($m_alamat['is_token']);
    // menambahkan beberapa penerima dengan email yg berbeda
    // $mail->addAddress('penerima2@contoh.com');
    // $mail->addAddress('penerima3@contoh.com');
    // Menambahkan cc atau bcc 
    // $mail->addCC('cc@contoh.com');
    // $mail->addBCC('bcc@contoh.com');
    $mail->Subject = 'Pembelian di cStore';
    // mengatur format email ke HTML
    $mail->isHTML(true);

    $mailContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Pembelian di cStore</title>
            <style type="text/css">
            body {margin: 0; padding: 0; min-width: 100%!important;}
            img {height: auto;}
            .content { width: 100%; max-width: 600px; }
            @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .hide {display: none!important;}
            body[yahoo] .buttonwrapper {background-color: transparent!important;}
            }
            /*@media only screen and (min-device-width: 601px) {
              .content {width: 600px !important;}
              .col425 {width: 425px!important;}
              .col380 {width: 380px!important;}
              }*/

            </style>
        </head>

        <body yahoo bgcolor="#f6f8f1">
          <table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td>
              <![endif]-->
              <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td bgcolor="#fff" style="border-bottom: 2px solid #37b464; padding: 10px 30px 10px 30px;">
                    <table align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="70">
                          <span style="font-size: 24px; font-weight: bold; font-family: sans-serif;">cStore by Carvellonic</span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="color: #153643; font-family: sans-serif;padding: 0 0 15px 0; font-size: 18px; line-height: 28px; font-weight: bold;">
                          Hi '.$m_alamat['cust_nama'].'.
                        </td>
                      </tr>
                      <tr>
                        <td style="color: #153643; font-family: sans-serif; ">
                          Berikut transaksi kamu pada tanggal '.$tgl_transaksi.'.
                        </td>
                      </tr>
                      <tr>
                        <td style="padding: 20px 0 0 0;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <thead class="b-0">
                              <tr class="b-0">
                                <th style="color: #153643; font-family: sans-serif; text-align: left;">Produk</th>
                                <th style="color: #153643; font-family: sans-serif; text-align: right">Harga</th>
                                <th style="color: #153643; font-family: sans-serif; text-align: right">Jumlah</th>
                                <th style="color: #153643; font-family: sans-serif; text-align: right">Subharga</th>
                              </tr>
                            </thead>
                            <tbody>';
                            foreach($m_cart as $obj) {
                            $mailContent .= '
                              <tr>
                                <td style="color: #153643; font-family: sans-serif; ">
                                  '.$obj['nama_produk'].' '.$obj['varian'].'
                                </td>
                                <td align="right" style="color: #153643; font-family: sans-serif; text-align: right;">
                                  '.$obj['harga_produk'].'
                                </td>
                                <td align="right" style="color: #153643; font-family: sans-serif;text-align: right; ">'.$obj['jumlah_beli'].'</td>
                                <td align="right" style="color: #153643; font-family: sans-serif;text-align: right; ">
                                  '.$obj['total_harga_produk'].'
                                </td>
                              </tr>';
                            }
                            $mailContent .= '
                            </tbody>
                            <tfoot>
                              <tr>
                                <td style="color: #153643; font-family: sans-serif;" colspan="1">Subtotal</td>
                                <td style="color: #153643; font-family: sans-serif;text-align: right;" colspan="3" align="right">'.$subtotal_bayar.'</td>
                              </tr>
                              <tr>
                                <td style="color: #153643; font-family: sans-serif;" colspan="1">Ongkos Kirim</td>
                                <td style="color: #153643; font-family: sans-serif;text-align: right;" colspan="3" align="right">'.$ongkos_kirim.'</td>
                              </tr>
                              <tr>
                                <td style="color: #153643; font-family: sans-serif;" colspan="1">Total Harga</td>
                                <td style="color: #153643; font-family: sans-serif;text-align: right; font-weight: 600;" colspan="3" align="right">
                                  '.$total_bayar.'
                                </td>
                              </tr>
                            </tfoot>
                          </table>

                          <div class="" style="border-top: 1px solid #e4e4e4; padding-top: 15px; margin-top: 15px; color: #153643; font-family: sans-serif;">
                            <div class="ft-14 mb-3">
                              <div class="ft-14 font-weight-bold mb-1" style="font-weight: 600;">
                                Metode Pembayaran
                              </div>
                              '.$m_bayar.'
                            </div>
                            <div class="ft-14 mb-3">
                              <div class="ft-14 font-weight-bold mb-1" style="font-weight: 600;">
                                Metode Pengiriman
                              </div>
                              Kurir - '.$nama_kurir.'
                            </div>
                            <div class="ft-14">
                              <div class="ft-14 font-weight-bold mb-1" style="font-weight: 600;">
                                Alamat Pengiriman
                              </div>
                              '.$m_alamat['nama_penerima'].'
                              <br>'.$m_alamat['nama_provinsi'].','.$m_alamat['nama_kabkot'].', '.$m_alamat['kodepos'].'
                              <br>'.$m_alamat['alamat_lengkap'].'
                              <br>
                              Nomor yang dapat di hubungi '.$m_alamat['ponsel_penerima'].'
                            </div>
                          </div>

                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 13px; padding: 30px 30px 30px 30px;color: #153643; font-family: sans-serif;">
                    Tolong jangan balas email ini. Karena email ini dibuat secara otomatis. Jika Anda memiliki keluhan atau masalah, silakan laporkan kepada kami dengan membuka halaman kontak kami.
                  </td>
                </tr>
                <tr>
                  <td bgcolor="#44525f" style="padding: 20px 30px 15px 30px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">
                          Copyright &copy; 2022 Carvellonic All Right Reserved
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <!--[if (gte mso 9)|(IE)]>
                    </td>
                  </tr>
              </table>
              <![endif]-->
              </td>
            </tr>
        </table>
      </body>
    </html>';
    $mail->Body = $mailContent;

    // Menambahakn lampiran
    // $mail->addAttachment('attachment/namafile.pdf'); // pdf doc dll,
    // $mail->addAttachment('attachment/namafile.png', 'nama-baru-file2.png'); //atur nama baru

    if(!$mail->send()){
        return 'Send OTP Error: ' . $mail->ErrorInfo;
    }else{
        return 'y';
    }
}

function hitungRating($id) {
    $CI = get_instance();
    $resultSet = $CI->db->query("SELECT SUM(rating_produk) as rating, COUNT(rating_produk) as count FROM tx_transaksi_det WHERE produk_id='$id' AND publikasi_rating='y'")->row_array();
    if($resultSet['count']>0) {
      return ($resultSet['rating']/$resultSet['count']);
    } else {
      return 0;
    }
    
}

function indo($tgl = null){
    if ($tgl!=null) {
        $date = substr($tgl,0,10);
        $BulanIndo = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $pecahkan = explode('-', $date);
        $tgl = isset($pecahkan[2]) ? $pecahkan[2] : '';
        $bln = isset($pecahkan[1]) ? $pecahkan[1] : '';
        $thn = isset($pecahkan[0]) ? $pecahkan[0] : '';
        return $tgl . ' ' . $BulanIndo[ (int)$bln-1] . ' ' . $thn;
    }else{
        return '';
    }
}

function indolengkap($tgl = null){
    if ($tgl!=null) {
        $date = substr($tgl,0,10);
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $pecahkan = explode('-', $date);
        $tgl = isset($pecahkan[2]) ? $pecahkan[2] : '';
        $bln = isset($pecahkan[1]) ? $pecahkan[1] : '';
        $thn = isset($pecahkan[0]) ? $pecahkan[0] : '';
        return $tgl . ' ' . $BulanIndo[ (int)$bln-1] . ' ' . $thn;
    }else{
        return '';
    }
}

function urutId($table,$field){
    $CI = get_instance();
    $query = $CI->db->query("SELECT max($field) as id FROM $table")->row_array();
    $hasilid = $query['id']+1;
    return ($hasilid);
}

function urutIdwhere($table,$field,$field2,$where){
    $CI = get_instance();
    $query = $CI->db->query("SELECT max($field) as id FROM $table WHERE $field2='$where'")->row_array();
    $hasilid = $query['id']+1;
    return ($hasilid);
}

function formatRupiah($jumlah){
    $conv = "Rp ".number_format($jumlah,0,',','.');
    return($conv);
}

function formatRupiahnorp($jumlah,$kutip = 0){
    $conv = number_format($jumlah,$kutip,',','.');
    return($conv);
}

function randCode($panjang){
    $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter[$pos];
    }
    
    return $string;
}

function randNumb($panjang){
    $karakter= '123456789';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter[$pos];
    }
    
    return $string;
}

function str_replace_html($txt){
    $find = array("<?php","?>","<?","<?=","<script>","<script","</script>","<a>","<a","</a>","<button>","<button","</button>","<ul>","<ul","</ul>","<li>","<li","</li>","<ol>","<ol","</ol>");
    $replace = "-";
    return str_replace($find,$replace,$txt);
}

function str_replace_kutip($txt){
    $find = array('"','`');
    $replace = "'";
    return str_replace($find,$replace,$txt);
}

function hitungBulan($tgl1,$tgl2){ 
    //convert
    $timeStart = strtotime($tgl1);
    $timeEnd = strtotime($tgl2);
    // Menambah bulan ini + semua bulan pada tahun sebelumnya
    $numBulan = 1 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
    // hitung selisih bulan
    $numBulan += date("m",$timeEnd)-date("m",$timeStart);
    return $numBulan;
}

function convertedTime($tambah,$date = null){ 
    if ($date==null) {
        $startTime = date("Y-m-d H:i:s");
    }else{
        $startTime = $date;
    }
    //add time
    $cenvertedTime = date('Y-m-d H:i:s',strtotime($tambah,strtotime($startTime)));
    //display the converted time
    return $cenvertedTime;
}

// function number_format_short($n) {
//     if ($n > 0 && $n < 1000) {
//         // 1 - 999
//         $n_format = floor($n);
//         $suffix = '';
//     } else if ($n >= 1000 && $n < 1000000) {
//         // 1k-999k
//         $n_format = floor($n / 1000);
//         $suffix = 'K+';
//     } else if ($n >= 1000000 && $n < 1000000000) {
//         // 1m-999m
//         $n_format = floor($n / 1000000);
//         $suffix = 'M+';
//     } else if ($n >= 1000000000 && $n < 1000000000000) {
//         // 1b-999b
//         $n_format = floor($n / 1000000000);
//         $suffix = 'B+';
//     } else if ($n >= 1000000000000) {
//         // 1t+
//         $n_format = floor($n / 1000000000000);
//         $suffix = 'T+';
//     }

//     return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
// }

function number_format_short_2($num, $precision = 2) {
    if ($num >= 1000 && $num < 1000000) {
       $n_format = number_format($num/1000, $precision).'K';
    } else if ($num >= 1000000 && $num < 1000000000) {
       $n_format = number_format($num/1000000, $precision).'M';
    } else if ($num >= 1000000000) {
       $n_format = number_format($num/1000000000, $precision).'B';
    } else {
       $n_format = $num;
    }
       return $n_format;
}

function time_ago($time_ago) {
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

function url_replace($string) {
    $c = array (' ');
    $d = array ('/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','*','?','&','=','+','°');
    $string = str_replace($d, '', $string); // Hilangkan karakter yang telah disebutkan di array $d
    $string = strtolower(str_replace($c, '-', $string)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $string;
}

// ini blm kepake

function check_access($role_id, $menu_id) {
    $CI = get_instance();
    $CI->db->where('role_id', $role_id);
    $CI->db->where('menu_id', $menu_id);
    $result = $CI->db->get('m_role_access');
    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function kon_waktu($waktu){
    if(($waktu>0) and ($waktu<60)){
        $lama=number_format($waktu,2)." detik";
        return $lama;
    }
    if(($waktu>60) and ($waktu<3600)){
        $detik=fmod($waktu,60);
        $menit=$waktu-$detik;
        $menit=$menit/60;
        $lama=$menit." Menit ".number_format($detik,2)." detik";
        return $lama;
    }
    elseif($waktu >3600){
        $detik=fmod($waktu,60);
        $tempmenit=($waktu-$detik)/60;
        $menit=fmod($tempmenit,60);
        $jam=($tempmenit-$menit)/60;    
        $lama=$jam." Jam ".$menit." Menit ".number_format($detik,2)." detik";
        return $lama;
    }
}

function kon_detik_to_menit($waktu){
    $result = $waktu/60;
    return $result;
}

class GoogleLoginApi {
  public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {  
    $url = 'https://www.googleapis.com/oauth2/v4/token';      
    $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
    curl_setopt($ch, CURLOPT_POST, 1);    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);  
    $data = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);    
    if($http_code != 200) 
      throw new Exception('Error : Failed to receieve access token');
        
    return $data;
  }

  public function GetUserProfileInfo($access_token) { 
    $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';      
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
    $data = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   
    if($http_code != 200) 
      throw new Exception('Error : Failed to get user information');
        
    return $data;
  }
}

function resizeImagev2($resourceType,$image_width,$image_height,$resizeWidth,$resizeHeight) {
    // $resizeWidth = 100;
    // $resizeHeight = 100;
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
}

function resizeImgv2($source_image, $dir){
    list( $width, $height ) = getimagesize($source_image);

    $width_size = $width*50/100; // compress 50%
    $k = $width / $width_size;
    $new_width = $width / $k;
    $new_height = $height / $k;

    $fileName = $source_image;
    $sourceProperties = getimagesize($fileName);
    $uploadPath = $dir;
    $uploadImageType = $sourceProperties[2];
    $sourceImageWidth = $sourceProperties[0];
    $sourceImageHeight = $sourceProperties[1];
    switch ($uploadImageType) {
        case IMAGETYPE_JPEG:
            $resourceType = imagecreatefromjpeg($fileName);
            $imageLayer = resizeImagev2($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
            imagejpeg($imageLayer,$uploadPath);
            break;

        case IMAGETYPE_GIF:
            $resourceType = imagecreatefromgif($fileName);
            $imageLayer = resizeImagev2($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
            imagegif($imageLayer,$uploadPath);
            break;

        case IMAGETYPE_PNG:
            $resourceType = imagecreatefrompng($fileName);
            $imageLayer = resizeImagev2($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
            imagepng($imageLayer,$uploadPath);
            break;

        default:
            break;
    }
}

?>