<div class="modal" id="modaltambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Pengunjung</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('/admin/users/simpanpengunjung'); ?>" method="POST" class="formsimpan">
        <?= csrf_field(); ?>
        <div class="modal-body">
          <div class="form-group">
            <label for="email_user" class="form-label">Email</label>
            <input type="email" name="email_user" class="form-control" placeholder="Email...">
            <div class="invalid-feedback error_email_user"></div>
          </div>
          <div class="form-group">
            <label for="nama_user" class="form-label">Nama</label>
            <input type="text" name="nama_user" class="form-control" placeholder="Nama...">
            <div class="invalid-feedback error_nama_user"></div>
          </div>
          <div class="form-group">
            <label for="telp_user" class="form-label">Telp</label>
            <input type="number" name="telp_user" class="form-control" placeholder="Nama...">
            <div class="invalid-feedback error_telp_user"></div>
          </div>
          <div class="form-group">
            <label for="password_user" class="form-label">Password</label>
            <input type="password" name="password_user" class="form-control" placeholder="Password...">
            <div class="invalid-feedback error_password_user"></div>
          </div>
          <div class="form-group">
            <label for="retype_password" class="form-label">Retype password</label>
            <input type="password" name="retype_password" class="form-control" placeholder="Retype password...">
            <div class="invalid-feedback error_retype_password"></div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary btnSimpan">Simpan</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
  $('.formsimpan').submit(function(e) {
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
        $('.btnSimpan').html('Simpan');
        $('.btnSimpan').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('success', response.success, 'success');
          $('#modaltambah').modal('hide');
          reload();
        }
        if (response.error) {
          Swal.fire('error', response.error, 'error');
          $('#modaltambah').modal('hide');
          reload();
        }
        if (response.errors) {
          if (response.errors.email_user) {
            $('.error_email_user').html(response.errors.email_user);
            $('input[name=email_user]').addClass('is-invalid');
          } else {
            $('input[name=email_user]').removeClass('is-invalid');
            $('.error_email_user').html('');
          }
          if (response.errors.nama_user) {
            $('.error_nama_user').html(response.errors.nama_user);
            $('input[name=nama_user]').addClass('is-invalid');
          } else {
            $('input[name=error_nama_user]').removeClass('is-invalid');
            $('.error_error_nama_user').html('');
          }
          if (response.errors.password_user) {
            $('.error_password_user').html(response.errors.password_user);
            $('input[name=password_user]').addClass('is-invalid');
          } else {
            $('input[name=password_user]').removeClass('is-invalid');
            $('.error_password_user').html('');
          }
          if (response.errors.retype_password) {
            $('.error_retype_password').html(response.errors.retype_password);
            $('input[name=retype_password]').addClass('is-invalid');
          } else {
            $('input[name=retype_password]').removeClass('is-invalid');
            $('.error_retype_password').html('');
          }
          if (response.errors.telp_user) {
            $('.error_telp_user').html(response.errors.telp_user);
            $('input[name=telp_user]').addClass('is-invalid');
          } else {
            $('input[name=telp_user]').removeClass('is-invalid');
            $('.error_telp_user').html('');
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });
</script>