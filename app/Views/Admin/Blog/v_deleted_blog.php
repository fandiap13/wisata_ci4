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
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/blog/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
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

<script>
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
      ajax: '<?= site_url('/admin/blog/deleted_datablog'); ?>',
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
          data: "status",
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

  function recover(id) {
    Swal.fire({
      title: 'Recover ?',
      text: `Apakah anda yakin ingin memulihkan postingan ini ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, pulihkan !',
      cancelButtonText: 'tidak',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= site_url('/admin/blog/recover'); ?>",
          data: {
            id: id
          },
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

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: `Apakah anda yakin ingin menghapus postingan ini secara permanen ?`,
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
          url: "<?= site_url('/admin/blog/forceDeleted/'); ?>" + id,
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
</script>

<?= $this->endSection('isi'); ?>