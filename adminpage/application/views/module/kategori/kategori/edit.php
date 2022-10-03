<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?= $this->session->flashdata('message'); ?>
<form id="editform" action="javascript:prosesDefault('kategori/editKategori/<?=$edit['kategori_id'].'/proses'?>','editform')" method="POST" enctype="multipart/form-data">
  <div class="modal-body">
    <div class="row">
      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label>Status<span style="color: red">*</span></label>
          <select class="form-control" name="is_active" required="" onchange="tipeFrame(this.value)">
            <option value="1" <?php if ($edit['is_active']==1) echo 'selected'; ?>>Aktif</option>
            <option value="0" <?php if ($edit['is_active']==0) echo 'selected'; ?>>Tidak Aktif</option>
          </select>
        </div>
      </div>
      <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <div class="form-group">
            <label>Nama Kategori<span style="color: red">*</span></label>
            <input type="text" class="form-control" value="<?=$edit['nama_kategori'];?>" name="nama_kategori" required="" autocomplete="off">
        </div>
      </div>
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
          <label for="exampleInputEmail1">Sub Kategori</label><br>
          <div class="row">
            <?php foreach ($all_sub as $sm) : 
              $check = $this->auth->rowData('m_kategori_det','kategori_sub_id='.$sm['kategori_sub_id'].' AND kategori_id='.$edit['kategori_id']); 
              if(!isset($check['kategori_sub_id'])) $stceck = 0; else $stceck = $check['kategori_sub_id'];
            ?>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label style="margin-bottom: 0px;"><input type="checkbox" name="subrole[]" <?php if($sm['kategori_sub_id']==$stceck){ echo "checked";} ?> value="<?=$sm['kategori_sub_id']?>"> <?=$sm['nama_kategori']?></label><br>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
          <label>Gambar</label>
          <input type="file" class="form-control" name="gambar" autocomplete="off" onChange="showImgfile(this,'edit');">
          <input type="hidden" class="form-control" name="old_gambar" value="<?=$edit['logo_image'];?>">
        </div>
        <span class="font-size-12 mt-1 mb-1">Ukuran gambar maksimal 2mb, rekomendasi 625*315 pixel.</span>
        <center><div id="targetfileimgedit"><img src="<?=$this->config->item("nhub_url");?>assets/uploaded/komponen/<?=$edit["logo_image"];?>" style="height: 140px;" class="rounded" class="img-fluid"></div></center> 
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-primary">&nbsp;Submit&nbsp;</button>
  </div>
</form>