    <footer class="<?=$rest_sistem['result']['ui_footer'];?> footer" id="footer_app_inav">
      <div class="footer__addr">
        <h1 class="footer__logo"><img src="<?=$main_imgurl;?>logo/<?=$rest_sistem['result']['logo_toko_image'];?>" width="120"></h1>
        <h2 class="nav__title mt-3 mb-3">Stay Tuned</h2>
        <div class="stay_tuned_footer">
          <?php if ($rest_sistem['result']['facebook']!=''){ ?>
          <a href="<?=$rest_sistem['result']['facebook'];?>" target="_blank" class="mr-2"><span class="icon-facebook"></span></a>
          <?php } ?>
          <?php if ($rest_sistem['result']['instagram']!=''){ ?>
          <a href="<?=$rest_sistem['result']['instagram'];?>" target="_blank" class="mr-2"><span class="icon-instagram"></span></a>
          <?php } ?>
          <?php if ($rest_sistem['result']['whatsapp']!=''){ ?>
          <a href="https://api.whatsapp.com/send?phone=<?=$rest_sistem['result']['whatsapp'];?>&amp;text=" target="_blank" class="mr-2"><span class="icon-whatsapp"></span></a>
          <?php } ?>
          <?php if ($rest_sistem['result']['call_center']!=''){ ?>
          <a href="tel:<?=$rest_sistem['result']['call_center'];?>" target="_blank" class="mr-2"><span class="icon-phone"></span></a>
          <?php } ?>
          <?php if ($rest_sistem['result']['email_address']!=''){ ?>
          <a href="mailto:<?=$rest_sistem['result']['email_address'];?>"><span class="icon-envelope"></span></a>
          <?php } ?>
        </div>
        <div class="mt-3" style="white-space: pre-line;">
          <?=$rest_sistem['result']['label_footer'];?>
        </div>
      </div>
  
      <ul class="footer__nav">
        <li class="nav__item mb-3">
          <h2 class="nav__title">Halaman</h2>
          <ul class="nav__ul">
            <li><a href="<?=$main_url;?>about-us">Tentang Kami</a></li>
            <li><a href="<?=$main_url;?>contact-us">Kontak Kami</a></li>
            <li><a href="<?=$main_url;?>terms-conditions">Syarat dan Ketentuan</a></li>
            <li><a href="<?=$main_url;?>privacy-policy">Kebijakan Privasi</a></li>
          </ul>
          <div class="mt-3 mb-2 hidden-891">
            <h2 class="nav__title">Layanan Pembayaran</h2>
            <img src="<?=$main_url;?>assets/images/paypment_image-v3.png" width="240" class="rounded-2 mt-2">
          </div>
        </li>
        <li class="nav__item mb-3">
          <h2 class="nav__title d-none d-sm-inline">&nbsp;</h2>
          <ul class="nav__ul">
            <li><a href="<?=$main_url;?>categories">Kategori</a></li>
            <li><a href="<?=$main_url;?>best-seller">Best Seller <sup><span class="badge badge-danger">Hot</span></sup></a></li>
            <li><a href="<?=$main_url;?>new-arrivals">New Arrivals</a></li>
            <li>&nbsp;</li>
          </ul>
          <div class="mt-3 mb-2 visible-891">
            <h2 class="nav__title">Layanan Pembayaran</h2>
            <img src="<?=$main_url;?>assets/images/paypment_image-v3.png" width="240" class="rounded-2 mt-2">
          </div>
          <div class="mt-3 mb-2">
            <h2 class="nav__title">Layanan Pengiriman</h2>
            <img src="<?=$main_url;?>assets/images/kurir_image.png" width="240" class="rounded-2 mt-2">
          </div>
        </li>
        <li class="nav__item mb-3">
          <h2 class="nav__title">Download App</h2>
          <ul class="nav__ul">
            <li><a href="javascript:myModalDownloadapp()">Google</a></li>
            <li><a href="javascript:myModalDownloadapp()">iOS</a></li>
          </ul>
          <div class="mt-3">
            <h2 class="nav__title">Keamanan & Privasi</h2>
            <img src="<?=$main_url;?>assets/images/secure_logo.png" width="220">
          </div>
        </li>
      </ul>
  
      <div class="legal">
        <p><?=$rest_sistem['result']['footer'];?> Powered by <a href="https://carvellonic.com" target="_blank">Carvellonic</a></p>
      </div>
    </footer>