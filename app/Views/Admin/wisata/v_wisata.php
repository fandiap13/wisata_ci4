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
      <button class="btn btn-primary" id="tambah" onclick="window.location = '<?= site_url('/admin/wisata/tambah'); ?>'"><i class="fa fa-plus"></i> Tambah Wisata</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-info" onclick="window.location = '<?= site_url('/admin/wisata/deleted-wisata'); ?>'"><i class="fas fa-trash-alt"></i> Data wisata yg terhapus</button>
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-striped table-bordered" id="tables" style="width: 100%;">
      <thead>
        <tr>
          <th style="width: 5%;">No</th>
          <th>Nama Wisata</th>
          <th>Status</th>
          <th style="width: 15%;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        foreach ($data as $key => $value) : ?>
          <tr>
            <td><?= $i; ?>. </td>
            <td><?= $value['nama_wisata']; ?></td>
            <td><?= $value['status'] == 0 ? '<span class="badge badge-danger">Draf</span>' : '<span class="badge badge-success">Dipublikasi</span>'; ?></td>
            <td>
              <button type="button" class="btn btn-sm btn-primary" onclick="edit('<?= $value['id']; ?>')"><i class='fa fa-edit'></i> Edit</button>
              <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $value['id']; ?>')"><i class='fa fa-trash-alt'></i> Hapus</button>
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

<div class="viewmodal" style="display: none;"></div>

<script>
  $(document).ready(function() {
    $('#tables').DataTable({
      "paging": false,
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

  function edit(id) {
    window.location = `<?= site_url('/admin/wisata/edit/'); ?>${id}`
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
          url: "<?= site_url('/admin/wisata/delete/'); ?>" + id,
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