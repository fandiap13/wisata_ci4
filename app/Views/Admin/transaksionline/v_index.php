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

    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3">
        <label for="tglawal">Tanggal Mulai</label>
        <input type="date" name="tglawal" id="tglawal" class="form-control">
      </div>
      <div class="col-md-3">
        <label for="tglakhir">Tanggal Selesai</label>
        <input type="date" name="tglakhir" id="tglakhir" class="form-control">
      </div>
      <div class="col-md-3">
        <label for="status">Status Transaksi</label>
        <select name="status" id="status" class="form-control">
          <option value="">-- Pilih status --</option>
          <option value="2">Diperoses</option>
          <option value="3">Berhasil</option>
          <option value="4">Gagal</option>
        </select>
      </div>
      <div class="col-md-3">
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
          <th>Tanggal Kunjungan</th>
          <th>Tanggal Pembayaran</th>
          <th>Pembeli</th>
          <th>Total Bayar (Rp)</th>
          <th>Status</th>
          <th style="width: 10%;">Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="viewmodal" style="display: none;"></div>

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
        url: '<?= site_url('/admin/TransaksiOnline/datatransaksi'); ?>',
        data: function(e) {
          e.tglawal = $('#tglawal').val();
          e.tglakhir = $('#tglakhir').val();
          e.status = $('#status').val();
        }
      },
      order: [],
      columns: [{
          data: "nomor",
          orderable: false
        },
        {
          data: "id",
        },
        {
          data: "tgl_transaksi",
        },
        {
          data: "tgl_kunjungan",
        },
        {
          data: "tgl_bayar",
        },
        {
          data: 'nama_user'
        },
        {
          data: 'totalbayar'
        },
        {
          data: 'status',
          orderable: false
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

  $('#tombolcari').click(function(e) {
    e.preventDefault();
    reload();
  });

  function pembayaran(id) {
    $.ajax({
      type: "POST",
      url: "<?= site_url('/admin/TransaksiOnline/pembayaran'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data).show();
          $('#modalpembayaran').modal('show');
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

  function detail(id) {
    window.location = `<?= site_url('/admin/TransaksiOnline/detail-transaksi/'); ?>${id}`;
  }

  function cetak(id) {
    let windowCetak = window.open(`<?= site_url('/admin/TransaksiOnline/cetak-transaksi/'); ?>${id}`, "Cetak Transaksi Online", "width=300,height=500");
    windowCetak.focus();
  }

  // setInterval(() => {
  //   reload();
  // }, 60000);
</script>
<?= $this->endSection('isi'); ?>