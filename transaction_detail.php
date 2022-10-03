<?php include "module/module.php"; ?>
<?php if (!isset($_SESSION['XID_ARRAY'])) { header("Location: ".$main_url); exit(); }
  $arr = array('tipe' => 'web', 'idtrx' => $_GET['p_url'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
  $i_trx = loadData('rest_load/load_riwayat_transaksi/', $arr); $rest_trx = $i_trx['result'][0];
?>
<!doctype html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?=$rest_sistem['result']['meta_description'];?>">
    <meta name="keywords" content="<?=$rest_sistem['result']['meta_keywords'];?>">
    
    <?php include "module/include/style.php"; ?>

    <title>Detail Transaksi</title>
  </head>
  <body>

    <?php include "module/include/header.php"; ?>

    <section class="bg-container-2 mt-4 pt-3-mob">
      <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-11">
          <div class="row">

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
              <div class="default-shadow rounded-2">
                <div class="padding-15 text-center">

                  <?php if ($i_trx['result']) { ?>

                    <input type="hidden" id="no_transaksiid" value="<?=$rest_trx['no_transaksi'];?>" class="form-control">

                    <?php if ($rest_trx['bukti_pembayaran']=='n' && $rest_trx['is_status']!='b') { ?>
                      <h3 class="mb-3 ft-18 font-weight-bold mt-3">TRANSAKSI BERHASIL <i class="fa fa-check"></i></h3>
                      <p class="color-semidark">
                        No Transaksi : <b class="font-weight-bold"><?=$rest_trx['no_transaksi'];?></b>
                        <br/>
                        Status : <b class="color-<?=$rest_trx['status_clr'];?>"><?=$rest_trx['status_lbl'];?></b>
                        <br/>
                        Tanggal : <b><?=$rest_trx['tgl_transaksi'];?></b>
                      </p>
                      <p class="color-semidark">
                        Total Pembayaran : <b class="font-weight-bold"><?=$rest_trx['total_bayar'];?></b>
                      </p>

                      <?php if ($rest_trx['is_status']=='p') { ?>
                        <p class="color-semidark ft-14">
                          Silahkan lakukan pembayaran agar transaksi kamu bisa langsung kami proses.
                        </p>
                      <?php } ?>

                      <?php if ($rest_trx['is_status']=='y' || $rest_trx['is_status']=='k' || $rest_trx['is_status']=='s') { ?>
                      <p class="color-semidark">Terima Kasih telah menggunakan layanan kami situs penjualan online terpercaya.</p>
                      <p class="color-success font-weight-bold">Pembayaran Selesai. <i class="fa fa-check"></i></p>
                      <?php } ?>

                    <?php }elseif($rest_trx['is_status']=='p' || $rest_trx['is_status']=='y' || $rest_trx['is_status']=='k' || $rest_trx['is_status']=='s') { ?>
                      <h3 class="mb-3 ft-18 font-weight-bold mt-2">TRANSAKSI BERHASIL <i class="fa fa-check"></i></h3>
                      <p class="color-semidark">
                        No Transaksi : <b class="font-weight-bold"><?=$rest_trx['no_transaksi'];?></b>
                        <br/>
                        Status : <b class="color-<?=$rest_trx['status_clr'];?>"><?=$rest_trx['status_lbl'];?></b>
                        <br/>
                        Tanggal : <b><?=$rest_trx['tgl_transaksi'];?></b>
                      </p>

                      <?php if ($rest_trx['is_status']!='s' && $rest_trx['is_status']!='k') { ?>
                        <?php if ($rest_trx['metode_pembayaran']=='saldo') { ?>
                        <p class="color-semidark">
                          <span class="font-weight-bold">Pembayaran dengan Saldo.</span>
                        </p>
                        <?php }else{ ?>
                        <p class="color-semidark">Bukti Transfer telah kami terima. Terima Kasih telah menggunakan layanan kami situs penjualan online terpercaya.
                          <br>
                          <span class="font-weight-bold">Pesanan kamu akan kami proses lebih lanjut.</span>
                        </p>
                        <?php } ?>
                      <?php } ?>

                      <?php if ($rest_trx['is_status']=='k' || $rest_trx['is_status']=='s') { ?>
                      <p class="color-semidark">Terima Kasih telah menggunakan layanan kami situs penjualan online terpercaya.</p>
                      <?php } ?>
      
                      <p class="color-semidark">
                        Total Pembayaran : <b class="font-weight-bold"><?=$rest_trx['total_bayar'];?></b>
                      </p>
                    
                      <?php if ($rest_trx['is_status']=='p') { ?>
                        <p class="color-app font-weight-bold">
                          Pembayaran kamu sedang kami cek. <i class="fa fa-hourglass-half"></i>
                        </p>
                        <div class="alert alert-primary">
                          Bukti pembayaran telah terkirim, proses pengecekan membutuhkan waktu hingga 1x24 jam.
                        </div>
                      <?php } else if ($rest_trx['is_status']=='y' || $rest_trx['is_status']=='k' || $rest_trx['is_status']=='s'){ ?>
                        <p class="color-success font-weight-bold">Pembayaran Selesai. <i class="fa fa-check"></i></p>
                      <?php } ?>

                      <?php if($rest_trx['is_status']=='y' || $rest_trx['is_status']=='k') { ?>
                        <p class="font-weight-bold color-semidark">Pesanan kamu akan segera tiba.</p>
                      <?php } ?>

                    <?php }else{ ?>
                      <h3 class="mb-3 ft-18 font-weight-bold mt-2">TRANSAKSI DIBATALKAN <i class="fa fa-times"></i></h3>
                      <p class="color-semidark">
                        No Transaksi : <b class="font-weight-bold"><?=$rest_trx['no_transaksi'];?></b>
                        <br/>
                        Status : <b class="color-<?=$rest_trx['status_clr'];?>"><?=$rest_trx['status_lbl'];?></b>
                        <br/>
                        Tanggal : <b><?=$rest_trx['tgl_transaksi'];?></b>
                      </p>

                      <p class="color-semidark"><?=$rest_trx['if_cancel'];?></p>

                      <p class="color-semidark">
                        Total Yang Harus Dibayarkan : <b class="font-weight-bold"><?=$rest_trx['total_bayar'];?></b>
                      </p>
                    <?php } ?>


                    <?php if ($rest_trx['is_status']=='p') { ?>
                      <p class="color-semidark">
                        <span class="font-weight-bold">Cara Pembayaran</span>
                        <br>
                        <span class="ft-14">Lakukan pembayaran melalui salah satu cara / bank dibawah ini :</span>
                      </p>
                      
                      <?php if ($rest_sistem['result']['metode_pembayaran']=='midtrans') { ?>
                        <?php if ($rest_trx['payment_type']!='manual') { ?>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12">
                            <ul class="nav nav-tabs b-0_ mb-4 justify-content-center" id="myTab" role="tablist">
                              <?php $no='1'; foreach($rest_trx['cara_bayar'] as $valc){ ?>
                              <li class="nav-item">
                                <a class="nav-link ft-16 <?php if($no=='1') echo 'active';?>" id="cbyr-tab<?=$valc['cara_bayar_id'];?>" data-toggle="tab" href="#tabcbyr<?=$valc['cara_bayar_id'];?>" role="tab" aria-controls="tabcbyr<?=$valc['cara_bayar_id'];?>" aria-selected="true"><?=$valc['jenis_bayar'];?></a>
                              </li>
                              <?php $no++; } ?>
                            </ul>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 text-left">
                            <div class="tab-content" id="myTabContent">
                              <?php $no = 0; foreach($rest_trx['cara_bayar'] as $valc){ ?>
                              <div class="tab-pane fade <?php if($no==0) echo 'show active';?>" id="tabcbyr<?=$valc['cara_bayar_id'];?>" role="tabpanel" aria-labelledby="cbyr-tab<?=$valc['cara_bayar_id'];?>">
                                <div class="padding-0-15 ft-14">
                                  <div class="mb-3 ft-16"><b>Kode Pembayaran / VA : <?=$rest_trx['bill_key'];?></b></div>
                                  <?php $nox = 1; foreach($rest_trx['cara_bayar'][$no]['cara_bayar'] as $valcc){ ?>
                                  <?=$nox.'. '.$valcc['cara_bayar'];?><br/>
                                  <?php $nox++; } ?>
                                </div>
                              </div>
                              <?php $no++; } ?>
                            </div>
                          </div>
                        </div>
                        <?php }else{ ?>
                        <!-- Jika menggunakan manual transfer -->
                        <div class="manual_bank_pay ft-14">
                          <?php foreach($i_trx['m_bank'] as $valb){ ?>
                            <img src="<?=$main_imgurl.'komponen/'.$valb['logo_image'];?>" width="80"> &nbsp;
                            Bank <?=$valb['nama_bank'];?> a/n <?=$valb['nama_rekening'];?><br>
                            Nomor Rekening : <?=$valb['nomor_rekening'];?><br><br>
                          <?php } ?>
                        </div>
                        <?php } ?>
                      <?php }else{ ?>
                        <?php if ($rest_trx['payment_type']!='manual') { ?>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12">
                            <ul class="nav nav-tabs b-0_ mb-4 justify-content-center" id="myTab" role="tablist">
                              <?php $no='1'; foreach($rest_trx['cara_bayar'] as $valc){ ?>
                              <li class="nav-item">
                                <a class="nav-link ft-16 <?php if($no=='1') echo 'active';?>" id="cbyr-tab<?=$valc['cara_bayar_id'];?>" data-toggle="tab" href="#tabcbyr<?=$valc['cara_bayar_id'];?>" role="tab" aria-controls="tabcbyr<?=$valc['cara_bayar_id'];?>" aria-selected="true"><?=$valc['jenis_bayar'];?></a>
                              </li>
                              <?php $no++; } ?>
                            </ul>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 text-left">
                            <div class="tab-content" id="myTabContent">
                              <?php $no = 0; foreach($rest_trx['cara_bayar'] as $valc){ ?>
                              <div class="tab-pane fade <?php if($no==0) echo 'show active';?>" id="tabcbyr<?=$valc['cara_bayar_id'];?>" role="tabpanel" aria-labelledby="cbyr-tab<?=$valc['cara_bayar_id'];?>">
                                <div class="padding-0-15 ft-14">
                                  <div class="mb-3 ft-16"><b>Kode Pembayaran / VA : <?=$rest_trx['bill_key'];?></b></div>
                                  <?php $nox = 1; foreach($rest_trx['cara_bayar'][$no]['cara_bayar'] as $valcc){ ?>
                                  <?=$nox.'. '.$valcc['cara_bayar'];?><br/>
                                  <?php $nox++; } ?>
                                </div>
                              </div>
                              <?php $no++; } ?>
                            </div>
                          </div>
                        </div>
                        <?php }else{ ?>
                        <!-- Jika menggunakan manual transfer -->
                        <div class="manual_bank_pay ft-14">
                          <?php foreach($i_trx['m_bank'] as $valb){ ?>
                            <img src="<?=$main_imgurl.'komponen/'.$valb['logo_image'];?>" width="80"> &nbsp;
                            Bank <?=$valb['nama_bank'];?> a/n <?=$valb['nama_rekening'];?><br>
                            Nomor Rekening : <?=$valb['nomor_rekening'];?><br><br>
                          <?php } ?>
                        </div>
                        <?php } ?>
                      <?php } ?>

                      <div class="pt-3"><hr></div>

                      <form id="form_kirim_bukti_bayar" class="mt-3" action="javascript:sendPayment()" enctype="multipart/form-data" method="POST">
                        <div class="row">
                          
                          <?php if ($rest_trx['payment_type']!='manual') { ?>
                          <input type="hidden" name="bank_id" value="0" class="form-control">
                          <?php }else{ ?>
                          <!-- Jika menggunakan manual transfer -->
                          <div class="col-sm-12 col-md-12">
                            <div class="form-label-group text-left">
                              <label class="text-left">Transfer Ke</label>
                              <select class="form-control" required="" name="bank_id">
                                <option value=""> -- Pilih Bank -- </option>
                                <?php foreach($i_trx['m_bank'] as $valb){ ?>
                                <option value="<?=$valb['bank_id'];?>"><?=$valb['nama_bank'];?> - <?=$valb['nama_rekening'];?> - <?=$valb['nomor_rekening'];?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <?php } ?>

                          <div class="col-sm-12 col-md-12">
                            <div class="form-label-group text-left mt-2">
                              <label style="text-align: left;">Bukti Pembayaran</label>
                              <input type="file" name="gambar" class="form-control" required="">
                              <input type="hidden" name="no_transaksi" value="<?=$rest_trx['no_transaksi'];?>" class="form-control">
                            </div>
                          </div>

                          <div class="col-sm-12 col-md-12">
                            <?php 
                              if (isset($_SESSION['pesanbukti']) && $_SESSION['pesanbukti'] <> '') {
                                  echo '<br><div class="pesan alert alert-success">'.$_SESSION['pesanbukti'].'</div>';
                              }
                              $_SESSION['pesanbukti'] = '';
                            ?>
                          </div>

                          <div class="col-sm-12 col-md-12">
                            <div id="checkSave" class="mt-4">
                              <button class="btn btn-primary btn-block" type="submit">Kirim Bukti Pembayaran</button>
                            </div>
                          </div>

                        </div>
                      </form>

                    <?php } ?>

                  <?php }else{ ?>
                    <div class="pb-3">
                      <h3 class="ft-18 font-weight-bold mt-3 mb-4">TRANSAKSI TIDAK DITEMUKAN</h3>
                      <a href="<?=$main_url;?>account" class="btn btn-primary">Kembali Ke Riwayat Transaksi</a>
                    </div>
                  <?php } ?>

                </div>
              </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabtrx" role="tabpanel" aria-labelledby="trx-tab">
                  <div class="default-shadow rounded-2">
                    <div class="padding-15">
                      <div class="">
                        
                        <div class="mb-2">
                          <a href="<?=$main_url;?>account" class="btn btn-light btn-sm">Kembali</a>
                          <?php if($rest_trx['is_status']=='p'){ ?>
                            <button class="btn btn-danger btn-sm ml-2" onClick="batalPesanan()">
                              <i class="fa fa-times"></i>&nbsp;&nbsp;Batalkan Transaksi
                            </button>
                          <?php } ?>

                          <?php if($rest_trx['is_status']=='k'){ ?>
                            <button class="btn btn-success btn-sm ml-2" onClick="datangPesanan()">
                              <i class="fa fa-check"></i>&nbsp;&nbsp;Konfirmasi Pesanan Sampai
                            </button>
                          <?php } ?>
                          <div class="mt-3">
                            <p class="color-dark ft-14">Nomor Resi Pengiriman : <br>
                              <?php 
                                if ($rest_trx['nomor_resi']=='') {
                                  echo '<b class="ft-18 font-weight-bold">Belum tersedia</b>';
                                }else{ ?>
                                  <b class="ft-18 font-weight-bold color-app c-pointer" onclick="lacakResi('<?=$rest_trx['nomor_resi'];?>','<?=$rest_trx['kurir'];?>')"><?=$rest_trx['nomor_resi'];?> - Lacak</b>
                                <?php } ?>
                            </p>
                          </div>
                        </div>

                        <div class="ft-14 font-weight-bold mb-3">
                          Pesanan
                        </div>

                        <div class="table-responsive mt-10 b-0">
                          <table class="table table-hover b-0">
                            <thead class="b-0">
                              <tr class="b-0">
                                <th class="ft-14">Produk</th>
                                <th class="ft-14 text-right">Harga</th>
                                <th class="ft-14 text-right">Jumlah</th>
                                <th class="ft-14 text-right">Subharga</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach($i_trx['m_cart'] as $obj) { ?>
                              <tr>
                                <td class="ft-14">
                                  <?=$obj['nama_produk'];?>
                                  <br/>
                                  <span class="ft-12"><?=$obj['varian'];?></span>
                                </td>
                                <td align="right" class="ft-14">
                                  <?=$obj['harga_produk'];?>
                                  <?php if ($obj['hs_diskon']!='0') { ?>
                                    <span class="ft-12 text-line-through color-semidark"><br/><?=$obj['hs_diskon'];?></span>
                                  <?php } ?>
                                </td>
                                <td align="right" class="ft-14"><?=$obj['jumlah_beli'];?></td>
                                <td align="right" class="ft-14">
                                  <?=$obj['total_harga_produk'];?>
                                  <?php if ($obj['hs_diskon']!='0') { ?>
                                  <span class="ft-12 text-line-through color-semidark"><br/><?=$obj['hst_diskon'];?></span>
                                  <?php } ?>
                                </td>
                              </tr>
                              <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td class="ft-14" colspan="1">Subtotal</td>
                                <td class="ft-14" colspan="3" align="right"><?=$rest_trx['subtotal_bayar'];?></td>
                              </tr>
                              <tr>
                                <td class="ft-14" colspan="1">Ongkos Kirim</td>
                                <td class="ft-14" colspan="3" align="right"><?=$rest_trx['ongkos_kirim'];?></td>
                              </tr>
                              <tr>
                                <td class="ft-14" colspan="1">Potongan Voucher</td>
                                <td class="ft-14 color-danger" colspan="3" align="right"><?=$rest_trx['potongan_voucher'];?></td>
                              </tr>
                              <tr>
                                <th class="ft-14" colspan="1">Total Harga</th>
                                <td class="ft-14 font-weight-bold" colspan="3" align="right">
                                  <?=$rest_trx['total_bayar'];?>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>

                        <div class="">
                          <div class="ft-14 mb-3">
                            <div class="ft-14 font-weight-bold mb-1">
                              Metode Pembayaran
                            </div>
                            <?php if ($rest_trx['metode_pembayaran']=='saldo') { ?>
                              Saldo
                            <?php }else{ ?>
                              <?=$rest_trx['m_bayar'];?>
                            <?php } ?>
                          </div>
                          <div class="ft-14 mb-3">
                            <div class="ft-14 font-weight-bold mb-1">
                              Metode Pengiriman
                            </div>
                            Kurir - <?=$rest_trx['nama_kurir'];?>
                            <br>Tingkat - <?=$rest_trx['level_kurir']?> (<?=$rest_trx['lama_pengiriman']?>hari)
                          </div>
                          <div class="ft-14">
                            <div class="ft-14 font-weight-bold mb-1">
                              Alamat Pengiriman
                            </div>
                            <?=$i_trx['m_alamat']['nama_penerima']?>
                            <br><?=$i_trx['m_alamat']['nama_provinsi']?>, <?=$i_trx['m_alamat']['nama_kabkot']?>, <?=$i_trx['m_alamat']['kodepos']?>
                            <br><?=$i_trx['m_alamat']['alamat_lengkap']?>
                            <br>
                            Nomor yang dapat di hubungi <?=$i_trx['m_alamat']['ponsel_penerima'];?>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div id="myTrackingresi" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Lacak Pesanan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <style type="text/css"> 
              .tanda0 { border-left: 3px solid #663f91; padding-left: 15px; }
              .tandamore { border-left: 2px solid #999; padding-left: 15px; }
            </style>
            <div id="lihatTrackingresi">Loading...</div>
          </div>
        </div>
      </div>
    </div>

    <?php
      $arr = array('opsi' => 'idsync', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idnotif' => $_GET['p_url'], 'lang' => 'en');
      loadData('rest_proses/proses_baca_notifikasi/', $arr);
    ?>

    <?php include "module/include/footer.php"; ?>
    
    <?php include "module/include/javascript.php"; ?>

    <script type="text/javascript">
      function sendPayment(){
        $.confirm({
            title: 'Confirm!',
            content: 'Pastikan bukti pembayaran yang di masukan benar!',
            theme: 'modern',
            closeIcon: true,
            draggable: false,
            animation: 'scale',
            type: 'dark',
            buttons: {
              Batal: function () {

              },
              Simpan: function () {
                $('button').addClass('disabled');
                var formData = new FormData($("#form_kirim_bukti_bayar")[0]);
                $.ajax({
                  type: "POST",
                  url: '<?=$main_url;?>module/action.php?jen=kirim_bukti_bayar',
                  data:  formData,
                  contentType: false,
                  cache: false,
                  processData:false,
                  success: function(result){
                    $('button').removeClass('disabled');
                    var res = result.split('~');
                    if (res[0]=='y') {
                      confirmBerhasil(res[1],'reload');
                    }else{
                      confirmGagal(res[1]);
                    }
                  } 
                });
              }
            }
        });
      }
      function batalPesanan(){
        $.confirm({
            title: 'Confirm!',
            content: 'Transaksi akan dibatalkan, kamu yakin?',
            theme: 'modern',
            closeIcon: true,
            draggable: false,
            animation: 'scale',
            type: 'red',
            buttons: {
              Batal: function () {

              },
              Simpan: function () {
                $('button').addClass('disabled');
                var notrx = $('#no_transaksiid').val();
                $.ajax({
                  type : "POST",
                  url : "<?=$main_url;?>module/action.php?jen=batalkan_transaksi",
                  data :  { 'notrx' : notrx },
                  success: function(result){
                    $('button').removeClass('disabled');
                    var res = result.split('~');
                    if (res[0]=='y') {
                      confirmBerhasil(res[1],'reload');
                    }else{
                      confirmGagal(res[1]);
                    }
                  } 
                });
              }
            }
        });
      }
      
      function datangPesanan(){
        $.confirm({
            title: 'Confirm!',
            content: 'Pastikan pesanan sudah kamu terima, yakin?',
            theme: 'modern',
            closeIcon: true,
            draggable: false,
            animation: 'scale',
            type: 'red',
            buttons: {
              Batal: function () {

              },
              Selesai: function () {
                $('button').addClass('disabled');
                var notrx = $('#no_transaksiid').val();
                $.ajax({
                  type : "POST",
                  url : "<?=$main_url;?>module/action.php?jen=tiba_transaksi",
                  data :  { 'notrx' : notrx },
                  success: function(result){
                    $('button').removeClass('disabled');
                    var res = result.split('~');
                    if (res[0]=='y') {
                      confirmBerhasil(res[1],'reload');
                    }else{
                      confirmGagal(res[1]);
                    }
                  } 
                });
              }
            }
        });
      }

      function lacakResi(a,b){
        $('#myTrackingresi').modal('toggle');
        formTrackingresi(a,b);
      }

      function formTrackingresi(a,b) {
        $.get('<?=$main_url;?>module/action.php?jen=cek_resi&resi='+a+'&kurir='+b, function(data) {
          $('#lihatTrackingresi').html(data);
        });
      }

    </script>

  </body>
</html>