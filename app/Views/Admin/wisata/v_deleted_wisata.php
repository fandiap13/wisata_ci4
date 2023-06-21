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
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/wisata/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-striped table-bordered" id="tables" style="width: 100%;">
      <thead>
        <tr>
          <th style="width: 5%;">No</th>
          <th>Nama Wisata</th>
          <th style="width: 25%;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        foreach ($data as $key => $value) : ?>
          <tr>
            <td><?= $i; ?>. </td>
            <td><?= $value['nama_wisata']; ?></td>
            <td>
              <button type="button" class="btn btn-sm btn-warning" onclick="recover('<?= $value['id']; ?>')"><i class="fas fa-redo-alt"></i> Recover</button>
              <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $value['id']; ?>')"><i class='fa fa-trash-alt'></i> Hapus Permanen</button>
            </td>
          </tr>
        <?php
          $i++;
        endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  $(document).ready(function() {
    $('#tables').DataTable({
      "paging": false,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian-Alternative.json"
      },
    });
  });

  function recover(id) {
    Swal.fire({
      title: 'Hapus?',
      text: `Apakah anda yakin ingin recover data wisata ini ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus !',
      cancelButtonText: 'Tidak',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "get",
          url: `<?= site_url('/admin/wisata/recover/'); ?>${id}`,
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

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: `Apakah anda yakin ingin menghapus wisata ini ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus !',
      cancelButtonText: 'Tidak',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "delete",
          url: "<?= site_url('/admin/wisata/forceDelete/'); ?>" + id,
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
</script>
<?= $this->endSection('isi'); ?>