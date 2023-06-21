<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>

<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<link rel="stylesheet" href="<?= base_url(); ?>/lightbox/dist/css/lightbox.min.css">
<script src="<?= base_url(); ?>/lightbox/dist/js/lightbox-plus-jquery.min.js"></script>

<script src="<?= base_url(); ?>/js/qrcode.min.js"></script>


<section class="ftco-section services-section">
  <div class="container">
    <!-- <div class="row justify-content-center pb-4"> -->
    <div class="row">
      <!-- <div class="col-md-12 heading-section text-center ftco-animate">
        <h2 class="mb-4">Keranjangku</h2>
      </div> -->

      <!-- <div class="row"> -->
      <div class="col-md-4">
        <h2 class="mb-4">Data Transaksi</h2>
        <ul class="list-group">
          <li class="list-group-item"><b>ID Transaksi :</b> <?= $transaksi['id']; ?></li>
          <li class="list-group-item"><b>Tanggal Transaksi :</b> <?= date('d F Y H:i:s', strtotime($transaksi['tgl_transaksi'])); ?></li>
          <li class="list-group-item"><b>Tanggal Kunjungan :</b> <?= date('d F Y', strtotime($transaksi['tgl_kunjungan'])); ?></li>

          <?php if ($transaksi['tgl_bayar'] !== NULL) : ?>
            <li class="list-group-item"><b>Tanggal Pembayaran :</b> <?= date('d F Y H:i:s', strtotime($transaksi['tgl_bayar'])); ?></li>
          <?php endif; ?>

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

          <?php if ($transaksi['status'] != 3 && $transaksi['status'] != 4) : ?>
            <li class="list-group-item">Upload Pembayaran : <button type="button" class="btn btn-sm btn-info" onclick="pembayaran('<?= $transaksi['id']; ?>')"><i class="fa fa-paypal"></i> Pembayaran</button></li>
          <?php endif; ?>

          <?php if ($transaksi['buktipembayaran'] !== NULL && $transaksi['buktipembayaran'] != "") : ?>
            <li class="list-group-item"><b>Bukti Pembayaran :</b>
              <a href="<?= base_url($transaksi['buktipembayaran']); ?>" data-lightbox="image-1" data-title="Bukti Pembayaran">
                <img src="<?= base_url($transaksi['buktipembayaran']); ?>" alt="Bukti Pembayaran" class="img-preview" style="width: 100%;">
              </a>
            </li>
          <?php endif; ?>

          <!-- <?php if ($transaksi['status'] == 3) : ?>
            <li class="list-group-item ">QR Code :
              <div class="d-flex justify-content-center align-items-center w-100">
                <div id="canvas"></div>
              </div>
            </li>
          <?php endif; ?> -->
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
</section>

<div class="viewmodal" style="display: none;"></div>

<script>
  function pembayaran(id) {
    $.ajax({
      type: "post",
      url: "<?= site_url('/transaksi/modalpembayaran'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').show();
          $('.viewmodal').html(response.data);
          $('#modalpembayaran').modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  // var qrcode = new QRCode(document.getElementById("canvas"), {
  //   text: '<?= $transaksi['id']; ?>',
  //   width: 200,
  //   height: 200,
  //   colorDark: "#000",
  //   colorLight: "#fff",
  //   correctLevel: QRCode.CorrectLevel.H
  // });

  var area = document.getElementById('canvas');
  new QRCode(area, "<?= site_url('/admin/transaksionline/detail-transaksi/' . $transaksi['id']); ?>");

  // function genQR(qr) {
  //   qrcode.makeCode(qr);
  // }

  $(document).ready(function() {
    $('#tables').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian-Alternative.json"
      },
    });

    // genQR();
  });
</script>
<?= $this->endSection('isi'); ?>