	
	<div class="modal fade" id="myModalsearch">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <div class="modal-body">
	        <div class="">
	        	<form action="javascript:goRedirect('search')" method="POST">
			       	<div class="input-group">
					  <input type="text" class="form-control ft-16" id="searchtext_val" placeholder="Cari apapun di sini..." autocomplete="off">
					  <div class="input-group-append">
					    <button class="btn btn-app" type="button" onclick="goRedirect('search')"><span class="icon-search"></span></button>
					  </div>
					</div>
				</form>
		    </div>
	      </div>
	      <!-- Modal footer -->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Tutup</button>
	      </div>

	    </div>
	  </div>
	</div>

	<?php if (!isset($_SESSION['XID_ARRAY'])) { ?>
	<div class="modal fade" id="myModalLogin">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
	      <div class="modal-body">
	      	<div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
	        <div class="text-center p-mob-30">
	        	<div class="row text-center pb-3 pr-3 pl-3">
		          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
		            <div class="row">
		              <div class="col-xl-6 col-lg-6 col-sm-12">
		              	<div class="">
		                  <img src="<?=$main_url;?>assets/images/komponen/login_cstore_web_popup_dark.png" class="img-fluid">
		              	</div>
		              </div>
		              <div class="col-xl-6 col-lg-6 col-sm-12 mt-5">
		              	<form id="form_signin" action="javascript:goSignin()" method="POST">
					       	<div class="form-group text-left">
					       		<label class="">Alamat Email</label>
							  	<input type="text" class="form-control ft-16" name="alamat_email" required="">
							</div>
							<div class="input-group">
							  	<input type="password" class="form-control ft-16" name="password" placeholder="*****************" required="">
							  	<div class="input-group-append">
								    <button class="btn btn-app" type="submit"><span class="icon-sign-in"></span></button>
								</div>
							</div>
						</form>
		              	<div class="ft-14 mt-2 mb-2">atau</div>
		                <a href="<?=$google_login_url?>" class="btn btn-danger btn-block p-2 border-e4 hv-a-google" style="border-radius: 100px">
		                	<span class="icon-google mr-2"></span> <span class="ft-14"> Masuk dengan Google </span>
		                </a>
		                <div class="text-left mt-4 color-semidark">
		                	Dengan melanjutkan, Anda menunjukkan bahwa Anda menerima kami <a href="<?=$main_url;?>terms-of-use">Syarat dan Ketentuan</a> dan <a href="<?=$main_url;?>privacy-policy">Kebijakan Privasi</a>.
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
	<?php } ?>

	<?php if (!isset($_SESSION['XID_ARRAY'])) { ?>
	<div class="modal fade" id="myModalDaftar">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
	      <div class="modal-body">
	      	<div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
	        <div class="text-center p-mob-30">
	        	<div class="row text-center pb-3 pr-3 pl-3">
		          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
		            <div class="row">
		              <div class="col-xl-6 col-lg-6 col-sm-12">
		              	<div class="">
		                  <img src="<?=$main_url;?>assets/images/komponen/login_cstore_web_popup_dark.png" class="img-fluid">
		              	</div>
		              </div>
		              <div class="col-xl-6 col-lg-6 col-sm-12 mt-5">
		              	<form id="form_registration" action="javascript:goRegistrasi()" method="POST">
					       	<div class="form-group mb-1 text-left">
					       		<label class="">Alamat Email</label>
								<div class="input-group">
								  	<input type="text" class="form-control ft-16" name="alamat_email" required="">
								  	<div class="input-group-append">
									    <button class="btn btn-app" type="submit"><span class="icon-sign-in"></span></button>
									</div>
								</div>
							</div>
						</form>
						<div id="xxxnotif_otp"></div>
		              	<form class="d-none" id="form_activation_otp_resis" action="javascript:goActivationOtp()" method="POST">
							<div class="form-group text-left">
					       		<label class="">Aktivasi</label>
								<div class="input-group">
								  	<input type="text" class="form-control ft-16" name="kode_otp" placeholder="Masukan kode OTP">
								  	<input type="hidden" class="form-control ft-16" name="alamat_email" id="val_activation_otp_email">
								  	<div class="input-group-append">
									    <button class="btn btn-app" type="submit"><span class="icon-sign-in"></span></button>
									</div>
								</div>
							</div>
						</form>
						<div class="ft-14 mt-2 mb-2">atau</div>
		                <a href="<?=$google_login_url?>" class="btn btn-danger btn-block p-2 border-e4 hv-a-google" style="border-radius: 100px">
		                	<span class="icon-google mr-2"></span> <span class="ft-14"> Daftar dengan Google </span>
		                </a>
		                <div class="text-left mt-4 color-semidark">
		                	Dengan melanjutkan, Anda menunjukkan bahwa Anda menerima kami <a href="<?=$main_url;?>terms-of-use">Syarat dan Ketentuan</a> dan <a href="<?=$main_url;?>privacy-policy">Kebijakan Privasi</a>.
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
	<?php } ?>

	<?php if (isset($_SESSION['XID_ARRAY'])) { ?>
	<div class="modal fade" id="myModaleditProfil">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
	      <div class="modal-body">
	      	<div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
	        <div class="text-center p-mob-30">
	        	<div class="section-title ft-18 text-center mb-3">
		            &mdash; Edit Akun &mdash;
		        </div>
	        	<div class="row text-center pb-3 pr-3 pl-3">
		          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
		            <form id="form_edit_akun_profil" action="javascript:goEditakunProfil()" method="POST"  enctype="multipart/form-data">
					    <div class="form-group mb-1 text-left">
					      	<label class="">Nama Lengkap<span class="color-danger">*</span></label>
							<input type="text" class="form-control ft-16" name="cust_nama" value="<?=$rest_cust['result']['cust_nama'];?>" required="" autocomplete="off">
						</div>
						<div class="form-group mb-1 text-left">
					      	<label class="">Nomor Ponsel / WhatsApp<span class="color-danger">*</span></label>
							<input type="text" class="form-control ft-16" name="cust_ponsel" value="<?=$rest_cust['result']['cust_ponsel'];?>" required="" autocomplete="off">
						</div>
						<div class="form-group mb-1 text-left">
					      	<label class="">Foto Profil <span class="color-semidark ft-14">opsional</span></label>
							<input type="file" class="form-control ft-16" name="gambar">
						</div>
						<div class="mt-4 mb-4">
			               <button type="submit" class="btn btn-primary btn-block">Simpan</button>
			           </div>
					</form>
		          </div>
		        </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

	<style type="text/css">
		.inotifyc:hover {
            background: #ddd !important;
        }
	</style>

	<div class="modal fade" id="myModalNotifikasi">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
	      <div class="modal-body">
	      	<div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
	        <div class="text-center p-mob-30">
	        	<div class="section-title ft-18 text-center mb-3">
		            &mdash; Notifikasi &mdash;
		        </div>
	        	<div class="row">
		          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
		            <div id="resmyModalNotifikasi" style="max-height:380px; overflow-y: auto;">
		            	<?php 
					      $arr = array('opsi' => 'i', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idnotif' => 'n', 'lang' => 'en');
					      $rest_notif = loadData('rest_load/load_notifikasi/',$arr);

					      if ($rest_notif['nums']>0) {

					      $no=1; foreach($rest_notif['result'] as $objx) {

						  if ($objx['is_read']=='n') { 
						    $bginfoynotif = "#f9f9f9";
						  }else{
						    $bginfoynotif = "#fff";
						  }

						  if ($objx['tipe_notif']=='trx') {
						    $xklikinfoyc = "goRedirect('".$main_url.'trx/'.$objx['sync_id']."')";
						  }else if ($objx['tipe_notif']=='topup') {
						    $xklikinfoyc = "goRedirect('".$main_url.'topup/'.$objx['sync_id']."')";
						  }else{
						    $xklikinfoyc = "goRedirect('".$main_url.'n/'.$objx['notifikasi_id']."')";
						  }
						?>
						<div class="inotifyc c-pointer border-bottom1 padding-10 text-left rounded mb-2 ft-14" onclick="<?=$xklikinfoyc;?>" style="background: <?=$bginfoynotif;?>; max-height: 60px; overflow: hidden;">
  							<?=$no;?>. 
  							<span class="float-right ft-12">
  								<?=substr($objx['created_at'],0,10)." ".substr($objx['created_at'],11,5)." WIB";?>
  							</span>
  							<?=$objx['judul_notif'];?><br>
  							<small><?=$objx['ket_notif'];?></small>
  						</div>
  						<?php $no++; } ?>
  						<?php }else{ ?>
  							<div class="color-semidark ft-16">Tidak ada notifikasi</div>
  						<?php } ?>
		            </div>
		          </div>
		        </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php } ?>

	<div class="modal fade" id="myModalDownloadapp">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <div class="modal-body">
	        <div class="text-center">
			    <div class="title-category font-weight-bold ft-18">Segera Hadir...</div>
		    </div>
	      </div>
	      <!-- Modal footer -->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Tutup</button>
	      </div>

	    </div>
	  </div>
	</div>

	<?php if (isset($_SESSION['XID_ARRAY'])) { ?>
	<div class="modal fade" id="myModalCart" tabindex="-1" role="dialog" aria-labelledby="myModalLabelCart">
	    <div class="right modal-dialog" role="document">
	      <div class="modal-content h-100">
	        <div class="modal-header b-0">
	        	<div class="font-weight-bold">&mdash; Keranjang &mdash;</div>
	        	<button type="button" class="close ft-30" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        </div>
	        <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 80px);">
	          <div id="resmyModalCart">Loading...</div>
	        </div>
	      </div>
	    </div>
	</div>

	<div class="modal fade" id="myModalCartItem">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
	      <div class="modal-body">
	      	<div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
	        <div class="text-center p-mob-30">
		        <div id="resmyModalCartItem"></div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php } ?>

	<?php if (isset($_SESSION['XID_ARRAY'])) { ?>
	<div class="modal fade" id="myModalOptionalamat">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <!-- Modal body -->
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v1.png" class="p-absolute h-100 left-0">
	      <img src="<?=$main_url;?>assets/images/komponen/bubble_x3_v2.png" class="p-absolute h-100 right-0">
	      <div class="modal-body">
	      	<div class="close_pfda" data-dismiss="modal"><span class="icon-close2"></span></div>
	      	<div id="resmyModalOptionalamat">Loading...</div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php } ?>

	<div class="modal fade" id="myModalVoucher" tabindex="-1" role="dialog" aria-labelledby="myModalLabelVoucher">
	    <div class="right modal-dialog" role="document">
	      <div class="modal-content h-100">
	        <div class="modal-header b-0">
	        	<div class="font-weight-bold">&mdash; Voucher &mdash;</div>
	        	<button type="button" class="close ft-30" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        </div>
	        <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 80px);">
	         	<?php
					$arr = array('tipe' => 'web', 'lang' => 'en');
				    $rest_val = loadData('rest_load/load_voucher/', $arr);
				?>
				<style type="text/css">
					.border-bottom-dashed1-c1 {
					  border-bottom: 1px dashed #C1C1C1 !important;
					}
				</style>
				<div class="padding-0-15">
					<div class="row">
						<?php if ($rest_val['items_count']>0) { ?>
						<div class="col-xl-12 col-lg-12">
							<div class="alert alert-primary ft-13">Gunakan kode voucher pada saat checkout.</div>
						</div>
						<?php foreach($rest_val['result'] as $obj) { ?>
							<div class="col-xl-12 col-lg-12 mb-3 pb-3 border-bottom-dashed1-c1">
								<div class="d-flex align-items-c_">
									<div class="text-left">
										<div class="media-title ft-13 color-dark">
											<div class="color-dark ft-14 font-weight-600">
						                      <?=$obj['nama_voucher'];?>
						                  	</div>
						                  	<div class="color-dark">
						                      Gunakan Voucher : <b class="color-app"><?=$obj['kode_voucher'];?></b>
						                  	</div>
						                  	<div class="color-dark">
						                      Potongan Diskon : <?=$obj['hlbl'];?>
						                  	</div>
						                  	<div class="color-dark">
						                      Minimal Belanja : <?=$obj['minimal_belanja'];?>
						                  	</div>
						                  	<div class="color-dark">
						                      Maksimal Diskon : <?=$obj['xlbl'];?>
						                  	</div>
						                  	<div class="color-dark">
						                      Berlaku : <?=$obj['llbl'];?>
						                  	</div>
					                    </div>
									</div>
								</div>
							</div>
						<?php } ?>
						
						<?php }else{ ?>
							<div class="col-xl-12 col-lg-12">
								<div class="ft-13 text-center">Saat ini tidak ada voucher yang bisa digunakan.</div>
							</div>
						<?php } ?>
					</div>
				</div>
	        </div>
	      </div>
	    </div>
	</div>

	<div id="container-floating">
	  <?php if ($rest_sistem['result']['whatsapp']!=''){ ?>
	  <div class="nd3 nds">
	    <p class="letter-ic">
	    	<a href="https://api.whatsapp.com/send?phone=<?=$rest_sistem['result']['whatsapp'];?>&amp;text=" class="color-putih" target="_blank">
	    		<span class="icon icon-whatsapp"></span>
	    	</a>
	    </p>
	  </div>
	  <?php } ?>

	  <?php if ($rest_sistem['result']['call_center']!=''){ ?>
	  <div class="nd1 nds">
	    <p class="letter-ic">
	    	<a href="tel:<?=$rest_sistem['result']['call_center'];?>" class="color-putih" target="_blank"><span class="icon icon-phone"></span></a>
	    </p>
	  </div>
	  <?php } ?>

	  <div id="floating-button">
	    <p class="plus-fab"><span class="icon icon-headset_mic"></span></p>
	    <p class="edit-fab"><span class="icon icon-close"></span></p>
	  </div>
	</div>

	<script src="<?=$main_url;?>assets/vendor/main/popper.min.js"></script>
    <script src="<?=$main_url;?>assets/vendor/main/bootstrap.min.js"></script>
    <script src="<?=$main_url;?>assets/vendor/main/jquery.sticky.js"></script>
    <script src="<?=$main_url;?>assets/vendor/bootstrap-select.min.js"></script>
    <script src="<?=$main_url;?>assets/vendor/jquery-confirm.min.js"></script>
    <script src="<?=$main_url;?>assets/vendor/main/main.js"></script>

    <?php if (isset($_SESSION['XID_ARRAY'])) { ?>
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		cekjumlahCart();
	    		setInterval(function() {
	    			autocekNotifikasi();
	    		}, 1500);
	    		setInterval(function() {
	            	cekjumlahCart();
	            }, 2000);

	    	});

	    	function autocekNotifikasi(){
	    		$.get('<?=$main_url;?>module/action.php?jen=auto_cek_notifikasi', function(data) {
	    			if (data==0) {
				    	$(".notifikasiidcheckcls").addClass('d-none');
				    }else{
				    	$(".notifikasiidcheckcls").removeClass('d-none');
				    }
	            });
			}
	    </script>
    <?php } ?>

    <script type="text/javascript">

    	$(document).ready(function(){

            $('.selectpicker').selectpicker();
            
    		setTimeout(function() {
    			$('#popalert-fixed').addClass('d-none');
    		}, 3000);

    		setTimeout(function() {
    			$('#alert-auto-close').addClass('d-none');
    		}, 10000);

    		$('#popalert-fixed').click(function(){
    			$('#popalert-fixed').addClass('d-none');
			});
    		
            $('#selectedmenunav a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
            $('#selectedmenunav a').click(function(){
                $(this).parent().addClass('active').siblings().removeClass('active')    
            });

            $('#selectedmenunax a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
            $('#selectedmenunax a').click(function(){
                $(this).parent().addClass('active').siblings().removeClass('active')    
            });

            $('#selectedmenunaximob a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
            $('#selectedmenunaximob a').click(function(){
                $(this).parent().addClass('active').siblings().removeClass('active')    
            });

        });

        function angkatOnly(key) {
	        return (key >= '0' && key <= '9') || key == 'ArrowLeft' || key == 'ArrowRight' || key == 'Delete' || key == 'Backspace' || key == 'Tab' || key == 'F5' || key == 'command';
	        // onkeydown="return angkatOnly(event.key)"
	    }

	    function actionAlamat(a,b = 'new'){
	        $('#myModalOptionalamat').modal('toggle');
	        formmyOptionAlamat(a,b);
	    }

	    function formmyOptionAlamat(a,b) {
	        $('#resmyModalOptionalamat').html('Loading...');    
	        $.get('<?=$main_url;?>module/action.php?jen=my_alamat_cust&tipe='+a+'&idalamat='+b, function(data) {
	            $('#resmyModalOptionalamat').html(data);    
	        });
	    }	    

    	function myModalsearch(){
    		$('body').removeClass('offcanvas-menu');
    		setTimeout(function() {
	    		$('#myModalsearch').modal('toggle');
	    	}, 200);
    	}

    	function myModalLogin(){
    		$("#sidebarStore").removeClass("active");
    		$(".overlay").removeClass("visible");
    		$('#myModalLogin').modal('toggle');
    	}

    	function myModalDaftar(){
    		$("#sidebarStore").removeClass("active");
    		$(".overlay").removeClass("visible");
    		$('#myModalDaftar').modal('toggle');
    	}

    	function myModalDownloadapp(){
    		$('#myModalDownloadapp').modal('toggle');
    	}

    	function myModaleditProfil(){
    		$('#myModaleditProfil').modal('toggle');
    	}

    	function myModalCart(){
    		$('body').removeClass('offcanvas-menu');
    		setTimeout(function() {
	    		$('#myModalCart').modal('toggle');
	    		formmyModalCart();
	    	}, 200);
    	}

    	function formmyModalCart() {           
		    $.get('<?=$main_url;?>module/action.php?jen=my_cart', function(data) {
		        $('#resmyModalCart').html(data);    
		    });
		}

		function myModalCartItem(a){
	    	$('#myModalCartItem').modal('toggle');
	    	formmyModalCartItem(a);
    	}

    	function formmyModalCartItem(a) {           
		    $.get('<?=$main_url;?>module/action.php?jen=my_cart_item&idcart='+a, function(data) {
		        $('#resmyModalCartItem').html(data);    
		    });
		}

    	function gohapusCart(a,b = null){
    		$.get('<?=$main_url;?>module/action.php?jen=hapus_p_cart&idcart='+a, function(data) {
    			if (b==null) {
	    			formmyModalCart();
	    		}else{
	    			location.reload();
	    		}
		    });
    	}

    	function cekjumlahCart() {
    		$.get('<?=$main_url;?>module/action.php?jen=cek_jumlah_cart', function(data) {
              $('.jumlah_cart_my_id').html(data);
              $('.jumlah_cart_my_idx').html(data);
            });
	    }

	    function openNotifikasi(){
		    $('body').removeClass('offcanvas-menu');
    		setTimeout(function() {
	    		$('#myModalNotifikasi').modal('toggle');
	    	}, 200);
		}

    	function goRedirect(a){
    		if (a=='search') {
    			var valtext = $('#searchtext_val').val();
    			if (valtext!='') {
	    			window.location.href='<?=$main_url;?>s/'+valtext;
	    		}
    		}else if (a=='searchx') {
    			var valtext = $('#searchtext_valx').val();
    			if (valtext!='') {
	    			window.location.href='<?=$main_url;?>s/'+valtext;
	    		}
    		}else if (a=='searchmob') {
    			var valtext = $('#searchtext_valmob').val();
    			if (valtext!='') {
	    			window.location.href='<?=$main_url;?>s/'+valtext;
	    		}
    		}else{
    			window.location.href=a;
    		}
    	}

    	function formatRupiah (money) {
	        return new Intl.NumberFormat('id-ID',
	          { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }
	        ).format(money);
	    }

    	function goEditakunProfil(){
    		$.confirm({
		        title: 'Confirm!',
		        content: 'Pastikan data yang di masukan benar!',
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
		            var formData = new FormData($("#form_edit_akun_profil")[0]);
		            $.ajax({
		              type: "POST",
		              url: '<?=$main_url;?>module/action.php?jen=edit_akun_profil',
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

    	function goEditakunPassword(){
    		$.confirm({
		        title: 'Confirm!',
		        content: 'Pastikan password yang di masukan slalu kamu ingat!',
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
		            var formData = new FormData($("#form_ubah_password_akun")[0]);
		            $.ajax({
		              type: "POST",
		              url: '<?=$main_url;?>module/action.php?jen=edit_akun_password',
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

    	function goRegistrasi(){
    		
    		$.confirm({
		        title: 'Confirm!',
		        content: 'Kode OTP akan dikirim ke email kamu, silahkan cek secara berkala.',
		        theme: 'modern',
		        closeIcon: true,
		        draggable: false,
		        animation: 'scale',
		        type: 'dark',
		        buttons: {
		          Batal: function () {

		          },
		          Kirim: function () {
		            $('button').addClass('disabled');
		            var formData = new FormData($("#form_registration")[0]);
		            $.ajax({
		              type: "POST",
		              url: '<?=$main_url;?>module/action.php?jen=registration',
		              data:  formData,
		              contentType: false,
		              cache: false,
		              processData:false,
		              success: function(result){
		                $('button').removeClass('disabled');
		                var res = result.split('~');
		                if (res[0]=='y') {
		                	$('#xxxnotif_otp').html('<div id="xxxnotif_otp" class="alert alert-primary mt-4 d-none">'+res[2]+'</div>');
		                	$('#form_activation_otp_resis').removeClass('d-none');
		                	$('#val_activation_otp_email').val(res[1]);
		                	confirmBerhasil(res[2]);
		                }else{
		                  	confirmGagal(res[2]);
		                }
		              } 
		            });
		          }
		        }
		    });
    	}

    	function goSignin(){
    		$('button').addClass('disabled');
		    var formData = new FormData($("#form_signin")[0]);
		    $.ajax({
		      type: "POST",
		      url: '<?=$main_url;?>module/action.php?jen=signin_login',
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

    	function goActivationOtp(){
    		$('button').addClass('disabled');
		    var formData = new FormData($("#form_activation_otp_resis")[0]);
		    $.ajax({
		      type: "POST",
		      url: '<?=$main_url;?>module/action.php?jen=activation_otp',
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

    	function confirmBerhasil(a,b = null,c = null){
	      $.confirm({
	        icon: 'fa fa-check',
	        title: 'Successfully',
	        content: a,
	        theme: 'modern',
	        autoClose: 'OK|3000',
	        type: 'green',
	        draggable: false,
	        buttons: {
	          OK: function () {
	            if (b=='reload') {
	              document.body.scrollTop = 0;
	              document.documentElement.scrollTop = 0;
	              location.reload();
	            }else if (b=='redirect') {
	              document.body.scrollTop = 0;
	              document.documentElement.scrollTop = 0;
	              window.location.href='<?=$main_url;?>'+c;
	            }
	          }
	        }
	      });
	    }

	    function confirmGagal(a,b = null){
	      $.confirm({
	        icon: 'fa fa-times',
	        title: 'Oops!',
	        content: a,
	        theme: 'modern',
	        autoClose: 'OK|5000',
	        type: 'red',
	        draggable: false,
	        buttons: {
	          OK: function () {
	            if (b=='reload') {
	              document.body.scrollTop = 0;
	              document.documentElement.scrollTop = 0;
	              location.reload();
	            }
	          }
	        }
	      });
	    }

    	function pSignout() {
    		$.get('<?=$main_url;?>module/action.php?jen=sign_out', function(data) {
              location.reload();
            });
	    }
	    
    </script>