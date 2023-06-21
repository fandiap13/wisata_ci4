<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/laporan/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-header">Pilih Periode</div>
          <div class="card-body bg-white">
            <p class="card-text">
            <form action="<?= site_url('/admin/laporan/laporan-transaksi-offline'); ?>" method="GET" target="_blank">
              <div class="form-group">
                <label for="">Tanggal Awal</label>
                <input type="date" name="tglawal" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Tanggal Akhir</label>
                <input type="date" name="tglakhir" class="form-control" required>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-block btn-success">
                  <i class="fa fa-print"></i> Cetak Laporan
                </button>
              </div>
            </form>
            </p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card text-white bg-primary mb-3">
          <div class="card-header">Laporan Grafik</div>
          <div class="card-body bg-white">
            <!-- cari berdasarkan bulan -->
            <div class="form-group">
              <label for="">Pilih Bulan</label>
              <div class="input-group mb-3">
                <input type="month" id="bulan" class="form-control" value="<?= date('Y-m'); ?>">
                <div class="input-group-append">
                  <button type="button" class="btn btn-sm btn-primary" id="tombolTampil">Tampil</button>
                </div>
              </div>
            </div>
            <div class="viewTampilGrafik"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  function tampilGrafik() {
    $.ajax({
      type: "POST",
      url: "<?= site_url('/admin/laporan/grafikTransaksiOffline'); ?>",
      data: {
        bulan: $('#bulan').val()
      },
      dataType: "json",
      success: function(response) {
        // console.log(response.data);
        if (response.data) {
          $('.viewTampilGrafik').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  $('#tombolTampil').click(function(e) {
    e.preventDefault();
    tampilGrafik();
  });

  $(document).ready(function() {
    tampilGrafik();
  });
</script>

<?= $this->endSection('isi'); ?>