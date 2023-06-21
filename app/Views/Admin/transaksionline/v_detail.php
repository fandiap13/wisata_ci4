<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/lightbox/dist/css/lightbox.min.css">
<script src="<?= base_url(); ?>/lightbox/dist/js/lightbox-plus-jquery.min.js"></script>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/TransaksiOnline/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <h2 class="mb-4">Data Transaksi</h2>
        <ul class="list-group">
          <li class="list-group-item"><b>ID Transaksi :</b> <?= $transaksi['id']; ?></li>
          <li class="list-group-item"><b>Tanggal Transaksi :</b> <?= date('d M Y H:i:s', strtotime($transaksi['tgl_transaksi'])); ?></li>
          <li class="list-group-item"><b>Tanggal Kunjungan :</b> <?= date('d M Y', strtotime($transaksi['tgl_kunjungan'])); ?></li>
          <li class="list-group-item"><b>Tanggal Pembayaran :</b> <?= date('d M Y H:i:s', strtotime($transaksi['tgl_bayar'])); ?></li>
          <li class="list-group-item"><b>Pengunjung :</b> <?= $transaksi['nama_user']; ?></li>
          <li class="list-group-item"><b>E-mail :</b> <?= $transaksi['email']; ?></li>
          <li class="list-group-item"><b>No. Telp / wa :</b> <?= $transaksi['telp']; ?></li>
          <li class="list-group-item"><b>Status :</b>
            <?php if ($transaksi['status'] == 1) { ?>
              <span class="badge badge-warning">Belum Bayar</span>
            <?php } elseif ($transaksi['status'] == 2) { ?>
              <span class="badge badge-info">Proses</span>
            <?php } elseif ($transaksi['status'] == 3) { ?>
              <span class="badge badge-success">Berhasil</span>
            <?php } else { ?>
              <span class="badge badge-danger">Gagal</span>
            <?php } ?>
          </li>

          <?php if ($transaksi['buktipembayaran'] !== NULL && $transaksi['buktipembayaran'] != "") : ?>
            <li class="list-group-item"><b>Bukti Pembayaran :</b>
              <a href="<?= base_url($transaksi['buktipembayaran']); ?>" data-lightbox="image-1" data-title="Bukti Pembayaran">
                <img src="<?= base_url($transaksi['buktipembayaran']); ?>" alt="Bukti Pembayaran" class="img-preview" style="width: 100%;">
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="col-md-8">
        <h2 class="mb-4">Detail Transaksi</h2>
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="tables" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Wisata</th>
                <th>Kategori Tiket</th>
                <th>Jumlah</th>
                <th>Harga (Rp)</th>
                <th>Subtotal (Rp)</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $totalbayar = 0;
              foreach ($detail as $k) :
              ?>
                <tr>
                  <td><?= $i; ?>.</td>
                  <td><?= $k['nama_wisata']; ?></td>
                  <td><?= $k['kategori']; ?></td>
                  <td><?= $k['jml']; ?> Tiket</td>
                  <td><?= number_format($k['harga_tiket'], 0, ",", "."); ?></td>
                  <td><?= number_format($k['jml'] * $k['harga_tiket'], 0, ",", "."); ?></td>
                </tr>
              <?php
                $totalbayar += $k['jml'] * $k['harga_tiket'];
                $i++;
              endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" class="text-center">Total Bayar (Rp)</th>
                <td colspan="1"><?= number_format($totalbayar, 0, ",", "."); ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- </div> -->
    </div>
  </div>
</div>

<script>
  function reload() {
    window.location.reload();
  }
</script>

<?= $this->endSection('isi'); ?>