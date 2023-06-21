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
    <div class="row justify-content-center pb-4">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <h2 class="mb-4">Daftar Transaksi</h2>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tables" style="width: 100%;">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>ID</th>
              <th>Tgl Transaksi</th>
              <th>Tgl Kunjungan</th>
              <th>Status Transaksi</th>
              <th>Total Bayar (Rp)</th>
              <th style="width: 15%;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $i = 1;
            foreach ($transaksi as $value) :
            ?>
              <tr>
                <td><?= $i; ?>. </td>
                <td><?= $value['id']; ?></td>
                <td><?= date('d F Y H:i:s', strtotime($value['tgl_transaksi'])); ?></td>
                <td><?= date('d F Y', strtotime($value['tgl_kunjungan'])); ?></td>
                <td class="text-center">
                  <?php if ($value['status'] == 1) { ?>
                    <span class="badge badge-warning">Belum Bayar</span>
                  <?php } elseif ($value['status'] == 2) { ?>
                    <span class="badge badge-info">Proses</span>
                  <?php } elseif ($value['status'] == 3) { ?>
                    <span class="badge badge-success">Berhasil</span>
                  <?php } else { ?>
                    <span class="badge badge-danger">Gagal</span>
                  <?php } ?>
                </td>
                <td class="text-right">
                  <?php
                  $total = 0;
                  $db = \Config\Database::connect();
                  $data = $db->table('detail_transaksi_online')->where('transonlineid', $value['id'])->get()->getResultArray();
                  foreach ($data as $key => $d) {
                    $total += $d['jml'] * $d['harga_tiket'];
                  }
                  ?>
                  <?= number_format($total, 0, ",", "."); ?>
                </td>
                <td>
                  <center>
                    <a href="<?= site_url('/transaksi/detail-transaksi/' . $value['id']); ?>" class="d-block mb-2 btn btn-sm btn-primary"><i class="fa fa-eye"></i> Detail</a>

                    <?php if ($value['status'] == 3) : ?>
                      <a href="<?= site_url('/transaksi/cetak-transaksi/' . $value['id']); ?>" class="d-block mb-2 btn btn-sm btn-success" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                    <?php endif; ?>

                    <?php if ($value['status'] != 3 && $value['status'] != 2) : ?>
                      <button type="button" class="mb-2 btn btn-block btn-sm btn-danger" onclick="hapus('<?= $value['id']; ?>')"><i class="fa fa-trash"></i> Hapus</button>
                    <?php endif; ?>

                    <?php if ($value['status'] != 3 && $value['status'] != 4) { ?>
                      <button type="button" class="btn btn-block btn-sm btn-info" onclick="pembayaran('<?= $value['id']; ?>')"><i class="fa fa-paypal"></i> Pembayaran</button>
                    <?php } ?>
                  </center>
                </td>
              </tr>
            <?php
              $i++;
            endforeach; ?>
          </tbody>
        </table>
      </div>
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
          // $('#modalpembayaran').on('shown.bs.modal')
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function hapus(id) {
    Swal.fire({
      title: 'Hapus',
      text: `Apakah anda yakin menghapus transaksi dengan ID ${id} ?`,
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
          url: `<?= site_url('/transaksi/hapusTransaksi'); ?>/${id}`,
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


  $(document).ready(function() {
    $('#tables').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
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