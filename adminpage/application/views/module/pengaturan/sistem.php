<div class="container-fluid" id="container-wrapper">
  <div class="row mb-3">
    <div class="col-lg-12">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-white"><?= $title; ?></h6>
        </div>
        <form id="tambahform" action="javascript:prosesDefault('pengaturan/sistem/proses','tambahform')" method="POST">
          <div class="card-body">
          <?= $this->session->flashdata('message'); ?>
            <div id="alertpassproses"></div>
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Diskon Semua Produk<span style="color: red">*</span></label>
                  <input type="text" class="form-control" name="global_diskon" value="<?=$sistem['global_diskon'];?>" autocomplete="off" required="">
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Metode Pembayaran<span style="color: red">*</span></label>
                  <select class="form-control" name="metode_pembayaran" required="">
                    <option value="midtrans" <?php if ($sistem['metode_pembayaran']=='midtrans') echo 'selected'; ?>>Midtrans</option>
                    <option value="manual" <?php if ($sistem['metode_pembayaran']=='manual') echo 'selected'; ?>>Transfer Manual</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Metode Ulasan Produk<span style="color: red">*</span></label>
                  <select class="form-control" name="metode_ulasan" required="">
                    <option value="konfirmasi" <?php if ($sistem['metode_ulasan']=='konfirmasi') echo 'selected'; ?>>Konfirmasi Ulasan</option>
                    <option value="auto" <?php if ($sistem['metode_ulasan']=='auto') echo 'selected'; ?>>Auto Publikasi</option>
                    <option value="off" <?php if ($sistem['metode_ulasan']=='off') echo 'selected'; ?>>Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Metode Rating Produk<span style="color: red">*</span></label>
                  <select class="form-control" name="metode_rating" required="">
                    <option value="konfirmasi" <?php if ($sistem['metode_rating']=='konfirmasi') echo 'selected'; ?>>Konfirmasi Rating</option>
                    <option value="auto" <?php if ($sistem['metode_rating']=='auto') echo 'selected'; ?>>Auto Publikasi</option>
                    <option value="off" <?php if ($sistem['metode_rating']=='off') echo 'selected'; ?>>Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Fitur Saldo & Topup<span style="color: red">*</span></label>
                  <select class="form-control" name="fitur_saldo" required="">
                    <option value="y" <?php if ($sistem['fitur_saldo']=='y') echo 'selected'; ?>>Aktif</option>
                    <option value="n" <?php if ($sistem['fitur_saldo']=='n') echo 'selected'; ?>>Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h6 class="mt-3 mb-3 font-weight-bold">API KEY Google Login</h6>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Client ID<span style="color: red">*</span></label>
                  <input type="text" class="form-control" name="google_client" value="<?=$sistem['google_client'];?>" autocomplete="off" required="">
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Secret ID<span style="color: red">*</span></label>
                  <input type="text" class="form-control" name="google_secret" value="<?=$sistem['google_secret'];?>" autocomplete="off" required="">
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                  <label>Redirect Url<span style="color: red">*</span></label>
                  <input type="text" class="form-control" name="google_redirect" value="<?=$sistem['google_redirect'];?>" autocomplete="off" required="">
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="alert alert-primary">Google key diatas adalah contoh sehingga tidak bisa digunakan di domain atau localhost lain.<br/>Cara mendapatkan google key bisa liat tutorial disini : https://www.dumetschool.com/blog/login-melalui-akun-google-menggunakan-php</div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h6 class="mt-3 mb-3 font-weight-bold">API KEY Midtrans</h6>
              </div>
              <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4">
                <div class="form-group">
                  <label>Tipe&nbsp;Key<span style="color: red">*</span></label>
                  <select class="form-control" name="midtrans_tipekey" required="">
                    <option value="sanbox" <?php if ($sistem['midtrans_tipekey']=='sanbox') echo 'selected'; ?>>Sanbox</option>
                    <option value="production" <?php if ($sistem['midtrans_tipekey']=='production') echo 'selected'; ?>>Production</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-5 col-lg-5 col-md-4 col-sm-4">
                <div class="form-group">
                  <label>Client Key<span style="color: red">*</span></label>
                  <input type="text" class="form-control" name="midtrans_clientkey" value="<?=$sistem['midtrans_clientkey'];?>" autocomplete="off" required="">
                </div>
              </div>
              <div class="col-xl-5 col-lg-5 col-md-4 col-sm-4">
                <div class="form-group">
                  <label>Server Key<span style="color: red">*</span></label>
                  <input type="text" class="form-control" name="midtrans_serverkey" value="<?=$sistem['midtrans_serverkey'];?>" autocomplete="off" required="">
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                Gunakan API KEY Midtrans sesuai tipe yang digunakan.<br/>
                Jika Sanbox gunakan Key Sanbox dan Jika Production gunakan Key Production.<br/>
                Gunakan Client Key : "<span class="color-primary font-weight-bold">SB-Mid-client-dCsnwVpQWvonhARF</span>" sebagai contoh Sanbox.<br/>
                Gunakan Server Key : "<span class="color-primary font-weight-bold">SB-Mid-server-WpBrWxGX6O1vasr6jzFeLW1B</span>" sebagai contoh Sanbox.
              </div>
              <!-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Status Maintance<span style="color: red">*</span></label>
                  <select class="form-control" name="maintance" required="">
                    <option value="y" <?php if ($sistem['maintance']=='y') echo 'selected'; ?>>Yes</option>
                    <option value="n" <?php if ($sistem['maintance']=='n') echo 'selected'; ?>>No</option>
                  </select>
                </div>
              </div> -->
            </div>
          </div>
          <hr>
          <div class="padding-submit">
            <button type="submit" class="btn btn-primary">&nbsp;Submit&nbsp;</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>