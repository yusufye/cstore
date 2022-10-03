<?php 

	include "module.php";

	if ($_GET['jen']=='check_voucher') {

	    $arr = array('kode' => $_GET['kode'], 'total_bayar' => $_GET['total_bayar'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
	    $rest_val = loadData('rest_load/check_voucher/', $arr);

	    echo $rest_val['st'].'~'.$rest_val['nominal'];

	}
	if ($_GET['jen']=='product_action') {

	    $arr = array('tipe' => $_GET['tipe'], 'idproduk' => $_GET['idproduk'], 'idwarna' => $_GET['idwarna'], 'idukuran' => $_GET['idukuran'], 'lang' => 'en');
	    $rest_produk_stok = loadData('rest_load/load_produk_stok/', $arr);

	    echo $rest_produk_stok['stok']."~".$rest_produk_stok['varian'];

	}
	if ($_GET['jen']=='signin_login') {
		$arr = array('tipe' => 'web', 'email' => $_POST['alamat_email'], 'password' => $_POST['password'], 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_signin/', $arr);

	    if ($rest_val['success']==true) {
	    	$rest_val['success'] = 'y';
	    	$_SESSION['XID_ARRAY'] = $rest_val['result'];
	    }else{
	    	$rest_val['success'] = 'n';
	    }

	    echo $rest_val['success'].'~'.$rest_val['msg'];
	}
	if ($_GET['jen']=='registration') {
		$arr = array('tipe' => 'web', 'from' => 'manual', 'email' => $_POST['alamat_email'], 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_signup/', $arr);

	    if ($rest_val['success']==true) {
	    	$rest_val['success'] = 'y';
	    }else{
	    	$rest_val['success'] = 'n';
	    }

	    echo $rest_val['success'].'~'.$rest_val['email'].'~'.$rest_val['msg'];
	}
	if ($_GET['jen']=='activation_otp') {
		$arr = array('tipe' => 'web', 'email' => $_POST['alamat_email'], 'kode_aktivasi' => $_POST['kode_otp'], 'onesignalid' => '', 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_aktivasi/', $arr);

	    if ($rest_val['success']==true) {
	    	$rest_val['success'] = 'y';
		    $_SESSION['XID_ARRAY'] = $rest_val['result'];
	    }else{
	    	$rest_val['success'] = 'n';
	    }


	    echo $rest_val['success'].'~'.$rest_val['msg'];
	}
	if ($_GET['jen']=='edit_akun_profil') {
		$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'cust_nama' => $_POST['cust_nama'], 'cust_ponsel' => $_POST['cust_ponsel'], 'gambar' => $_FILES['gambar']['name'], 'gambar_tmp' => $_FILES['gambar']['tmp_name'], 'gambar_size' => $_FILES['gambar']['size'], 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_edit_akun/', $arr);

	    if ($rest_val['success']==true) {
	    	$rest_val['success'] = 'y';
	    }else{
	    	$rest_val['success'] = 'n';
	    }

	    echo $rest_val['success'].'~'.$rest_val['msg'];
	}
	if ($_GET['jen']=='edit_akun_password') {

		if ($rest_cust['result']['is_password']!='') {
			$pass_lama = $_POST['password_lama'];
		}else{
			$pass_lama = '';
		}

		if ($_POST['password_baru']==$_POST['password_confirm']) {
			$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'password_lama' => $pass_lama, 'password_baru' => $_POST['password_baru'], 'lang' => 'en');
		    $rest_val = loadData('rest_proses/proses_edit_password/', $arr);

		    if ($rest_val['success']==true) {
		    	$rest_val['success'] = 'y';
		    }else{
		    	$rest_val['success'] = 'n';
		    }

		    echo $rest_val['success'].'~'.$rest_val['msg'];

		}else{
		    echo 'n~Ulangi password tidak sesuai.';
		}

	}
	if ($_GET['jen']=='add_to_cart') {

		$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idproduk' => $_POST['produk_id'], 'idwarna' => $_POST['warna_id'], 'idukuran' => $_POST['ukuran_id'], 'idcart' => $_POST['cart_id'], 'jumlah_qty' => $_GET['jumlah_beli'], 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_add_cart/', $arr);

	    if ($rest_val['success']==true) {
	    	$rest_val['success'] = 'y';
	    }else{
	    	$rest_val['success'] = 'n';
	    }

		echo $rest_val['success'].'~'.$rest_val['msg'];

	}
	if ($_GET['jen']=='add_wo_whislist') {

		$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idproduk' => $_GET['produk_id'], 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_add_whislist/', $arr);

	    if ($rest_val['success']==true) {
	    	$rest_val['success'] = 'y';
	    }else{
	    	$rest_val['success'] = 'n';
	    }

		echo $rest_val['success'].'~'.$rest_val['ires'].'~'.$rest_val['msg'];

	}
	if ($_GET['jen']=='sign_out') { 
		unset($_SESSION['XID_ARRAY']); 
		unset($_SESSION['access_token']); 
		session_destroy(); 
		exit(); 
	} 
	if ($_GET['jen']=='cek_jumlah_cart') {
		if ($_SESSION['XID_ARRAY']['cust_id']) {
			$arr = array('idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
		    $rest_val = loadData('rest_load/load_jumlah_cart/', $arr);
		    echo "+".$rest_val['jumlah_cart'];
		}else{
		    echo '';
		}
	}
	if ($_GET['jen']=='hapus_p_cart') {
		$arr = array('idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idcart' => $_GET['idcart'], 'lang' => 'en');
	    $rest_val = loadData('rest_proses/proses_del_cart/', $arr);
	}
?>
<?php if ($_GET['jen']=='my_cart') { ?>
	<?php
		$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
	    $rest_val = loadData('rest_load/load_cart/', $arr);
	?>
	<div class="padding-0-15">
		<div class="row">
			<?php if ($rest_val['items_count']>0) { ?>
			<?php foreach($rest_val['result'] as $obj) { ?>
				<div class="col-xl-12 col-lg-12 mb-3">
					<div class="d-flex align-items-c_">
						<a href="<?=$main_url;?>p/<?=$obj['url_produk'];?>" class="mr-3">
							<div class="bg_cart-set rounded-2" style="background: url('<?=$main_imgurl.'products/'.$obj['logo_image'];?>');"></div>
						</a>
						<div class="text-left">
							<div class="media-title ft-14 font-weight-600 color-dark"> 
								<a href="<?=$main_url;?>p/<?=$obj['url_produk'];?>" class="color-dark">
			             <?=$obj['nama_produk'];?>
			          </a>
			          <span class="p-absolute right-0 ft-14 c-pointer del-cart-p" onclick="gohapusCart('<?=$obj['cart_id'];?>')"><span class="icon-trash-o"></span></span>
			          <span class="p-absolute right-20 ft-14 c-pointer del-cart-p" onclick="myModalCartItem('<?=$obj['cart_id'];?>')"><span class="icon-pencil"></span></span>
		          </div>
		          <div class="media-title ft-14 color-semidark-m"> 
		            Varian : <?=$obj['varian'];?>
		          </div>
		          <?php if ($obj['tstok']=='y') { ?>
		          <div class="media-title ft-14 color-semidark-m"> 
		            <?=$obj['harga_produk'];?>
		            <span class="p-absolute right-0">x<?=$obj['jumlah_beli'];?></span>
		          </div>
		          <div class="media-title ft-14 color-dark font-weight-600"> 
		            Subtotal : <?=$obj['harga_produk_q'];?>
		          </div>
			      	<?php } ?>
			      	<?php if ($obj['tstok']!='y') { ?>
				      <span class="p-absolute right-0 ft-14 color-semidark-m">x<?=$obj['jumlah_beli'];?></span>
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
					<hr><div class="font-weight-600 ft-16">Total : <?=$rest_val['total_bayar'];?></div><hr>
				</div>
				<div class="">
					<a href="<?=$main_url;?>v/checkout" class="btn btn-primary ft-14 border-d border-radius-5 btn-block"> Checkout </a>
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
<?php } ?>
<?php if ($_GET['jen']=='my_cart_item') { ?>
	<?php
		$arr = array('idcart' => $_GET['idcart'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
  	$rest_i_cart = loadData('rest_load/load_item_cart/', $arr);

  	$arr = array('wishlist' => 'n', 'idproduk' => $rest_i_cart['result']['url_produk'], 'new' => '', 'tipe' => '', 'lang' => 'en');
  	$rest_produk = loadData('rest_load/load_produk/', $arr); $res_p = $rest_produk['result'][0];

  	$arr = array('idproduk' => $rest_i_cart['result']['produk_id'], 'lang' => 'en');
  	$rest_v_varian = loadData('rest_load/load_produk_varian/', $arr);
  ?>
  <div class="section-title ft-18 text-center mb-3">
	    &mdash; Edit <?=$res_p['nama_produk'];?> &mdash;
	</div>
	<div class="row">
	  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	  	<div class="bg-putih border-d_ rounded-2">
        <div class="padding-10-15">
          <form id="form_p_add_to_cartx" action="javascript:prosesaddtoCartx()" method="POST">
            <?php if ($rest_v_varian['result']['row_w']>0) { ?>
            <div class="ft-14 varian_warna">
              <div class="form-group mb-2">
                <label class="">Warna</label>
                <select class="form-control" name="warna_id" id="id_warna_pidx" onchange="checkStokProdukx()" required="">
                  <option value="">-- Pilih --</option>
                  <?php foreach($rest_v_varian['result']['p_warna'] as $objx) { ?>
                  <option value="<?=$objx['warna_id'];?>" <?php if ($rest_i_cart['result']['warna_id']==$objx['warna_id']) echo 'selected'; ?>><?=$objx['nama_warna'];?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php }else{ ?>
              <input type="hidden" name="warna_id" id="id_warna_pidx">
            <?php } ?>
            <?php if ($rest_v_varian['result']['row_u']>0) { ?>
            <div class="ft-14 varian_ukuran">
              <div class="form-group mb-2">
                <label class="">Ukuran</label>
                <select class="form-control" name="ukuran_id" id="id_ukuran_pidx" onchange="checkStokProdukx()" required="">
                  <option value="">-- Pilih --</option>
                  <?php $no=1; foreach($rest_v_varian['result']['p_ukuran'] as $objx) { $ukid = explode('~', $objx['ukuran_id']); ?>
                  <option value="<?=$objx['ukuran_id'];?>" <?php if ($rest_i_cart['result']['ukuran_id']==$ukid[0]) echo 'selected'; ?>><?=$objx['ukuran'];?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php }else{ ?>
              <input type="hidden" name="ukuran_id" id="id_ukuran_pidx">
            <?php } ?>
            <div class="ft-14 varian_qty">
              <div class="form-group mb-2">
                <label class="">Jumlah / Qty</label>
                <div class="input-group disabled-x" id="id_jumlah_qty_px">
                  <div class="input-group-prepend">
                      <button type="button" class="btn btn-light ft-14 border-d b-lt-lb-5" id="minus-bt-px"><i class="fa fa-minus"></i></button>
                  </div>
                  <input type="number" name="qty_input_pr" id="qty_input_prx" class="form-control text-center bg-putih" value="<?=$rest_i_cart['result']['jumlah_beli'];?>" min="1">
                  <div class="input-group-prepend">
                      <button type="button" class="btn btn-light ft-14 border-d b-rt-rb-5" id="plus-bt-px"><i class="fa fa-plus"></i></button>
                  </div>
                  <div class="ml-2" style="line-height: 35px">
                    <span id="id_produk_stok_rex">Stok <b>...</b></span>
                  </div>
                </div>
              </div>
              <div class="varian_subtotal mt-3 w-100">
                <?php if ($res_p['potongan_status']=='y') { ?>
                <div class="text-right text-line-through color-semidark-m ft-14" id="subtotal_harga_apx"><?=$res_p['harga_produk_awal'];?></div>
                <?php } ?>
                <div class="d-flex align-items-center j-c-sb">
                  <span class="color-semidark-m">Subtotal</span>
                  <span class="color-dark ft-18 font-weight-bold" id="subtotal_harga_px"><?=$res_p['harga_produk'];?></span>
                </div>
              </div>
            </div>
            <div class="ft-14 varian_submit mt-2">
              <div class="">
                <input type="hidden" name="produk_id" value="<?=$res_p['produk_id'];?>">
                <input type="hidden" name="cart_id" value="<?=$_GET['idcart'];?>">
                <button type="submit" class="btn btn-primary ft-14 border-d border-radius-5 btn-block" id="produk_keranjang_submit_pidx">
                  <i class="fa fa-check"></i>&nbsp;&nbsp;Simpan Perubahan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
	  </div>
	</div>

	<script type="text/javascript">

		var h_produk_awalx = '<?=$res_p['harga_produk_awal_num'];?>';
    var h_produkx = '<?=$res_p['harga_produk_num'];?>';

    var id_px = '<?=$res_p['produk_id'];?>';
    var row_wx = '<?=$rest_v_varian['result']['row_w'];?>';
    var row_ux = '<?=$rest_v_varian['result']['row_u'];?>';
      
    var hrga_tmbhanx = 0;
    var p_stokx = 1;

    var qty_awalx = '<?=$rest_i_cart['result']['jumlah_beli'];?>';

    $(document).ready(function(){

    		$('#subtotal_harga_px').html(formatRupiah((parseInt(h_produkx)+parseInt(hrga_tmbhanx))*parseInt(qty_awalx)));
        $('#subtotal_harga_apx').html(formatRupiah((parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx))*parseInt(qty_awalx)));

        if (row_wx==0 && row_ux==0) {
          $.get('<?=$main_url;?>module/action.php?jen=product_action&tipe=zero&idproduk='+id_px+'&idwarna=0&idukuran=0', function(res) {
            var data = res.split('~');
            if (data[0]>0) {
              p_stokx = parseInt(data[0]);
              $('#id_produk_stok_rex').html('Stok <b>'+data[0]+'</b>');
            }else{
              p_stokx = 1;
              $('#id_produk_stok_rex').html('Stok Habis');
              $('#produk_keranjang_submit_pidx').addClass('disabled-x');
            }
            $('#id_jumlah_qty_px').removeClass('disabled-x');
          });
        }

        $('#qty_input_prx').prop('disabled', true);
        $('#plus-bt-px').click(function(){
          if ($('#qty_input_prx').val() < p_stokx) {
            var qty_px = parseInt($('#qty_input_prx').val()) + 1;
            $('#qty_input_prx').val(qty_px);
            $('#subtotal_harga_px').html(formatRupiah((parseInt(h_produkx)+parseInt(hrga_tmbhanx))*parseInt(qty_px)));
            $('#subtotal_harga_apx').html(formatRupiah((parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx))*parseInt(qty_px)));
          }
        });
        $('#minus-bt-px').click(function(){
          var qty_px = parseInt($('#qty_input_prx').val()) - 1;
          $('#qty_input_prx').val(qty_px);
          if ($('#qty_input_prx').val() == 0) {
            $('#qty_input_prx').val(1);
            if(qty_px!=0){
              $('#subtotal_harga_px').html(formatRupiah(parseInt(h_produkx)+parseInt(hrga_tmbhanx)));
              $('#subtotal_harga_apx').html(formatRupiah(parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx)));
            }
          }else{
            $('#subtotal_harga_px').html(formatRupiah((parseInt(h_produkx)+parseInt(hrga_tmbhanx))*parseInt(qty_px)));
            $('#subtotal_harga_apx').html(formatRupiah((parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx))*parseInt(qty_px)));
          }
        });

        checkStokProdukx();

        setTimeout(function() {
	        $('#qty_input_prx').val(qty_awalx);
	    	}, 500);

    });

		function checkStokProdukx(){

      var id_wx = $('#id_warna_pidx').val();
      var id_ux = $('#id_ukuran_pidx').val();

      if (row_wx>0 && row_ux>0 && id_wx!='' && id_ux!='') {
        $('#id_produk_stok_rex').html('Loading...');
        s_udix = id_ux.split('~');
        hrga_tmbhanx = s_udix[1];

        $.get('<?=$main_url;?>module/action.php?jen=product_action&tipe=zero&idproduk='+id_px+'&idwarna='+id_wx+'&idukuran='+s_udix[0], function(res) {
          var data = res.split('~');
          if (data[0]>0) {
            p_stokx = parseInt(data[0]);
            $('#id_produk_stok_rex').html('Stok <b>'+data[0]+'</b>');
            $('#produk_keranjang_submit_pidx').removeClass('disabled-x');
          }else{
            p_stokx = 1;
            $('#id_produk_stok_rex').html('Stok Habis');
            $('#produk_keranjang_submit_pidx').addClass('disabled-x');
          }
          $('#qty_input_prx').val(1)
          $('#id_jumlah_qty_px').removeClass('disabled-x');

          $('#subtotal_harga_px').html(formatRupiah(parseInt(h_produkx)+parseInt(hrga_tmbhanx)));
          $('#subtotal_harga_apx').html(formatRupiah(parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx)));
        });
      }else if (row_wx>0 && row_ux==0 && id_wx!='') {
        $('#id_produk_stok_rex').html('Loading...');
        $.get('<?=$main_url;?>module/action.php?jen=product_action&tipe=zero&idproduk='+id_px+'&idwarna='+id_wx+'&idukuran=0', function(res) {
          var data = res.split('~');
          if (data[0]>0) {
            p_stokx = parseInt(data[0]);
            $('#id_produk_stok_rex').html('Stok <b>'+data[0]+'</b>');
            $('#produk_keranjang_submit_pidx').removeClass('disabled-x');
          }else{
            p_stokx = 1;
            $('#id_produk_stok_rex').html('Stok Habis');
            $('#produk_keranjang_submit_pidx').addClass('disabled-x');
          }
          $('#qty_input_prx').val(1)
          $('#id_jumlah_qty_px').removeClass('disabled-x');

          $('#subtotal_harga_p').html(formatRupiah(parseInt(h_produkx)+parseInt(hrga_tmbhanx)));
          $('#subtotal_harga_ap').html(formatRupiah(parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx)));

        });
      }else if (row_wx==0 && row_ux>0 && id_ux!='') {
        $('#id_produk_stok_rex').html('Loading...');
        s_udix = id_ux.split('~');
        hrga_tmbhanx = s_udix[1];

        $.get('<?=$main_url;?>module/action.php?jen=product_action&tipe=zero&idproduk='+id_px+'&idwarna=0&idukuran='+s_udix[0], function(res) {
            var data = res.split('~');
            if (data[0]>0) {
              p_stokx = parseInt(data[0]);
              $('#id_produk_stok_rex').html('Stok <b>'+data[0]+'</b>');
              $('#produk_keranjang_submit_pidx').removeClass('disabled-x');
            }else{
              p_stokx = 1;
              $('#id_produk_stok_rex').html('Stok Habis');
              $('#produk_keranjang_submit_pidx').addClass('disabled-x');
            }
            $('#qty_input_prx').val(1)
            $('#id_jumlah_qty_px').removeClass('disabled-x');

            $('#subtotal_harga_px').html(formatRupiah(parseInt(h_produkx)+parseInt(hrga_tmbhanx)));
            $('#subtotal_harga_apx').html(formatRupiah(parseInt(h_produk_awalx)+parseInt(hrga_tmbhanx)));
            
        });
      }else{
        $('#id_produk_stok_rex').html('Stok ...');
        $('#id_jumlah_qty_px').addClass('disabled-x');
      }

    }

    function prosesaddtoCartx(){
      $('button').addClass('disabled');
      var formData = new FormData($("#form_p_add_to_cartx")[0]);
      $.ajax({
        type: "POST",
        url: '<?=$main_url;?>module/action.php?jen=add_to_cart&jumlah_beli='+$('#qty_input_prx').val(),
        data:  formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
          $('button').removeClass('disabled');
          var res = result.split('~');
          if (res[0]=='y') {
          	$('#myModalCartItem').modal('hide');
          	formmyModalCart();
            confirmBerhasil(res[1]);
          }else{
            confirmGagal(res[1]);
          }
        } 
      });
    }

	</script>
	
<?php } ?>
<?php if ($_GET['jen']=='my_alamat_cust') { ?>
	<?php
		$arr = array('tipe' => $_GET['tipe'], 'idalamat' => $_GET['idalamat'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
	    $rest_val = loadData('rest_load/load_alamat_customer/', $arr);
	?>
	<div class="text-center p-mob-30">
		<div class="section-title ft-18 text-center mb-3">
			<?php if ($_GET['tipe']=='add') { ?>
			&mdash; Tambah Alamat &mdash;
			<?php }else{ ?>
			&mdash; Edit Akun &mdash;
			<?php } ?>
		</div>
	    <div class="row text-center pb-3 pr-3 pl-3">
		    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
		    	<form id="form_option_alamat_akun" action="javascript:goOptionmyAlamat()" method="POST">
		    		<div class="form-group mb-1 text-left">
					    <label class="">Label Alamat</label>
						<input type="hidden" class="form-control ft-16" name="idalamat" value="<?=$_GET['idalamat'];?>">
						<input type="hidden" class="form-control ft-16" name="idtipe" value="<?=$_GET['tipe'];?>" required="">
						<input type="text" class="form-control ft-16" name="label_alamat" value="<?=$rest_val['result']['label_alamat'];?>" required="" autocomplete="off">
					</div>
					<div class="form-group mb-1 text-left">
					    <label class="">Nama Penerima</label>
						<input type="text" class="form-control ft-16" name="nama_penerima" value="<?=$rest_val['result']['nama_penerima'];?>" required="" autocomplete="off">
					</div>
					<div class="form-group mb-1 text-left">
				      	<label class="">Nomor Penerima / WhatsApp</label>
						<input type="text" class="form-control ft-16" name="ponsel_penerima" value="<?=$rest_val['result']['ponsel_penerima'];?>" required="" autocomplete="off">
					</div>
					<div class="form-group mb-1 text-left">
					    <label class="">Provinsi</label>
                        <?php 
							$arr = array('id' => 'n', 'lang' => 'en');
						    $rest_prov = loadData('rest_load/load_provinsi/', $arr);
						?>

                        <select name='provinsi_id_ex' id='provinsi_id_ex' class='form-control selectpicker' data-live-search="true" title="-- Pilih --" required="">
                        	<?php foreach($rest_prov['result'] as $obj) { 
	                            if($rest_val['result']['id_provinsi']==$obj['province_id']){
	                                $oksip ='selected';
	                            }else{
	                                $oksip ='';
	                            }

                            echo "<option value='".$obj['province_id']."*".$obj['province']."' $oksip>".$obj['province']."</option>";
                        	} ?>
                        </select>
					</div>
					<div class="form-group mb-1 text-left" id="kabupaten_kota_id_ex">
					    <label class="">Kabupaten / Kota</label>
                        <select name='kabkot_id_ex' id='kabupaten_kota_id_eexx' class='form-control selectpicker' data-live-search="true" title="--" required="">
                        </select>
					</div>
					<div class="form-group mb-1 text-left">
				      	<label class="">Kodepos</label>
						<input type="text" class="form-control ft-16" name="kode_pos_ex" id="kode_pos_ex" required="" autocomplete="off" value="<?=$rest_val['result']['kodepos'];?>">
					</div>
					<div class="form-group mb-1 text-left">
				      	<label class="">Alamat Pengiriman</label>
						<textarea type="text" class="form-control ft-16" name="alamat_lengkap" required="" rows="3"><?=$rest_val['result']['alamat_lengkap'];?></textarea>
					</div>
					<div class="mt-4 mb-4">
		               <button type="submit" class="btn btn-primary btn-block">Simpan</button>
			        </div>
				</form>
		    </div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			var tipe_action = '<?=$_GET['tipe']?>';
			var idalamat = '<?=$_GET['idalamat']?>';

			if (tipe_action=='edit'){
				var prov = $('#provinsi_id_ex').val();
                id_prov = prov.split("*");

                $("#kabupaten_kota_id_ex").html('<label class="">Loading...</label>');
                $.get('<?=$main_url;?>module/action.php?jen=load_kabkot&prov_id='+id_prov[0]+'&tipe='+tipe_action+'&idalamat='+idalamat, function(data) {
					$("#kabupaten_kota_id_ex").html(data);
	            });
			}

            $('.selectpicker').selectpicker();

			$('#provinsi_id_ex').change(function(){
                var prov = $('#provinsi_id_ex').val();
                id_prov = prov.split("*");

                $("#kabupaten_kota_id_ex").html('<label class="">Loading...</label>');
                $.get('<?=$main_url;?>module/action.php?jen=load_kabkot&prov_id='+id_prov[0]+'&tipe='+tipe_action+'&idalamat='+idalamat, function(data) {
					$("#kabupaten_kota_id_ex").html(data);
	            });
            });
        });

        function goOptionmyAlamat(){
    		$.confirm({
		        title: 'Confirm!',
		        content: 'Pastikan alamat yang di masukan sudah benar!',
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
		            var formData = new FormData($("#form_option_alamat_akun")[0]);
		            $.ajax({
		              type: "POST",
		              url: '<?=$main_url;?>module/action.php?jen=my_option_alamat_ex',
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

	</script>
<?php } ?>
<?php if ($_GET['jen']=='load_kabkot') {

	$arr = array('tipe' => $_GET['tipe'], 'idalamat' => $_GET['idalamat'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
	$alamat_val = loadData('rest_load/load_alamat_customer/', $arr);

	$arr = array('idkabkot' => $_GET['prov_id'], 'lang' => 'en');
	$rest_val = loadData('rest_load/load_kabkot/', $arr); ?>

	<label class="">Kabupaten / Kota</label>
	<select name='kabkot_id_ex' id='kabupaten_kota_id_eexx' class='form-control selectpicker' data-live-search="true" title="-- Pilih --" required="" onchange="cekKodepos(this.value);">
		<?php 
			foreach($rest_val['result'] as $obj) { 
			if($alamat_val['result']['id_kabkot']==$obj['city_id']){
	            $oksip ='selected';
	        }else{
	            $oksip ='';
	        }
		?>
		<option value="<?=$obj['city_id'].'*'.$obj['city_name'].'*'.$obj['postal_code'];?>" <?=$oksip;?>><?=$obj['type']." ".$obj['city_name'];?></option>
	<?php } ?>
	</select>

	<script type="text/javascript">
		$(document).ready(function(){
            $('.selectpicker').selectpicker();
        });

        function cekKodepos(a){
	      id_kot = a.split("*");
	      $('#kode_pos_ex').val(id_kot[2]);
	    }
	</script>
<?php } ?>
<?php if ($_GET['jen']=='my_option_alamat_ex') {

		$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idalamat' => $_POST['idalamat'], 'tipe' => $_POST['idtipe'], 'label_alamat' => $_POST['label_alamat'], 'nama_penerima' => $_POST['nama_penerima'], 'ponsel_penerima' => $_POST['ponsel_penerima'], 'provinsi_id_ex' => $_POST['provinsi_id_ex'], 'kabkot_id_ex' => $_POST['kabkot_id_ex'], 'kode_pos_ex' => $_POST['kode_pos_ex'], 'alamat_lengkap' => $_POST['alamat_lengkap'], 'lang' => 'en');

		$rest_val = loadData('rest_proses/proses_option_alamat/', $arr);

		if ($rest_val['success']==true) {
			$rest_val['success'] = 'y';
		}else{
			$rest_val['success'] = 'n';
		}

		echo $rest_val['success'].'~'.$rest_val['msg'];
	}
?>
<?php if ($_GET['jen']=='change_my_alamat') {
	$arr = array('tipe' => 'edit', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idalamat' => $_GET['idalamat'], 'lang' => 'en');
    $rest_alamat = loadData('rest_load/load_alamat_customer/',$arr); ?>

	<div class="ft-14 font-weight-bold mb-1">
      <?=$rest_alamat['result']['label_alamat'];?>
      <span class="float-right"><span class="icon-pencil-square-o c-pointer" onclick="actionAlamat('edit','<?=$rest_alamat['result']['cust_det_id']?>')"></span></span>
    </div>
    <div class="ft-14">Penerima : <?=$rest_alamat['result']['nama_penerima'];?></div>
    <div class="ft-14"><?=$rest_alamat['result']['alamat_lengkap'];?>, 
      <span class="text-lowercase"><?=$rest_alamat['result']['nama_provinsi'];?>, <?=$rest_alamat['result']['nama_kabkot'];?> - <?=$rest_alamat['result']['kodepos'];?></span>
    </div>
    <div class="ft-14">Nomor yang dapat di hubungi <?=$rest_alamat['result']['ponsel_penerima'];?></div>
    <input type="hidden" id="idkbakot_id" value="<?=$rest_alamat['result']['id_kabkot']?>">
    <input type="hidden" id="cust_det_id" value="<?=$rest_alamat['result']['cust_det_id']?>">
<?php } ?>
<?php if ($_GET['jen']=='raja_ongkir_cari_kurir') {
	$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'kabkot_id' => $_POST['kabkot_id'], 'kurir_id' => $_POST['kurir_id'], 'lang' => 'en');
    $rest_kurir = loadData('rest_load/load_kurir_cost/',$arr); ?>
  	<div class="">
	    <?php if ($rest_kurir['success']==false){ ?>
	    	<div class="alert alert-primary">cURL Error #:SSL certificate problem: certificate has expired [SSL required]</div>
	    <?php }else{ ?>
	    	<div class="row">
		      <?php foreach($rest_kurir['result'] as $obj) { ?>
		      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
		      	<label class="d-block c-pointer" onclick="selectKurirv2('<?=$obj['kurir_id'];?>','<?=$obj['ongkos_kirim'];?>')">
			        <div class="padding-15 mb-3 border-radius-5 border-d" style="background: #f3f4f5;">
			          <div class="ft-16 font-weight-bold"><?=$obj['nama_kurir']?></div>
			          <div class="ft-16">Service <?=$obj['level_kurir'];?> 
			          	<span class="ft-12 float-right">estimasi <?=$obj['lama_pengiriman'];?> hari</span>
			          </div>
			          <div class="ft-16 pull-right font-weight-bold">
			             <?=formatRupiah($obj['ongkos_kirim'])?>
			          </div>
			          <div class="">
			            <input type="radio" name="kurir_id" style="margin-top: 7px;" value="<?=$obj['kurir_id'];?>" id="ku<?=$hasil['kurir_id'];?>" required="required">
			          </div>
			        </div>
		  		</label>
		      </div>
		      <?php } ?>
	      	<input type="hidden" id="kurir_yg_dipilih_fix" class="form-control">
	    	</div>
		  <?php } ?>
  	</div>
<?php } ?>
<?php if($_GET['jen']=="snap_token_midtrans_topup"){

	    require_once "midtrans/veritrans.php";
	    Veritrans_Config::$serverKey = $rest_sistem['result']['midtrans_serverkey'];
	    // Uncomment for production environment enable if production
	    // Veritrans_Config::$isProduction = true;
	    // Enable sanitization
	    Veritrans_Config::$isSanitized = true;
	    // Enable 3D-Secure
	    Veritrans_Config::$is3ds = true;

	    $uniquecode = 'TOPUP-'.date('Y-m-d-His')."-cstore-xid-".$_SESSION['XID_ARRAY']['cust_id'];

	    // load alamat customer
	    $arr = array('idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en'); 
	    $rest_cust = loadData('rest_load/load_customer/', $arr);

	    $explname = explode(" ", $rest_cust['result']['cust_nama']);
	    $firstname = $explname[0];
	    $lastname = $explname[1];

	    $item_details = array();

	    $item_details[] = array(
	      'id'       => date('Ymdhis').$_SESSION['XID_ARRAY']['cust_id'],
	      'price'    => $_GET['nominalv'],
	      'quantity' => 1,
	      'name'     => " Topup Saldo "
	    );

	    // Fill transaction details
	    $transaction_details = array(
	      'order_id' => $uniquecode,
	      'gross_amount' => $_GET['nominalv'], // no decimal allowed
	    );

	    $customer_details = array(
	      'first_name'    => $firstname, //optional
	      'last_name'     => $lastname, //optional
	      'email'         => $rest_cust['result']['is_token'], //mandatory
	      'phone'         => $rest_cust['result']['cust_ponsel']
	    );

	    // Optional, remove this to display all available payment methods
			$enable_payments = array("credit_card", "gopay", "shopeepay", "permata_va", "bca_va", "bni_va", "bri_va", "echannel", "other_va", "Indomaret", "alfamart");

	    // Fill transaction details
	    $transaction = array(
	      'enabled_payments' => $enable_payments,
	      'transaction_details' => $transaction_details,
	      'customer_details' => $customer_details,
	      'item_details' => $item_details
	    );

	    $snapToken = Veritrans_Snap::getSnapToken($transaction);
	    $result = json_encode(array('snapMidtrans'=>$snapToken, 'uniquecode'=>$uniquecode));
	    echo $result;
  	}
?>
<?php if ($_GET['jen']=='simpan_topup_saldo') {

		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'idunique' => $_POST['idunique'], 
			'snapobj' => $_POST['snapobj'], 
			'statuspay' => $_POST['statuspay'], 
			'nominaltopup' => $_POST['nominaltopup'],
			'lang' => 'en'
		);

	  $rest_val = loadData('rest_proses/proses_topup_saldo/', $arr);

	  if ($rest_val['success']==true) {
	  	$rest_val['success'] = 'y';
	  }else{
	  	$rest_val['success'] = 'n';
	  }

	  echo $rest_val['success'].'~'.$rest_val['msg'].'~'.$rest_val['uid'];
	}
?>
<?php if($_GET['jen']=="snap_token_midtrans"){

	    require_once "midtrans/veritrans.php";
	    Veritrans_Config::$serverKey = $rest_sistem['result']['midtrans_serverkey'];
	    // Uncomment for production environment enable if production
	    // Veritrans_Config::$isProduction = true;
	    // Enable sanitization
	    Veritrans_Config::$isSanitized = true;
	    // Enable 3D-Secure
	    Veritrans_Config::$is3ds = true;

	    $uniquecode = date('Y-m-d-His')."-cstore-xid-".$_SESSION['XID_ARRAY']['cust_id'];

	    // load alamat customer
	    $arr = array('tipe' => 'edit', 'idalamat' => $_GET['idalamat'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en'); $rest_cust = loadData('rest_load/load_alamat_customer/', $arr);

	    // load kurir yang dipilih
			$arr = array('idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idkurir' => $_GET['idkurir'], 'lang' => 'en'); $rest_kurir = loadData('rest_load/load_kurir_pilihan/', $arr);

	    // load cart atau keranjang
			$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
        $rest_cart = loadData('rest_load/load_cart/', $arr);

      $arr = array('kode' => $_GET['kodevoucher'], 'total_bayar' => $rest_cart['total_bayar_num'], 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
	    $rest_voucher = loadData('rest_load/check_voucher/', $arr);

	    $explname = explode(" ", $rest_cust['result']['cust_nama']);
	    $firstname = $explname[0];
	    $lastname = $explname[1];

	    $item_details = array();

	    foreach($rest_cart['result'] as $obj) {
	    	if ($obj['tstok']=='y') {
		      $item_details[] = array(
		        'id'       => $obj['produk_id'],
		        'price'    => $obj['harga_produk_num'],
		        'quantity' => $obj['jumlah_beli'],
		        'name'     => $obj['nama_produk'].' - '.$obj['varian']
		      );
		  	}
	    }

	    $item_details[] = array(
	      'id'       => date('Ymdhis').$_SESSION['XID_ARRAY']['cust_id'],
	      'price'    => $rest_kurir['result']['ongkos_kirim'],
	      'quantity' => 1,
	      'name'     => $rest_kurir['result']['nama_kurir']." - ".$rest_kurir['result']['level_kurir']
	    );

	    if ($rest_voucher['st']=='y') {
		    $item_details[] = array(
		      'id'       => "v-".date('Ymdhis').$_SESSION['XID_ARRAY']['cust_id'],
		      'price'    => -$rest_voucher['nominal'],
		      'quantity' => 1,
		      'name'     => "Potongan Voucher"
		    );
		}

		$gross_amount = is_numeric($rest_cart['total_bayar'])+is_numeric($rest_kurir['result']['ongkos_kirim']);

	    // Fill transaction details
	    $transaction_details = array(
	      'order_id' => $uniquecode,
	      'gross_amount' => $gross_amount, // no decimal allowed
	    );

	    // Optional
	    $billing_address = array(
	      'first_name'    => $firstname,
	      'last_name'     => $lastname,
	      'address'       => $rest_cust['result']['alamat_lengkap'],
	      'city'          => $rest_cust['result']['nama_provinsi']." - ".$rest_cust['result']['nama_kabkot'],
	      'postal_code'   => $rest_cust['result']['kodepos'],
	      'phone'         => $rest_cust['result']['ponsel_penerima'],
	      'country_code'  => 'IDN'
	    );

	    if ($rest_sistem['result']['call_center']!=''){
	    	$callcenter = $rest_sistem['result']['call_center'];
	    }else{
	    	$callcenter = $rest_sistem['result']['whatsapp'];
	    }

	    // Optional
	    $shipping_address = array(
	      'first_name'    => "cStore",
	      'last_name'     => "by Cavellonic",
	      'address'       => "Jln. Perm Ciampea Asri Blok D - Bogor 16620 - Indonesia",
	      'city'          => "Jawa Barat - Bogor",
	      'postal_code'   => "16620",
	      'phone'         => $callcenter,
	      'country_code'  => 'IDN'
	    );

	    $customer_details = array(
	      'first_name'    => $firstname, //optional
	      'last_name'     => $lastname, //optional
	      'email'         => $rest_cust['result']['is_token'], //mandatory
	      'phone'         => $rest_cust['result']['cust_ponsel'], //mandatory
	      'billing_address'  => $billing_address, //optional
	      'shipping_address' => $shipping_address //optional
	    );

	    // Optional, remove this to display all available payment methods
		$enable_payments = array("credit_card", "gopay", "shopeepay", "permata_va", "bca_va", "bni_va", "bri_va", "echannel", "other_va", "Indomaret", "alfamart");

	    // Fill transaction details
	    $transaction = array(
	      'enabled_payments' => $enable_payments,
	      'transaction_details' => $transaction_details,
	      'customer_details' => $customer_details,
	      'item_details' => $item_details
	    );

	    $snapToken = Veritrans_Snap::getSnapToken($transaction);
	    $result = json_encode(array('snapMidtrans'=>$snapToken, 'uniquecode'=>$uniquecode));
	    echo $result;
  	}
?>
<?php if ($_GET['jen']=='simpan_transaksi') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'idalamat' => $_POST['idalamat'], 
			'idkurir' => $_POST['idkurir'], 
			'idunique' => $_POST['idunique'], 
			'snapobj' => $_POST['snapobj'], 
			'statuspay' => $_POST['statuspay'], 
			'kodevoucher' => $_POST['kodevoucher'], 
			'potonganvoucher' => $_POST['potonganvoucher'],
			'metodepembayaran' => $_POST['metodepembayaran'],
			'lang' => 'en'
		);

		if ($_POST['metodepembayaran']=='bank' || $_POST['metodepembayaran']=='saldo') {
			$rest_val = loadData('rest_proses/proses_simpan_transaksi/', $arr);

			if ($rest_val['success']==true) {
				$rest_val['success'] = 'y';
				$_SESSION['trxidunique_xix'] = $rest_val['uid'];
				$_SESSION['trxidunique_msg'] = $rest_val['msg'];
			}else{
				$rest_val['success'] = 'n';
				$_SESSION['trxidunique_msg'] = $rest_val['msg'];
			}

			echo $rest_val['success'].'~'.$rest_val['msg'];
		}else{
			echo 'n~Metode pembayaran tidak sesuai.';
		}
	}
?>
<?php if ($_GET['jen']=='batalkan_transaksi') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'notrx' => $_POST['notrx'], 
			'lang' => 'en'
		);

		$rest_val = loadData('rest_proses/proses_batalkan_transaksi/', $arr);

		if ($rest_val['success']==true) {
			$rest_val['success'] = 'y';
		}else{
			$rest_val['success'] = 'n';
		}

		echo $rest_val['success'].'~'.$rest_val['msg'];
	}
?>
<?php if ($_GET['jen']=='tiba_transaksi') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'notrx' => $_POST['notrx'], 
			'lang' => 'en'
		);

		$rest_val = loadData('rest_proses/proses_tiba_transaksi/', $arr);

		if ($rest_val['success']==true) {
			$rest_val['success'] = 'y';
		}else{
			$rest_val['success'] = 'n';
		}

		echo $rest_val['success'].'~'.$rest_val['msg'];
	}
?>
<?php if ($_GET['jen']=='kirim_bukti_bayar') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'notrx' => $_POST['no_transaksi'], 
			'idbank' => $_POST['bank_id'], 
			'gambar' => $_FILES['gambar']['name'], 
			'gambar_tmp' => $_FILES['gambar']['tmp_name'], 
			'gambar_size' => $_FILES['gambar']['size'],
			'lang' => 'en'
		);

		$rest_val = loadData('rest_proses/proses_kirim_bukti_bayar/', $arr);

		if ($rest_val['success']==true) {
			$rest_val['success'] = 'y';
		}else{
			$rest_val['success'] = 'n';
		}
		
		$_SESSION['pesanbukti'] = $rest_val['msg'];

		echo $rest_val['success'].'~'.$rest_val['msg'];
	}
?>
<?php if ($_GET['jen']=='kirim_bukti_bayar_topup') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'notrx' => $_POST['no_transaksi'], 
			'idbank' => $_POST['bank_id'], 
			'gambar' => $_FILES['gambar']['name'], 
			'gambar_tmp' => $_FILES['gambar']['tmp_name'], 
			'gambar_size' => $_FILES['gambar']['size'],
			'lang' => 'en'
		);

		$rest_val = loadData('rest_proses/proses_kirim_bukti_bayar_topup/', $arr);

		if ($rest_val['success']==true) {
			$rest_val['success'] = 'y';
		}else{
			$rest_val['success'] = 'n';
		}
		
		$_SESSION['pesanbukti'] = $rest_val['msg'];

		echo $rest_val['success'].'~'.$rest_val['msg'];
	}
?>
<?php if ($_GET['jen']=='cek_resi') { 
	$arr = array('tipe' => 'web', 'noresi' => $_GET['resi'], 'kurir' => $_GET['kurir'], 'lang' => 'en');
    $rest_trx = loadData('rest_load/load_cek_resi/',$arr); ?>
  	<div class="">
        <div class="bg-putih">
            <div class="border-bottom1 mb-2 pb-3">
                <div class="ft-12 color-semidark">Nomor Resi</div>
                <div class="ft-16 color-dark font-weight-bold"><?=$rest_trx['result']['query']['waybill'];?></div>
            </div>
            <div class="">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-5 mb-2">
                        <div class="">
                            <div class="ft-12 color-semidark">Tanggal Pengiriman</div>
                            <div class="ft-16 color-dark"><?=indo($rest_trx['result']['result']['summary']['waybill_date']);?></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-7 mb-2">
                        <div class="">
                            <div class="ft-12 color-semidark">Service Code</div>
                            <div class="ft-16 color-dark">
                            	<?=$rest_trx['result']['result']['summary']['courier_name'];?> - <?=$rest_trx['result']['result']['summary']['service_code'];?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2">
                        <div class="">
                            <div class="ft-12 color-semidark">Pembeli & Alamat Pengiriman</div>
                            <div class="ft-16 color-dark">
                                <?=$rest_trx['result']['result']['details']['receiver_name'];?><br>
                            	<?=$rest_trx['result']['result']['summary']['destination'];?> - 
                                <?=$rest_trx['result']['result']['details']['receiver_address1'];?> 
                                <?=$rest_trx['result']['result']['details']['receiver_address2'];?> 
                                <?=$rest_trx['result']['result']['details']['receiver_address3'];?>  
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-5 mb-2">
                        <div class="">
                            <div class="ft-12 color-semidark">Status</div>
                            <div class="ft-16 color-dark">
                                <div class="ft-16 color-app font-weight-bold"><?=$rest_trx['result']['result']['delivery_status']['status'];?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-7 mb-2">
                        <div class="">
                            <div class="ft-12 color-semidark">Diterima Oleh</div>
                            <div class="ft-16 color-dark">
                                <div class="ft-14">
                                	<?=$rest_trx['result']['result']['delivery_status']['pod_receiver'];?><br>
                                	<?=$rest_trx['result']['result']['delivery_status']['pod_date'];?>&nbsp;&nbsp;<?=$rest_trx['result']['result']['delivery_status']['pod_time'];?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-bottom1 mb-3"></div>

                <div class="mb-3">
                    <div class="">
                    	<?php 
                    		$arr_manifest = $rest_trx['result']['result']['manifest'];
                    		$nol = count($rest_trx['result']['result']['manifest']);
                    		$inol = $nol-1;
                    		for ($x = 1; $x <= $nol; $x++) { 
                    	?>
                        <div class="<?php if($nol-$x==$inol) echo 'tanda0'; else echo 'tandamore';?>">
                        	<div class="pb-2">
	                            <div class="ft-14 font-weight-bold mb-1">
	                                <span class="color-dark font-weight-bold"><?=indo($arr_manifest[$nol-$x]['manifest_date']);?></span>
	                                <span class="float-right color-semidark font-weight-400"><?=$arr_manifest[$nol-$x]['manifest_time'];?></span>
	                            </div>
	                            <div class="ft-14 color-semidark"><?=$arr_manifest[$nol-$x]['manifest_description'];?> - <?=$arr_manifest[$nol-$x]['city_name'];?></div>
	                        </div>
                        </div>
	                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($_GET['jen']=='ulasan_transaksi') {
	$arr = array('tipe' => 'web', 'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'idtrx' => $_GET['idtrx'], 'lang' => 'en');
    $rest_trx = loadData('rest_load/load_ulasan_transaksi/',$arr); ?>
  	<div class="pb-5">
	    <div class="row">
	      <?php 
	      	foreach($rest_trx['result'] as $obj) {

	      		if($obj['rating_produk']!='' || $obj['ulasan_produk']!=''){
	                $iz = "readonly='true'";
	            }else{
	                $iz = "";
	            }
	      ?>
	      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-left mb-1">
	      	<div class="form-group">
	      		<label class="text-left mb-0"><?=$obj['nama_produk'];?> ( <?=$obj['varian'];?> )</label>
	      		<?php if($rest_sistem['result']['metode_rating']!='off'){ ?>
				<div class="rat_produk_trx mb-2 text-left c-pointer">
					<?php if ($iz=='') { ?>
						<?php for ($i=1; $i < 6; $i++) { ?>
						<span class="ft-20" id="rat_trx_star<?=$i.$obj['transaksi_det_id'];?>" onmouseover="iRating(<?=$i;?>,<?=$obj['transaksi_det_id'];?>)"><i class="fa fa-star"></i></span>
						<?php } ?>
					<?php }else{ ?>
						<?php for ($i=1; $i < 6; $i++) { ?>
						<?php if ($obj['rating_produk']>=$i) $colorrat = 'color-warning'; else $colorrat = ''; ?>
						<span class="ft-20 <?=$colorrat;?>"><i class="fa fa-star"></i></span>
						<?php } ?>
					<?php } ?>
				</div>
				<?php }else{ echo '<div class="mb-2"></div>'; } ?>
				<input type="hidden" id="ididrating<?=$obj['transaksi_det_id'];?>" value="5" class="form-control">
	      		<?php if($rest_sistem['result']['metode_ulasan']!='off'){ ?>
		      	<div class="input-group">
					<input type="text" <?=$iz;?> id="ididulasan<?=$obj['transaksi_det_id'];?>" value="<?=$obj['ulasan_produk'];?>" class="form-control" placeholder="Ulasan...">
				  	<div class="input-group-append classclassidbutton<?=$obj['transaksi_det_id'];?>">
				  		<?php if ($iz=='') { ?>
				    		<button class="btn btn-app" type="button" onclick="simpanUlasan('<?=$obj['transaksi_det_id'];?>',ididulasan<?=$obj['transaksi_det_id'];?>.value,ididrating<?=$obj['transaksi_det_id'];?>.value)"><span class="icon-check"></span></button>
				    	<?php } ?>
				  	</div>
				</div>
				<?php }else{ ?>
				  	<?php if ($iz=='') { ?>
					  	<div class="classclassidbutton<?=$obj['transaksi_det_id'];?>">
							<button class="btn btn-app btn-sm" type="button" onclick="simpanUlasan('<?=$obj['transaksi_det_id'];?>','',ididrating<?=$obj['transaksi_det_id'];?>.value)"><span class="icon-check"></span></button>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
	      </div>
	      <?php } ?>
	    </div>
  	</div>
<?php } ?>
<?php if ($_GET['jen']=='semua_ulasan_produk') {
	$arr = array('tipe' => 'all', 'idproduk' => $_GET['idproduk'], 'lang' => 'en');
    $rest_ulasan = loadData('rest_load/load_ulasan_produk/',$arr); ?>
  	<div class="pb-5">
	    <div class="row">
	        <?php foreach($rest_ulasan['result'] as $objp) { ?>
	        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	          <div class="card card-body mb-3">
	            <div class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
	              <div class="mr-hid-mob-3 mb-3 mb-lg-0">
	                <div class="account-div-ulasan_img rounded-circle box-shadow-v1" style="background: url('<?=$main_imgurl;?>profile/<?=$rest_cust['result']['cust_gambar'];?>');"></div>
	              </div>
	              <div class="media-body overflow-hidden w-100">
	                <h6 class="media-title font-weight-semibold mb-0 text-overflow-ellips font-weight-bold">
	                  <?=$objp['cust_nama'];?>
	                </h6>
	                <ul class="list-inline list-inline-dotted mb-0 mb-lg-0 text-overflow-ellips">
	                  <?php if ($rest_sistem['result']['metode_rating']!='off') { ?>
	                  <li class="list-inline-item ft-14"><i class="fa fa-star color-warning"></i>&nbsp;<?=$objp['rating_produk'];?></li>
	                  <?php } ?>
	                  <li class="list-inline-item ft-14 text-muted d-sm-inline">
	                    <?=$objp['varian'];?>
	                  </li>
	                </ul>
	                <p class="mb-1 ft-14 color-semidark"><?=$objp['ulasan_produk'];?></p>
	                <p class="mb-0 ft-12 text-right color-semidark"><?=$objp['tgl_ulasan'];?></p>
	              </div>
	            </div>
	          </div>
	        </div>
	        <?php } ?>
	    </div>
  	</div>
<?php } ?>
<?php if ($_GET['jen']=='simpan_ulasan_rating') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'idtrx' => $_POST['idtrx'], 
			'ulasan' => $_POST['ulasan'], 
			'rating' => $_POST['rating'], 
			'lang' => 'en'
		);
		$rest_val = loadData('rest_proses/proses_simpan_ulasan_rating/', $arr);

		if ($rest_val['success']==true) {
			$rest_val['success'] = 'y';
		}else{
			$rest_val['success'] = 'n';
		}

		echo $rest_val['success'].'~'.$rest_val['msg'];
	}
?>
<?php if ($_GET['jen']=='auto_cek_notifikasi') {
		$arr = array(
			'tipe' => 'web', 
			'idcust' => $_SESSION['XID_ARRAY']['cust_id'], 
			'lang' => 'en'
		);
		$rest_val = loadData('rest_load/load_auto_cek_notifikasi/', $arr);
		echo $rest_val['msg'];
	}
?>