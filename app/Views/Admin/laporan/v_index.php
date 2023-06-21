<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">

    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-6">
        <button type="button" style="padding-top: 50px; padding-bottom: 50px;" class="btn btn-block btn-lg btn-success" onclick="window.location=('/admin/laporan/transaksi-online')">
          <i class="fa fa-file"></i> LAPORAN TRANSAKSI ONLINE
        </button>
      </div>
      <div class="col-lg-6">
        <button type="button" style="padding-top: 50px; padding-bottom: 50px;" class="btn btn-block btn-lg btn-primary" onclick="window.location=('/admin/laporan/transaksi-offline')">
          <i class="fa fa-file"></i> LAPORAN TRANSAKSI OFFLINE
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection('isi'); ?>