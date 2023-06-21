<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <button class="btn btn-primary" id="tambah" onclick="window.location = '<?= site_url('/admin/transaksioffline/tambah-transaksi'); ?>'"><i class="fa fa-plus"></i> Tambah Transaksi</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <label for="tglawal">Tanggal Mulai</label>
        <input type="date" name="tglawal" id="tglawal" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="tglakhir">Tanggal Selesai</label>
        <input type="date" name="tglakhir" id="tglakhir" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="">&nbsp;</label>
        <button type="button" class="btn btn-block btn-primary" id="tombolcari">
          Tampilkan
        </button>
      </div>
    </div>

    <br><br>

    <table class="table table-striped table-bordered" id="datatransaksi" style="width: 100%;">
      <thead>
        <tr>
          <th style="width: 5%;">No</th>
          <th>ID Transaksi</th>
          <th>Tanggal Transaksi</th>
          <th>Pembeli</th>
          <th>Kasir</th>
          <th>Total Bayar (Rp)</th>
          <th style="width: 15%;">Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>


<script>
  $(document).ready(function() {
    table = $('#datatransaksi').DataTable({
      'responsive': true,
      'lengthChange': true,
      'autoWidth': false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian-Alternative.json"
      },
      processing: true,
      serverSide: true,
      ajax: {
        url: '<?= site_url('/admin/transaksioffline/datatransaksi'); ?>',
        data: function(e) {
          e.tglawal = $('#tglawal').val();
          e.tglakhir = $('#tglakhir').val();
        }
      },
      order: [],
      columns: [{
          data: "nomor",
        },
        {
          data: "id",
        },
        {
          data: "created_at",
        },
        {
          data: 'nama_pembeli'
        },
        {
          data: 'nama_kasir'
        },
        {
          data: 'totalbayar'
        },
        {
          data: "aksi",
          orderable: false
        },
      ]
    });
  });

  function reload() {
    table.ajax.reload();
  }

  function cetak(id) {
    let windowCetak = window.open(`<?= site_url('/admin/transaksioffline/cetak-transaksi/'); ?>${id}`, "Cetak Transaksi Offline", "width=300,height=500");
    windowCetak.focus();
  }

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: `Apakah anda yakin ingin menghapus transaksi dengan ID ${id} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus !',
      cancelButtonText: 'tidak',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "delete",
          url: "<?= site_url('/admin/transaksioffline/delete/'); ?>" + id,
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success');
              reload();
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error');
              reload();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  }

  $('#tombolcari').click(function(e) {
    e.preventDefault();
    reload();
  });
</script>
<?= $this->endSection('isi'); ?>