<div class="container-fluid" id="container-wrapper">
	<div class="row mb-3">
		<div class="col-xl-9 col-lg-10 col-md-12 col-sm-12">
      <!-- Form Basic -->
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-white">Tambah <?=$title;?></h6>
        </div>
        <div class="card-body">
          <?= $this->session->flashdata('message'); ?>
          <form id="tambahform" action="javascript:prosesDefault('produk/tambahProduk/proses?>','tambahform')" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">Kategori<span style="color: red">*</span></label>
                  <select class="form-control selectpicker" data-max-options="3" data-live-search="true" multiple="" title="-- Pilih --" id="kategori_id" name="kategori_id[]" required="" onchange="selectSub();">
                    <?php foreach ($all_kateg as $datarl) : ?>
                    <option value="<?=$datarl['kategori_id'];?>"><?=$datarl['nama_kategori'];?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <div class="form-group" id="result_subkategori_produk">
                  <label for="exampleInputEmail1">Sub Kategori <span class="font-size-12">- opsional</span></label>
                  <select class="form-control" name="kategori_sub_id[]">
                    <option value="">-- Menunggu Kategori --</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <div class="form-group" id="result_subkategori_lv2_produk">
                  <label>Sub Lv2 <span class="font-size-12">- opsional</span></label>
                  <select class="form-control" name="kategori_sub_lv2_id[]">
                    <option value="">-- Menunggu Sub --</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Kode Produk<span style="color: red">*</span></label>
                  <input type="text" name="kode_produk" class="form-control" required="" autocomplete="off">
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Nama Produk<span style="color: red">*</span></label>
                  <input type="text" name="nama_produk" class="form-control" required="" autocomplete="off">
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Harga Produk<span style="color: red">*</span></label>
                  <input type="text" name="harga_produk" class="form-control" required="" autocomplete="off" onkeydown="return angkatOnly(event.key)">
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Berat Produk (gram)<span style="color: red">*</span></label>
                  <input type="text" name="berat_produk" class="form-control" required="" autocomplete="off" onkeydown="return angkatOnly(event.key)">
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Warna <span class="font-size-12">- opsional</span></label>
                  <select class="form-control selectpicker" data-live-search="true" multiple name="warna_id[]" id="warna_id_id" title="-- Default --" onchange="checkWarna(this.value)">
                    <?php foreach ($all_warna as $datarwr) : ?>
                    <option value="<?=$datarwr['warna_id'];?>"><?=$datarwr['nama_warna'];?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group " id="selectpicker-container">
                  <label for="exampleInputEmail1">Ukuran / Spesifikasi <span class="font-size-12">- opsional</span></label>
                  <select class="form-control selectpicker" data-live-search="true" multiple name="ukuranid_i" title="-- Default --" onchange="ukuranSize(this.value)">
                    <?php foreach ($all_ukuran as $dataruk) : ?>
                    <option value="<?=$dataruk['ukuran_id']."~".$dataruk['ukuran_size'];?>"><?=$dataruk['ukuran_size'];?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="row" id="ukuran_size_label_id"></div>
            
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>New Arrival<span style="color: red">*</span></label>
                  <select class="form-control" name="is_new" required="">
                    <option value="n">Tidak</option>
                    <option value="y">Ya, new arrival</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Status Potongan / Diskon<span style="color: red">*</span></label>
                  <select class="form-control" name="potongan_status" required="" onchange="statusPotongan(this.value)">
                    <option value="n">Tidak Aktif</option>
                    <option value="y">Aktif</option>
                  </select>
                </div>
              </div>
            </div>

            <div id="potongan_p_xid" class="d-none">
              <div class="">
                <div class="row" style="background: #ededed; border-radius: 5px; padding: 10px 5px; margin: 5px 0px 10px 0px;">
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                      <label>Nominal Diskon<span style="color: red">*</span></label>
                      <input type="text" name="potongan_diskon" class="form-control" autocomplete="off" onkeydown="return angkatOnly(event.key)">
                    </div>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                      <label>Mulai Berlaku<span style="color: red">*</span></label>
                      <input type="date" name="potongan_mulai" class="form-control" id="startcon">
                    </div>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                      <label>Berakhir<span style="color: red">*</span></label>
                      <input type="date" name="potongan_akhir" class="form-control" id="endcon">
                    </div>
                  </div>
                  <div class="col-xl-12 col-lg-12 font-size-12">
                    Contoh : harga produk 65.000 nominal diskon 15.000 maka harga produk akan menjadi 50.000.
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group editoronly">
                  <label>Deskripsi <span class="font-size-12">- opsional</span></label>
                  <textarea name="keterangan_produk" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Gambar 1<span style="color: red">*</span></label>
                  <input type="file" class="form-control" name="gambar" autocomplete="off" onChange="showImgfileGlobal(this,'targetfileimg_p');">
                </div>
                <center><div id="targetfileimg_p"></div></center> 
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Gambar 2 <span class="font-size-12">- opsional</span></label>
                  <input type="file" class="form-control" name="gambar2" autocomplete="off" onChange="showImgfileGlobal(this,'targetfileimg_p2');">
                </div>
                <center><div id="targetfileimg_p2"></div></center> 
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Gambar 3 <span class="font-size-12">- opsional</span></label>
                  <input type="file" class="form-control" name="gambar3" autocomplete="off" onChange="showImgfileGlobal(this,'targetfileimg_p3');">
                </div>
                <center><div id="targetfileimg_p3"></div></center> 
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Gambar 4 <span class="font-size-12">- opsional</span></label>
                  <input type="file" class="form-control" name="gambar4" autocomplete="off" onChange="showImgfileGlobal(this,'targetfileimg_p4');">
                </div>
                <center><div id="targetfileimg_p4"></div></center> 
              </div>
              <div class="col-xl-12 col-lg-12 mt-5">
                <span class="font-size-12">Ukuran gambar maksimal 2mb, rekomendasi 640*640 pixel.</span>
              </div>
            </div>
            <hr>
            <a href="<?= base_url('produk/produk'); ?>" class="btn btn-light">Kembali</a> &nbsp; atau &nbsp;
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  function selectSub(){
    let res = $('select#kategori_id').val().toString().replace(/,/g, '-');
    $('#result_subkategori_produk').html('Loading....');
    $.get('<?=base_url()?>produk/selectSub/'+res, function(data) {
      // $('#result_subkategori_lv2_produk').html('<label>Sub Lv2 <span class="font-size-12">- opsional</span></label><select class="form-control" name="kategori_sub_lv2_id[]"><option value="">-- Menunggu Sub --</option></select>');
      $('#result_subkategori_produk').html(data);
      selectSub2();
    });
  }

  function selectSub2(){
    let res = $('select#kategori_sub_id').val().toString().replace(/,/g, '-');
    if (res=='') {
      res = 0;
    }
    $('#result_subkategori_lv2_produk').html('Loading....');
    $.get('<?=base_url()?>produk/selectSub2/'+res, function(data) {
      $('#result_subkategori_lv2_produk').html(data);
    });
  }

  function checkWarna(a){
    if (a==1) {
      $('#warna_id_id').selectpicker('deselectAll');
      $('#warna_id_id').selectpicker('toggle');
    }
  }

  function ukuranSize(a){
    let val = a.split("~");
    if (val[0]==1) {
      $('select[name=ukuranid_i]').selectpicker('deselectAll');
      $('select[name=ukuranid_i]').selectpicker('toggle');
      // setTimeout(function(){ $('select[name=ukuranid_i]').val("1~Default"); }, 500);
    }else{
      var atext = '';
      for (let d of $('select[name=ukuranid_i]').val()) {
        var res = d.split("~");
        atext += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-6" style="color:green"><div class="form-group"><label>Tambahan Harga '+res[1]+' <span class="font-size-12">- opsional</span></label><input type="text" name="harga_tambahan_ukuran[]" placeholder="0" class="form-control" autocomplete="off"><input type="hidden" name="ukuran_id[]" value="'+res[0]+'" class="form-control"></div></div>';
      }
      $('#ukuran_size_label_id').html(atext);
    }
  }

  function statusPotongan(a){
    if (a=='n') {
      $('#potongan_p_xid').addClass('d-none');
      $("input[name=potongan_diskon]").prop('required',false);
      $("input[name=potongan_mulai]").prop('required',false);
      $("input[name=potongan_akhir]").prop('required',false);
    }else{
      $('#potongan_p_xid').removeClass('d-none');
      $("input[name=potongan_diskon]").prop('required',true);
      $("input[name=potongan_mulai]").prop('required',true);
      $("input[name=potongan_akhir]").prop('required',true);
    }
  }
</script>