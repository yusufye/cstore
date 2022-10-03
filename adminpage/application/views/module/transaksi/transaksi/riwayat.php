<style type="text/css">
    .modal { overflow: auto !important; }
</style>

<div class="container-fluid" id="container-wrapper">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white"><?= $title; ?></h6>
                    <div class="text-right">
                        <a href="<?=base_url('transaksi/download_riwayat_transaksi/'.$tgl_awal.'/'.$tgl_akhir);?>" class="btn btn-primary btn-sm no-hov"><i class="fa fa-download"></i>&nbsp; Download Riwayat</a>
                    </div>
                </div>
                <div class="table-responsive p-3">
                    <form action="<?=base_url('transaksi/riwayat');?>" method="GET">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                <label>Pencarian</label>
                                <div class="input-group mb-3">
                                  <input type="text" name="keyvalue" autocomplete="off" class="form-control" placeholder="No Transaksi" value="<?=$this->input->get('keyvalue');?>" />
                                </div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-8 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <div class="row" style="width: 100% !important;">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Tanggal Mulai</label>
                                                <input type="date" name="tgl_awal" class="form-control" id="startcon" autocomplete="off" value="<?=$tgl_awal;?>" onClick="event.preventDefault()">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label>Tanggal Akhir</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="date" name="tgl_akhir" class="form-control" id="endcon" autocomplete="off" value="<?=$tgl_akhir;?>" onClick="event.preventDefault()">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                        <?php if ($reset=='yes') { ?>
                                                        <a href="<?=base_url('transaksi/riwayat');?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table align-items-center table-flush table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>No Transaksi</th>
                                <th>Tanggal Transaksi</th>
                                <th>Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no = 1; foreach ($all_data as $data) : 
                                if($data['is_status']=='s'){
                                    $st = '&nbsp;Selesai&nbsp;';
                                    $stx = 'success';
                                }else if($data['is_status']=='b'){
                                    $st = '&nbsp;Dibatalkan&nbsp;';
                                    $stx = 'danger';
                                }else{
                                    $st = 'Unknown';
                                    $stx = 'light';
                                }
                            ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $data['no_transaksi'];?></td>
                                <td><?= indo($data['tgl_transaksi']);?></td>
                                <td id="noidtrxidr<?= $data['transaksi_id'];?>"><span class="badge badge-<?=$stx;?>"><?=$st;?></span></td>
                                <td align="right">
                                    <a href="javascript:" onclick="modalBesar('<?=base_url('transaksi/transaksiDetail/1900/').$data['transaksi_id']?>','1900')" class="btn btn-info btn-sm font-size-12">Lihat</a>
                                </td>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                    </table>

                    <div class="mt-3"><?=$pagination;?></div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function downloadRiwayat(a,b) {
        window.location='<?=$this->config->item("nhub_url");?>module/download_riwayat_transaksi.php?mulai='+a+'&akhir='+b;
    }
</script>