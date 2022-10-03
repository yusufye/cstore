<div class="container-fluid" id="container-wrapper">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-white"><?= $title; ?></h6>
                    <div class="text-right">
                        <a href="" class="btn btn-primary btn-sm no-hov" data-toggle="modal" data-target="#modalDataKpilihan"><i class="fa fa-star"></i>&nbsp; Kategori Pilihan</a>
                        <a href="" class="btn btn-primary btn-sm no-hov" data-toggle="modal" data-target="#modalData"><i class="fa fa-plus"></i>&nbsp; Tambah</a>
                    </div>
                </div>
                <div class="table-responsive p-3 middle-tabel">
                    <?= $this->session->flashdata('message'); ?>
                    <table class="table align-items-center table-flush table-hover" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th>&nbsp;</th>
                                <th>Sub</th>
                                <th width="18%">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no=1; foreach ($all_data as $data) : 
                                if ($data['is_active']==1) {
                                    $lbl = 'Aktif';
                                    $badge = 'badge-success';
                                }else{
                                    $lbl = 'Tidak aktif';
                                    $badge = 'badge-primary';
                                }

                                $checksub = $this->auth->resultData('m_kategori_det a JOIN m_kategori_sub b ON a.kategori_sub_id=b.kategori_sub_id','a.kategori_id='.$data['kategori_id'],'*','b.nama_kategori ASC');
                            ?>
                            <tr>
                                <td><?=$no;?></td>
                                <td><img src='<?=$this->config->item("nhub_url");?>assets/uploaded/komponen/<?=$data["logo_image"];?>' height='50' class="rounded"></td>
                                <td>
                                    <?= $data['nama_kategori'];?><br/>
                                    <span class="badge <?=$badge;?>"><?=$lbl;?></span>
                                </td>
                                <td>
                                    <div class="row">
                                        <?php $nox=1; foreach ($checksub as $sdata) : ?>
                                        <div class="col-xl-6 col-lg-6 font-size-12">
                                            <?=$nox;?>. <?=$sdata['nama_kategori'];?>
                                        </div>
                                        <?php $nox++; endforeach; ?>
                                    </div>
                                </td>
                                <td align="right">
                                    <a href="javascript:prosesHapus('kategori/hapusKategori/<?=$data['kategori_id'];?>');" class="btn btn-danger btn-sm font-size-12"><i class="fa fa-trash"></i></a>
                                    <a href="#" onclick="modalNormal('<?=base_url('kategori/editKategori/').$data['kategori_id']?>')" class="btn btn-warning btn-sm font-size-12">Edit</a>
                                </td>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahform" action="javascript:prosesDefault('kategori/kategori/proses','tambahform')" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nama Kategori<span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="nama_kategori" required="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Sub Kategori</label><br>
                      <div class="row">
                        <?php foreach ($all_sub as $sm) : ?>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label style="margin-bottom: 0px;"><input type="checkbox" name="subrole[]" value="<?=$sm['kategori_sub_id']?>"> <?=$sm['nama_kategori']?></label><br>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>Gambar<span style="color: red">*</span></label>
                        <input type="file" class="form-control" name="gambar" required="" autocomplete="off" onchange="showImgfile(this);">
                        <center><div id="targetfileimg" style="margin-top: 15px;"></div></center>
                    </div>
                    <div class="mt-3">
                        <span class="font-size-12">Ukuran gambar maksimal 2mb, rekomendasi 625*315 pixel.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">&nbsp;Submit&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDataKpilihan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kategori Pilihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahformx" action="javascript:prosesDefault('kategori/pilihanKategori','tambahformx')" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-max-options="5" data-live-search="true" multiple="" title="-- Pilih --" name="kategori_id[]" required="">
                        <?php foreach ($all_data as $datarl) : ?>
                        <?php $is = $this->auth->getById('ui_kategori','kategori_id',$datarl['kategori_id']) ?>
                        <option value="<?=$datarl['kategori_id'];?>" <?php if ($is) echo 'selected'; ?>><?=$datarl['nama_kategori'];?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mt-1">
                        <span class="font-size-12">Maksimal 5.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">&nbsp;Submit&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>