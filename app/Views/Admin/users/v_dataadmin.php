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
      <button class="btn btn-primary" id="tambah"><i class="fa fa-plus"></i> Tambah Admin</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="reload()"><i class="fas fa-sync-alt"></i></button>
      <button class="btn btn-info" onclick="window.location = '<?= site_url('/admin/users/admin-deleted'); ?>'"><i class="fas fa-trash-alt"></i> Data admin yg terhapus</button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-striped table-bordered" id="dataadmin" style="width: 100%;">
      <thead>
        <tr>
          <th style="width: 5%;">No</th>
          <th>E - mail</th>
          <th>Nama</th>
          <th>Telp</th>
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
  $('#tambah').click(function(e) {
    e.preventDefault();
    $.ajax({
      url: "<?= site_url('/admin/users/modaltambahadmin'); ?>",
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data);
          $('.viewmodal').show();
          $('#modaltambah').modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  $(document).ready(function() {
    table = $('#dataadmin').DataTable({
      'responsive': true,
      'lengthChange': true,
      'autoWidth': false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian-Alternative.json"
      },
      processing: true,
      serverSide: true,
      ajax: '<?= site_url('/admin/users/dataadmin'); ?>',
      order: [],
      columns: [{
          data: "nomor",
        },
        {
          data: "email_user",
        },
        {
          data: "nama_user"
        },
        {
          data: "telp_user"
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

  const edit = (id) => {
    $.ajax({
      type: "POST",
      url: "<?= site_url('/admin/users/modaleditadmin'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data);
          $('.viewmodal').show();
          $('#modaledit').modal('show');
        }

        if (response.error) {
          Swal.fire('Error', response.error, 'error')
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function hapus(id, nama) {
    Swal.fire({
      title: 'Hapus?',
      text: `Apakah anda yakin ingin menghapus admin dengan nama ${nama}`,
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
          url: "<?= site_url('/admin/users/delete/'); ?>" + id,
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
</script>
<?= $this->endSection('isi'); ?>