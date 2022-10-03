<?php include "module/module.php"; ?>
<?php if (!isset($_SESSION['XID_ARRAY'])) { header("Location: ".$main_url); exit(); } ?>
<!doctype html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?=$rest_sistem['result']['meta_description'];?>">
    <meta name="keywords" content="<?=$rest_sistem['result']['meta_keywords'];?>">

    <meta property="og:title" content="Checkout">
    <meta property="og:description" content="<?=$rest_sistem['result']['meta_description'];?>">
    <meta property="og:image" content="<?=$main_imgurl;?>logo/<?=$rest_sistem['result']['logo_toko_image'];?>">
    <meta property="og:url" content="<?=$main_url;?>/v/checkout">
    
    <?php include "module/include/style.php"; ?>

    <title>Checkout</title>
  </head>
  <body>

    <?php include "module/include/header.php"; ?>

    <section id="" class="bg-container-2 mt-4 mb-30">
      <div class="title-category pb-3 border-bot-d font-weight-bold ft-14">
        <a href="<?=$main_url;?>">Home</a>
        &nbsp;>&nbsp;
        <a href="<?=$main_url;?>account"><?=$rest_cust['result']['cust_nama'];?></a>
        &nbsp;>&nbsp;
        Checkout
      </div>
    </section>

    <?php 
      $arr = array('tipe' => 'all', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
      $rest_alamat = loadData('rest_load/load_alamat_customer/',$arr);
    ?>

    <section class="bg-container-2 mb-5">

      <?php 
        if (isset($_SESSION['trxidunique_msg']) && $_SESSION['trxidunique_msg'] <> '') {
            echo '<div class="alert alert-danger mb-4">'.$_SESSION['trxidunique_msg'].'</div>';
        }
        $_SESSION['trxidunique_msg'] = '';
      ?>

      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <div class="produk_detail">
            <form id="form_p_cart_checkout" action="javascript:snapPayprocess()" method="POST">
              <div class="row">
                <div class="col-xl-8 col-lg-7 mb-2">
                  <div class="border-bottom-dashed1 pb-10">
                    <div class="detail_pd ft-16 font-weight-bold">
                      &mdash; Alamat Pengiriman &mdash;
                    </div>
                  </div>
                  <div class="pb-4 w-100">
                    <div class="font-weight-bold color-app mt-3 mb-3">
                      <select class="selectpicker border-radius-5 mr-2" name="alamat_id" data-live-search="true" title="-- Pilih --" required="" onchange="changeAlamat(this.value)">
                        <?php 
                          foreach($rest_alamat['result'] as $obj) { 
                          if($rest_alamat['iselected']['cust_det_id']==$obj['cust_det_id']){
                            $oksip ='selected';
                          }else{
                            $oksip ='';
                          }
                        ?>
                        <option value="<?=$obj['cust_det_id'];?>" <?=$oksip;?>><?=$obj['label_alamat'];?></option>
                        <?php } ?>
                      </select>
                      <button type="button" onclick="actionAlamat('add')" class="btn btn-outline-primary ft-14 border-d border-radius-5 height-38"><i class="fa fa-plus"></i>&nbsp;&nbsp;Alamat</button>
                    </div>
                    <?php if ($rest_alamat['nums']>0) { ?>
                    <div class="alamat_nya_my_send padding-10-15 rounded-2 border-d" id="alamat_nya_my_send" style="background: #f3f4f5;">
                      <div class="ft-14 font-weight-bold mb-1">
                        <?=$rest_alamat['iselected']['label_alamat'];?>
                        <span class="float-right"><span class="icon-pencil-square-o c-pointer" onclick="actionAlamat('edit','<?=$rest_alamat['iselected']['cust_det_id']?>')"></span></span>
                      </div>
                      <div class="ft-14">Penerima : <?=$rest_alamat['iselected']['nama_penerima'];?></div>
                      <div class="ft-14"><?=$rest_alamat['iselected']['alamat_lengkap'];?>, 
                        <span class="text-lowercase"><?=$rest_alamat['iselected']['nama_provinsi'];?>, <?=$rest_alamat['iselected']['nama_kabkot'];?> - <?=$rest_alamat['iselected']['kodepos'];?></span>
                      </div>
                      <div class="ft-14">Nomor yang dapat di hubungi <?=$rest_alamat['iselected']['ponsel_penerima'];?></div>
                      <input type="hidden" id="idkbakot_id" value="<?=$rest_alamat['iselected']['id_kabkot']?>">
                      <input type="hidden" id="cust_det_id" value="<?=$rest_alamat['iselected']['cust_det_id']?>">
                    </div>
                    <?php }else{ ?>
                    <div class="alamat_nya_my_send padding-10-15 rounded-2 border-d" style="background: #f3f4f5;">
                      <div class="ft-14 font-weight-bold mb-1">
                        Tidak ada alamat tersimpan
                      </div>
                      <div class="ft-14">Tambah alamat untuk melanjutkan.</div>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="border-bottom-dashed1 pb-10 pt-10">
                    <div class="detail_pd ft-16 font-weight-bold">
                      &mdash; Kurir Pengiriman &mdash;
                    </div>
                  </div>
                  <div class="font-weight-bold color-app mt-3 mb-3">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12">
                        <select id="kurir_idmy" class="form-control border-d border-radius-5" name="kurir_id" required="" onchange="selectKurir(this.value)">
                          <option value="">-- Pilih Kurir --</option>
                          <option value="jne">JNE</option>
                          <option value="jnt">J&T Express</option>
                          <option value="sicepat">SiCepat Express</option>
                          <option value="anteraja">AnterAja</option>
                          <option value="lion">Lion Parcel</option>
                          <option value="pos">POS Indonesia</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="" id="rajaongkir_pilihan_kurir"></div>
                </div>
                <div class="col-xl-4 col-lg-5 mb-4">
                  <div class="">
                    <div class="bg-putih">
                      <div class="border-bottom-dashed1 mb-4 mt-15 d-none-991"></div>
                      <div class="border-d rounded-2 padding-10-15">
                        <div class="font-weight-bold ft-16 mb-3">Keranjang</div>
                        <?php
                          $arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
                          $rest_val_c = loadData('rest_load/load_cart/', $arr);
                        ?>
                        <div class="padding-0-15_">
                          <div class="row">
                            <?php if ($rest_val_c['items_count']>0) { ?>
                            <?php foreach($rest_val_c['result'] as $obj) { ?>
                              <div class="col-xl-12 col-lg-12 mb-3">
                                <div class="d-flex align-items-c_">
                                  <a href="<?=$main_url;?>p/<?=$obj['url_produk'];?>" class="mr-3">
                                    <div class="bg_cart-set-2 rounded-2" style="background: url('<?=$main_imgurl.'products/'.$obj['logo_image'];?>');"></div>
                                  </a>
                                  <div class="text-left">
                                    <div class="media-title ft-14 font-weight-600 color-dark"> 
                                      <a href="<?=$main_url;?>p/<?=$obj['url_produk'];?>" class="color-dark">
                                        <?=$obj['nama_produk'];?>
                                      </a>
                                      <span class="p-absolute right-15 ft-14 c-pointer del-cart-p" onclick="gohapusCart('<?=$obj['cart_id'];?>','reload')"><span class="icon-trash-o"></span></span>
                                    </div>
                                    
                                    <div class="media-title ft-14 color-semidark-m"> 
                                      Varian : <?=$obj['varian'];?>
                                    </div>
                                    <?php if ($obj['tstok']=='y') { ?>
                                    <div class="media-title ft-14 color-semidark-m"> 
                                      <?=$obj['harga_produk'];?>
                                      <span class="p-absolute right-15">x<?=$obj['jumlah_beli'];?></span>
                                    </div>
                                    <div class="media-title ft-14 color-dark font-weight-600"> 
                                      Subtotal : <?=$obj['harga_produk_q'];?>
                                    </div>
                                    <?php } ?>
                                    <?php if ($obj['tstok']!='y') { ?>
                                      <span class="p-absolute right-15 ft-14 color-semidark-m">x<?=$obj['jumlah_beli'];?></span>
                                      <div class="media-title ft-14 color-danger mr-1"> 
                                        <?=$obj['tstok'];?>
                                      </div>
                                    <?php } ?>
                                  </div>
                                </div>
                              </div>
                            <?php } ?>
                            
                            <div class="col-xl-12 col-lg-12">
                              <div class="text-left">
                                <hr>
                                  <div class="ft-14">
                                    Subtotal 
                                    <span class="float-right"><?=substr($rest_val_c['total_bayar'],3);?></span>
                                  </div>
                                  <div class="ft-14">
                                    Ongkos Kirim 
                                    <span class="float-right" id="harga_ongkir_pay_id">0</span>
                                  </div>
                                  <div class="ft-14">
                                    Potongan Voucher 
                                    <span class="float-right color-danger" id="harga_voucher_pay_id">0</span>
                                  </div>
                                  <div class="font-weight-600 ft-16">
                                    Total Pembayaran
                                    <span class="float-right" id="total_h_pay_id"><?=$rest_val_c['total_bayar'];?></span>
                                  </div>
                                <hr>
                              </div>
                              <div class="">
                                <div id="infosebelumsubmit"></div>
                              </div>
                              <div class="">
                                <?php if ($rest_cust['result']['cust_nama']=='' || $rest_cust['result']['cust_ponsel']==''){ ?>
                                <div class="alert alert-danger c-pointer" onclick="myModaleditProfil();">Lengkapi data informasi untuk melanjutkan belanja. Klik untuk melengkapi.</div>
                                <?php }else{ ?>
                                <?php if ($rest_sistem['result']['fitur_saldo']=='y') { ?>
                                  <div class="form-group mb-3">
                                    <div class="pb-2">
                                      <span class="ft-12">Saldo :</span> 
                                      <span class="color-dark font-weight-500 ft-14"><?=$rest_cust['saldo'];?></span>
                                    </div>

                                    <select class="selectpicker form-control border-radius-5" name="tipe_payment" title="-- Metode Pembayaran --" required="" id="tipe_payment" onchange="checkSaldo()">
                                      <option value="bank">Bank Transfer</option>
                                      <option value="saldo">Saldo</option>
                                    </select>
                                  </div>
                                  <?php }else{ ?>
                                  <input type="hidden" name="tipe_payment" id="tipe_payment" value="bank">
                                <?php } ?>
                                <div class="form-group text-left">
                                  <div class="ft-12" id="voucher_nya_my_send"></div>
                                  <div class="input-group">
                                    <input type="text" class="form-control ft-14" name="kode_voucher_icust" id="kode_voucher_icust" placeholder="Masukan Kode Voucher" autocomplete="off" onkeyup="checkVoucher(this.value)">
                                    <div class="input-group-append">
                                      <button class="btn btn-app height-35" type="button" onclick="checkVoucher(kode_voucher_icust.value)"><span class="icon-check"></span></button>
                                    </div>
                                  </div>
                                </div>
                                <div id="alertnotiflblsaldopay"></div>
                                <button type="submit" id="submitpayproses" class="btn btn-primary ft-14 border-d border-radius-5 btn-block"> Proses Pembayaran </button>
                                <?php } ?>
                              </div>
                            </div>
                            
                            <?php }else{ ?>
                              <div class="col-xl-12 col-lg-12">
                                <div class="padding-15">
                                  <img src="<?=$main_imgurl;?>logo/<?=$rest_sistem['result']['empty_cart_image'];?>" class="img-fluid">
                                </div>
                              </div>
                            <?php } ?>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <?php include "module/include/footer.php"; ?>
    
    <?php include "module/include/javascript.php"; ?>
    <?php if($rest_sistem['result']['midtrans_tipekey']=='production') { ?>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?=$rest_sistem['result']['midtrans_clientkey'];?>"></script>
    <?php }else{ ?>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?=$rest_sistem['result']['midtrans_clientkey'];?>"></script>
    <?php } ?>

    <script type="text/javascript">

      $(document).ready(function(){
        $("#kurir_idmy").val('');
      });

      var total_bayar = "<?=$rest_val_c['total_bayar_num'];?>";
      var total_ongkir = 0;
      var total_voucher = 0;

      var grand_total_bayar = "<?=$rest_val_c['total_bayar_num'];?>";

      var fitur_saldo = "<?=$rest_sistem['result']['fitur_saldo'];?>";

      function checkSaldo() {
        if (fitur_saldo=='y') {
          var a = $('#tipe_payment').val();
          var saldo = "<?=$rest_cust['saldo_num'];?>";
          if (a=='saldo') {
            if (saldo>=grand_total_bayar) {
              $('#submitpayproses').removeClass('disabled-x');
              $('#alertnotiflblsaldopay').html('');
            }else{
              $('#submitpayproses').addClass('disabled-x');
              $('#alertnotiflblsaldopay').html('<div class="color-danger mb-3">Saldo tidak mencukupi.</div>');
            }
          }else{
            $('#submitpayproses').removeClass('disabled-x');
            $('#alertnotiflblsaldopay').html('');
          }
        }
      }

      function changeAlamat(a) {
        $('#alamat_nya_my_send').html('Loading...');
        $.get('<?=$main_url;?>module/action.php?jen=change_my_alamat&idalamat='+a, function(data) {
            $("#kurir_idmy").val('');
            $("#rajaongkir_pilihan_kurir").html('');
            $('#alamat_nya_my_send').html(data);
        });
      }

      function checkVoucher(a) {
        $('#voucher_nya_my_send').html('<div class="mb-2">Loading...</div>');
        $.get('<?=$main_url;?>module/action.php?jen=check_voucher&kode='+a+'&total_bayar='+total_bayar, function(data) {
          var res_v = data.split('~');
          if (res_v[0]=='y') {
            total_voucher = res_v[1];
            $("#voucher_nya_my_send").html('');
            $('#harga_voucher_pay_id').html('-'+formatRupiah(res_v[1]));

            grand_total_bayar = parseInt(total_bayar)+parseInt(total_ongkir)-parseInt(res_v[1]);
            $('#total_h_pay_id').html(formatRupiah(grand_total_bayar));
          }else{
            total_voucher = 0;
            $('#voucher_nya_my_send').html('<div class="mb-2 color-danger">'+res_v[0]+'</div>');
            $('#harga_voucher_pay_id').html(0);

            grand_total_bayar = parseInt(total_bayar)+parseInt(total_ongkir);
            $('#total_h_pay_id').html(formatRupiah(grand_total_bayar));
          }

          checkSaldo();
        });
      }

      function selectKurir(a){
        $("#rajaongkir_pilihan_kurir").html('Loading...');
        var kabkot   = $('#idkbakot_id').val();
        var kurir = $('#kurir_idmy').val();
        $.ajax({
          type : 'POST',
          url : '<?=$main_url;?>module/action.php?jen=raja_ongkir_cari_kurir',
          data :  { 'kabkot_id' : kabkot, 'kurir_id' : kurir },
          success: function (data) {
            $("#rajaongkir_pilihan_kurir").html(data);
          }
        });
      }

      function selectKurirv2(a,b){
        total_ongkir = b;
        $("#ku"+a).prop("checked", true);
        $('#kurir_yg_dipilih_fix').val(a);
        $('#harga_ongkir_pay_id').html(formatRupiah(b));
        grand_total_bayar = parseInt(total_bayar)+parseInt(b)-parseInt(total_voucher);
        $('#total_h_pay_id').html(formatRupiah(grand_total_bayar));

        checkSaldo();
      }

      function snapPayprocess(){
        var kabkot = $('#idkbakot_id').val();
        var alamatid = $('#cust_det_id').val();
        var jeniskurir = $('#kurir_idmy').val();
        var kurirdipilih = $('#kurir_yg_dipilih_fix').val(); // kuririd

        var kodevoucher = $('#kode_voucher_icust').val();

        var metodepembayaran = $('#tipe_payment').val();

        if (!kabkot){
          $('#infosebelumsubmit').html('<div class="alert alert-danger">Gagal memuat data, refresh browser dan coba lagi.</div>');
          return false;
        }else if(!alamatid){
          $('#infosebelumsubmit').html('<div class="alert alert-danger">Alamat tidak terbaca, pilih atau tambah alamat baru dan silahkan coba lagi.</div>');
          return false;
        }else if(!jeniskurir){
          $('#infosebelumsubmit').html('<div class="alert alert-danger">Ekspedisi atau jenis pengiriman belum di pilih.</div>');
          return false;
        }else if(!kurirdipilih){
          $('#infosebelumsubmit').html('<div class="alert alert-danger">Kurir belum di pilih.</div>');
          return false;
        }else if(!metodepembayaran){
          $('#infosebelumsubmit').html('<div class="alert alert-danger">Metode pembayaran belum di pilih.</div>');
          return false;
        }else{

          if (metodepembayaran=='bank' || metodepembayaran=='saldo') {
            $.confirm({
              title: 'Confirm!',
              content: 'Pastikan orderan dan alamat pengiriman yang di masukan sudah benar!',
              theme: 'modern',
              closeIcon: true,
              draggable: false,
              animation: 'scale',
              type: 'dark',
              buttons: {
                Batal: function () {

                },
                Submit: function () {
                  $('button').addClass('disabled');
                  var itipepay = "<?=$rest_sistem['result']['metode_pembayaran'];?>";

                  if (itipepay=='midtrans' && metodepembayaran=='bank'){
                    $.ajax({
                      url: '<?=$main_url;?>module/action.php?jen=snap_token_midtrans&idalamat='+alamatid+'&idkurir='+kurirdipilih+'&kodevoucher='+kodevoucher,
                      cache: false,
                      success: function(result){
                        console.log(result);
                        $('button').removeClass('disabled');
                        var obj = JSON.parse(result);
                        snap.pay(obj.snapMidtrans, {
                          autoCloseDelay: 2,
                          showOrderId: false,
                          onSuccess: function(resultmid){
                            var objmid = JSON.stringify(resultmid);
                            prosescartCheckout(alamatid,kurirdipilih,obj.uniquecode,objmid,'y',kodevoucher,metodepembayaran);
                          },
                          onPending: function(resultmid){
                            var objmid = JSON.stringify(resultmid);
                            prosescartCheckout(alamatid,kurirdipilih,obj.uniquecode,objmid,'p',kodevoucher,metodepembayaran);
                          },
                          onError: function(resultmid){
                            document.location='<?=$main_url;?>payment/error/';
                          },
                          onClose: function(){
                            document.body.scrollTop = 0;
                            document.documentElement.scrollTop = 0;
                            console.log('close snap!')
                          }
                        });
                      } 
                    });
                  }else{
                    prosescartCheckout(alamatid,kurirdipilih,'manual','manual','p',kodevoucher,metodepembayaran);
                  }
                }
              }
            });
          }else{
            confirmGagal('Metode pembayaran tidak sesuai.','reload');
          }
        }
      }

      function prosescartCheckout(a,b,c,d,e,f,g){
        if (e=='y' || e=='p') {
          $.ajax({
            type : "POST",
            url : "<?=$main_url;?>module/action.php?jen=simpan_transaksi",
            data :  { 'idalamat' : a, 'idkurir' : b, 'idunique' : c, 'snapobj' : d, 'statuspay' : e, 'kodevoucher' : f, 'potonganvoucher' : total_voucher, 'metodepembayaran' : g },
            success: function (result) {
              console.log(result);
              var res = result.split('~');
              if (res[0]=='y') { // success
                if(e=='y'){
                  document.location='<?=$main_url;?>payment/success/';
                }else if(e=='p'){ // pending blm bayar
                  document.location='<?=$main_url;?>payment/pending/';
                }else{
                  document.location='<?=$main_url;?>payment/error/';
                }
              }else{
                confirmGagal(res[1],'reload');
              }
            }
          });
        }else{
          document.location='<?=$main_url;?>payment/error/';
        }
      }
        
    </script>

  </body>
</html>