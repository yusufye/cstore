<?php include "module/module.php"; ?>
<!doctype html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?=$rest_sistem['result']['meta_description'];?>">
    <meta name="keywords" content="<?=$rest_sistem['result']['meta_keywords'];?>">

    <meta property="og:title" content="Kategori - <?=$rest_sistem['result']['meta_title'];?>">
    <meta property="og:description" content="<?=$rest_sistem['result']['meta_description'];?>">
    <meta property="og:image" content="<?=$main_imgurl;?>logo/<?=$rest_sistem['result']['logo_toko_image'];?>">
    <meta property="og:url" content="<?=$main_url;?>/categories">
    
    <?php include "module/include/style.php"; ?>

    <title>Kategori - <?=$rest_sistem['result']['meta_title'];?></title>
  </head>
  <body>

    <?php include "module/include/header.php"; ?>
    

    <section class="bg-container-2 mt-4 mb-3">
      <div class="row justify-content-center mb-30">
        <div class="col-xl-10 col-lg-10 col-md-11">
          <div class="section-title ft-28 text-center">
            &mdash; Kategori &mdash;
          </div>
        </div>
      </div>

      <?php $arr = array('opsi' => 'i', 'lang' => 'en');
        $rest_kategori = loadData('rest_load/load_kategori/',$arr); ?>

      <?php
        if ($rest_sistem['result']['ui_kategori']==3) { 
          include 'module/include/categories_lv3.php';
        }else if ($rest_sistem['result']['ui_kategori']==2) {
          include 'module/include/categories_lv2.php';
        }else{
          include 'module/include/categories_lv1.php';
        }
      ?>
    </section>

    <?php include "module/include/footer.php"; ?>
    
    <?php include "module/include/javascript.php"; ?>

  </body>
</html>