<?php include "module/module.php"; ?>
<?php if (!isset($_SESSION['XID_ARRAY'])) { header("Location: ".$main_url); exit(); } ?>
<!doctype html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?=$rest_sistem['result']['meta_description'];?>">
    <meta name="keywords" content="<?=$rest_sistem['result']['meta_keywords'];?>">
    
    <?php include "module/include/style.php"; ?>

    <title>Saldo - <?=$rest_cust['result']['cust_nama'];?></title>
  </head>
  <body>

    <?php include "module/include/header.php"; ?>

    <section class="bg-container mt-4">
      <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-xl-10_ col-lg-10_ col-md-11_">
          <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 mb-4">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabtrx" role="tabpanel" aria-labelledby="trx-tab">

                  <div class="default-shadow rounded-2 mb-3">
                    <div class="padding-15 border-bottom1 pt-4">
                      <div class="">
                        <div class="ft-14 font-weight-bold mb-1">
                          Saldo Kamu :
                        </div>
                        <div class="ft-18 color-app font-weight-bold b-0">
                          <?=$rest_cust['saldo'];?>
                        </div>
                      </div>
                      <div class="p-absolute right-30" style="top: 7px !important;">
                        <a href="<?=$main_url;?>account" class="ft-16 color-semidark-m">&nbsp;<i class="fa fa-close"></i>&nbsp;</a>
                      </div>
                      <div class="p-absolute right-30" style="top: 40px !important;">
                        <button type="button" onclick="topupSaldo()" class="btn btn-primary ft-14 border-radius-5 height-38">&nbsp;Topup&nbsp;</button>
                      </div>
                    </div>

                    <ul class="nav nav-tabs b-0 mb-3 border-bottom1" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active ft-14" id="trxtopup-tab" data-toggle="tab" href="#tabtrxtopup" role="tab" aria-controls="tabtrxtopup" aria-selected="true">Riwayat Topup</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link ft-14" id="alamat-tab" data-toggle="tab" href="#tabalamat" role="tab" aria-controls="tabalamat" aria-selected="false">Riwayat Saldo</a>
                      </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="tabtrxtopup" role="tabpanel" aria-labelledby="trxtopup-tab">
                        <div class="">
                          <div class="padding-15 pt-0">
                            <div class="">
                              <div class="ft-14 font-weight-bold mb-3">
                                Riwayat Topup
                              </div>

                              <?php 
                                $arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
                                $rest_trx = loadData('rest_load/load_riwayat_topup/', $arr);
                              ?>

                              <div class="table-responsive mt-10 b-0" style="height:310px; overflow-y: auto;">
                                <table class="table table-hover b-0">
                                  <thead class="b-0">
                                    <tr class="b-0">
                                      <th class="ft-14">No Transaksi</th>
                                      <th class="ft-14">Nominal</th>
                                      <th class="ft-14">Status</th>
                                      <th class="ft-14">Tanggal</th>
                                      <th class="ft-14">&nbsp;</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach($rest_trx['result'] as $obj) { ?>
                                    <tr>
                                      <td class="ft-14">
                                        <a href="<?=$main_url;?>topup/<?=$obj['uid'];?>" class="font-weight-bold">
                                          <?=$obj['kode'];?>
                                        </a>
                                      </td>
                                      <td class="ft-14"><?=$obj['nominal'];?></td>
                                      <td class="ft-14">
                                        <span class="color-<?=$obj['status_clr'];?>"><?=$obj['status'];?></span>
                                      </td>
                                      <td class="ft-14">
                                        <?=$obj['tanggal'];?>
                                      </td>
                                      <td class="ft-14" align="right">
                                        <a href="<?=$main_url;?>topup/<?=$obj['uid'];?>">
                                          Lihat
                                        </a>
                                      </td>
                                    </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="tabalamat" role="tabpanel" aria-labelledby="alamat-tab">
                        <div class="">
                          <div class="padding-15 pt-0">
                            <div class="">
                              <div class="ft-14 font-weight-bold mb-3">
                                Riwayat Saldo
                              </div>

                              <?php 
                                $arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
                                $rest_trx = loadData('rest_load/load_riwayat_saldo/', $arr);
                              ?>

                              <div class="table-responsive mt-10 b-0" style="height:310px; overflow-y: auto;">
                                <table class="table table-hover b-0">
                                  <thead class="b-0">
                                    <tr class="b-0">
                                      <th class="ft-14">No Transaksi</th>
                                      <th class="ft-14">Tipe</th>
                                      <th class="ft-14">Nominal</th>
                                      <th class="ft-14">Tanggal</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach($rest_trx['result'] as $obj) { ?>
                                    <tr>
                                      <td class="ft-14">
                                        <?php if ($obj['tipe']=='trx') { ?>
                                        <a href="<?=$main_url;?>trx/<?=$obj['uid'];?>" class="font-weight-bold">
                                          <?=$obj['kode'];?>
                                        </a>
                                        <?php }else if($obj['tipe']=='topup'){ ?>
                                        <a href="<?=$main_url;?>topup/<?=$obj['uidtopup'];?>" class="font-weight-bold">
                                          <?=$obj['kode'];?>
                                        </a>
                                        <?php }else { ?>
                                          <?=$obj['kode'];?>
                                        <?php } ?>
                                      </td>
                                      <td class="ft-14"><?=$obj['tipelbl'];?></td>
                                      <td class="ft-14">
                                        <span class="color-<?=$obj['clr_ft'];?>"><?=$obj['nominal'];?></span>
                                      </td>
                                      <td class="ft-14">
                                        <?=$obj['tanggal'];?>
                                      </td>
                                    </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
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
        </div>
      </div>
    </section>

    <div class="modal fade" id="myModalTopupsaldo">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal body -->
          <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
          <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
          <div class="modal-body">
            <div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
            <div class="text-center p-mob-30">
              <div class="section-title ft-18 text-center mb-3">
                  &mdash; Topup Saldo &mdash;
              </div>
              <div class="row text-center pb-3 pr-3 pl-3">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <form id="form_topup_akun_saldo" action="javascript:goTopupSaldo()" method="POST">
                    <div class="form-group mb-1 text-left">
                      <label class="">Nominal<span class="color-danger">*</span></label>
                      <input type="text" class="form-control ft-16" name="nominal_topup" id="nominal_topup" placeholder="0" required="" autocomplete="off" onkeydown="return angkatOnly(event.key)">
                      <div class="ft-12 mt-2 color-semidark">Minimal Rp 10.000</div>
                    </div>
                    <div class="mt-4 mb-4">
                      <div class="">
                        <div id="infosebelumsubmitx"></div>
                      </div>
                      <button type="submit" class="btn btn-primary btn-block">Topup</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include "module/include/footer.php"; ?>
    
    <?php include "module/include/javascript.php"; ?>

    <?php if($rest_sistem['result']['midtrans_tipekey']=='production') { ?>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?=$rest_sistem['result']['midtrans_clientkey'];?>"></script>
    <?php }else{ ?>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?=$rest_sistem['result']['midtrans_clientkey'];?>"></script>
    <?php } ?>

    <script type="text/javascript">

      function topupSaldo(){
          $('#myModalTopupsaldo').modal('toggle');
      }

      function goTopupSaldo(){

        var nominalv = $('#nominal_topup').val();

        if (!nominalv){
          $('#infosebelumsubmitx').html('<div class="alert alert-danger">Nominal tidak boleh kosong.</div>');
          return false;
        }else{
          $.confirm({
            title: 'Confirm!',
            content: 'Pastikan nominal yang di masukan benar!',
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
                if (itipepay=='midtrans'){
                  $.ajax({
                    url: '<?=$main_url;?>module/action.php?jen=snap_token_midtrans_topup&nominalv='+nominalv,
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
                          prosestopupCheckout(obj.uniquecode,objmid,'y',nominalv);
                        },
                        onPending: function(resultmid){
                          var objmid = JSON.stringify(resultmid);
                          prosestopupCheckout(obj.uniquecode,objmid,'p',nominalv);
                        },
                        onError: function(resultmid){
                          document.location='<?=$main_url;?>topup/error';
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
                  prosestopupCheckout('manual','manual','p',nominalv);
                }
              }
            }
          });
        }
      }

      function prosestopupCheckout(c,d,e,f){
        if (e=='y' || e=='p') {
          $.ajax({
            type : "POST",
            url : "<?=$main_url;?>module/action.php?jen=simpan_topup_saldo",
            data :  { 'idunique' : c, 'snapobj' : d, 'statuspay' : e, 'nominaltopup' : f },
            success: function (result) {
              var res = result.split('~');
              if (res[0]=='y') {
                if(e=='y' || e=='p'){
                  document.location='<?=$main_url;?>topup/'+res[2];
                }else{
                  document.location='<?=$main_url;?>topup/error';
                }
              }else{
                confirmGagal(res[1],'reload');
              }
            }
          });
        }else{
          document.location='<?=$main_url;?>topup/error';
        }
      }
    </script>

  </body>
</html>