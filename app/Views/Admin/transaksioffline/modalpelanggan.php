<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>


<div class="modal" id="modalpelanggan">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Data Pelanggan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered" id="datapengunjung" style="width: 100%;">
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
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
  $(document).ready(function() {
    table = $('#datapengunjung').DataTable({
      'responsive': true,
      'lengthChange': true,
      'autoWidth': false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian-Alternative.json"
      },
      processing: true,
      serverSide: true,
      ajax: '<?= site_url('/admin/users/datapelanggan'); ?>',
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

  function pilih(id, nama) {
    $('#pengunjungid').val(id);
    $('#namapelanggan').val(nama);
    $('#modalpelanggan').modal('hide');
  }
</script>