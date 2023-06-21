<div class="modal" id="modalpembayaran">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Pembayaran</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('/admin/TransaksiOnline/simpanTransaksi'); ?>" method="POST" class="formpembayaran">
        <?= csrf_field(); ?>
        <div class="modal-body">
          <div class="form-group">
            <label for="">ID Transaksi</label>
            <input type="text" name="id" id="id" class="form-control" value="<?= $transaksi['id']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="">Bukti Pembayaran</label>
            <a href="<?= base_url($transaksi['buktipembayaran']); ?>" target="_blank" data-title="Bukti Pembayaran">
              <img src="<?= base_url($transaksi['buktipembayaran']); ?>" alt="Bukti Pembayaran" class="img-thumbnail img-preview d-block" style="width: 200px;">
            </a>

          </div>
          <div class="form-group">
            <label for="">Ubah Status Transaksi</label>
            <select name="status" id="status" class="form-control">
              <option value="2" <?= $transaksi['status'] == 2 ? 'selected' : ''; ?>>Diperoses</option>
              <option value="3" <?= $transaksi['status'] == 3 ? 'selected' : ''; ?>>Berhasil</option>
              <option value="4" <?= $transaksi['status'] == 4 ? 'selected' : ''; ?>>Gagal</option>
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success btnSimpan"><i class="fa fa-save"></i> Simpan Perubahan</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
  $('.formpembayaran').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: () => {
        $('.btnSimpan').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnSimpan').attr('disabled', true);
      },
      complete: () => {
        $('.btnSimpan').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('.btnSimpan').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success');
          $('#modalpembayaran').modal('hide');
          reload();
          getNotif();
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error');
          $('#modalpembayaran').modal('hide');
          reload();
          getNotif();
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });
</script>