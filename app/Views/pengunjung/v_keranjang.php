<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<section class="ftco-section services-section">
  <div class="container">
    <!-- <div class="row justify-content-center pb-4"> -->
    <div class="row">
      <!-- <div class="col-md-12 heading-section text-center ftco-animate">
        <h2 class="mb-4">Keranjangku</h2>
      </div> -->

      <!-- <div class="row"> -->
      <div class="col-md-4">
        <h2 class="mb-4">Data Pengunjung</h2>
        <form action="<?= site_url('/transaksi/checkout'); ?>" method="POST" class="formcheckout">
          <div class="form-group">
            <label for="tgl_kunjungan">Tanggal Kunjungan </label>
            <input type="date" class="form-control" name="tgl_kunjungan" id="tgl_kunjungan">
          </div>
          <div class="form-group">
            <label for="nama_user">Nama </label>
            <input type="text" class="form-control" name="nama_user" id="nama_user" value="<?= $user['nama_user']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="email">E-mail </label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $user['email_user']; ?>">
          </div>
          <div class="form-group">
            <label for="telp">No. Telp / Wa </label>
            <input type="number" class="form-control" name="telp" id="telp" value="<?= $user['telp_user']; ?>">
            <div class="invalid-feedback error_telp"></div>
          </div>
          <div class="form-group">
            <button type="submit" name="simpan" class="btn btn-block btn-primary btnCheckout"><i class="fa fa-save"></i> Checkout</button>
          </div>
        </form>
      </div>

      <div class="col-md-8">
        <h2 class="mb-4">Detail Keranjang</h2>
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="tables" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Wisata</th>
                <th>Kategori Tiket</th>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Harga (Rp)</th>
                <th>Subtotal (Rp)</th>
                <th style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $totalbayar = 0;
              foreach ($keranjang as $k) :
              ?>
                <tr>
                  <td><?= $i; ?>.</td>
                  <td><?= $k['nama_wisata']; ?></td>
                  <td><?= $k['kategori']; ?></td>
                  <td><?= $k['status_tiket'] == 1 ? '<span class="badge badge-success">Tersedia</span>' : '<span class="badge badge-danger">Habis</span>'; ?></td>
                  <td><?= $k['jml']; ?> Tiket</td>
                  <td><?= number_format($k['harga'], 0, ",", "."); ?></td>
                  <td><?= number_format($k['jml'] * $k['harga'], 0, ",", "."); ?></td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $k['id']; ?>')"><i class="fa fa-trash"></i> Hapus</button>
                  </td>
                </tr>
              <?php
                $totalbayar += $k['jml'] * $k['harga'];
                $i++;
              endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="6" class="text-center">Total Bayar (Rp)</th>
                <td colspan="2"><?= number_format($totalbayar, 0, ",", "."); ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- </div> -->
    </div>
  </div>
</section>


<script>
  function hapus(id) {
    Swal.fire({
      title: 'Hapus',
      text: "Apakah anda yakin menghapus item ini ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Tidak!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "delete",
          url: `<?= site_url('/transaksi/hapusTemp'); ?>/${id}`,
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  }

  $('.formcheckout').submit(function(e) {
    e.preventDefault();

    let tgl_kunjungan = $('#tgl_kunjungan').val();
    let email = $('#email').val();
    let telp = $('#telp').val();

    if (!tgl_kunjungan || !email || !telp) {
      Swal.fire('Error', 'Semua inputan harus terisi !', 'error');
      return false;
    }

    if (!telp.match(/62[0-9]{11,12}/gs)) {
      $('#telp').addClass('is-invalid');
      $('.error_telp').html("No.Telp/Wa tidak valid [contoh: 6285293444555]");
      return false;
    } else {
      $('#telp').removeClass('is-invalid');
      $('.error_telp').html('');
    }

    Swal.fire({
      title: 'Checkout',
      text: "Apakah anda yakin melakukan checkout ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, lakukan!',
      cancelButtonText: 'Tidak'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: $(this).attr('method'),
          url: $(this).attr('action'),
          data: $(this).serialize(),
          dataType: "json",
          beforeSend: () => {
            $('.btnCheckout').html('<i class="fa fa-spinner fa-spin"></i>');
            $('.btnCheckout').attr('disabled', true);
          },
          complete: () => {
            $('.btnCheckout').html('<i class="fa fa-save"></i> Checkout');
            $('.btnCheckout').removeAttr('disabled');
          },
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success').then(() => window.location = "<?= site_url('/'); ?>");
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  });

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
  });
</script>
<?= $this->endSection('isi'); ?>