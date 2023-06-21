<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <button class="btn btn-primary" id="tambah" onclick="window.location = '<?= site_url('/admin/blog/tambah-postingan'); ?>'"><i class="fa fa-plus"></i> Tambah Postingan</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
      <button class="btn btn-info" onclick="window.location = '<?= site_url('/admin/blog/deleted-blog'); ?>'"><i class="fas fa-trash-alt"></i> Data postingan yg terhapus</button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-9">
        <select name="status" id="status" class="form-control">
          <option value="">-- Pilih status blog --</option>
          <option value="2">Draf</option>
          <option value="1">Dipublikasi</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="button" class="btn btn-block btn-primary" id="tombolcari">
          Tampilkan
        </button>
      </div>
    </div>

    <br><br>

    <table class="table table-striped table-bordered" id="datablog" style="width: 100%;">
      <thead>
        <tr>
          <th style="width: 5%;">No</th>
          <th>Judul</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Status</th>
          <th style="width: 15%;">Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<div class="viewmodal" style="display: none;"></div>

<script>
  // $('#status').change(function(e) {
  //   e.preventDefault();
  //   console.log($('#status').val())
  // });

  $(document).ready(function() {
    table = $('#datablog').DataTable({
      'responsive': true,
      'lengthChange': true,
      'autoWidth': false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian-Alternative.json"
      },
      processing: true,
      serverSide: true,
      ajax: {
        url: '<?= site_url('/admin/blog/datablog'); ?>',
        data: function(e) {
          e.status = $('#status').val();
        }
      },
      order: [],
      columns: [{
          data: "nomor",
        },
        {
          data: "judul",
        },
        {
          data: 'created_at'
        },
        {
          data: 'updated_at'
        },
        {
          data: "status_blog",
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

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: `Apakah anda yakin ingin menghapus postingan ini ?`,
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
          url: "<?= site_url('/admin/blog/delete/'); ?>" + id,
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              Swal.fire('Berhasil', response.sukses, 'success');
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

  function edit(id) {
    window.location = `<?= site_url('/admin/blog/edit_postingan/'); ?>${id}`;
  }

  function lihat(id) {
    window.open(`<?= site_url('/blog/'); ?>${id}`, '_blank');
  }

  $('#tombolcari').click(function(e) {
    e.preventDefault();
    table.ajax.reload();
  });
</script>
<?= $this->endSection('isi'); ?>