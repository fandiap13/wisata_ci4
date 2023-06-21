<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>
<section class="ftco-section services-section">
  <div class="container">
    <!-- <div class="row justify-content-center pb-4"> -->
    <div class="row">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <h2 class="mb-4">Profile User</h2>
      </div>

      <!-- <div class="row"> -->
      <div class="col-md-4">
        <h4>Ubah Password</h4>
        <form action="<?= site_url('/ubah-password'); ?>" method="POST" class="formubahpassword">
          <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" id="password">
            <div class="invalid-feedback error_password"></div>
          </div>
          <div class="form-group">
            <label for="">Retype Password</label>
            <input type="retype_password" name="retype_password" class="form-control" id="retype_password">
            <div class="invalid-feedback error_retype_password"></div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary btnUbahPass"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>

      <div class="col-md-8">
        <h4>Data User</h4>
        <form action="<?= site_url('/ubah-profile'); ?>" method="POST" class="formsimpan">
          <div class="form-group">
            <label for="nama_user">Nama </label>
            <input type="text" class="form-control" name="nama_user" id="nama_user" value="<?= $user['nama_user']; ?>">
            <div class="invalid-feedback error_nama_user"></div>
          </div>
          <div class="form-group">
            <label for="email">E-mail </label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $user['email_user']; ?>">
            <div class="invalid-feedback error_email"></div>
          </div>
          <div class="form-group">
            <label for="telp">No. Telp / Wa </label>
            <input type="number" class="form-control" name="telp" id="telp" value="<?= $user['telp_user']; ?>">
            <div class="invalid-feedback error_telp"></div>
          </div>
          <div class="form-group">
            <button type="submit" name="simpan" class="btn btn-block btn-primary btnSimpan"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>


<script>
  $('.formsimpan').submit(function(e) {
    e.preventDefault();

    let telp = $('#telp').val();
    if (!telp.match(/62[0-9]{11,12}/gs)) {
      $('#telp').addClass('is-invalid');
      $('.error_telp').html("No.Telp/Wa tidak valid [contoh: 6285293444555]");
      return false;
    } else {
      $('#telp').removeClass('is-invalid');
      $('.error_telp').html('');
    }

    Swal.fire({
      title: 'Checkout',
      text: "Apakah anda yakin mengubah data profile ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, lakukan!',
      cancelButtonText: 'Tidak'
    }).then((result) => {
      if (result.isConfirmed) {
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
              Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
            }
            if (response.errors) {
              if (response.errors.nama_user) {
                $('#nama_user').addClass('is-invalid');
                $('.error_nama_user').html(response.errors.nama_user);
              } else {
                $('#nama_user').removeClass('is-invalid');
                $('.error_nama_user').html('');
              }
              if (response.errors.email) {
                $('#email').addClass('is-invalid');
                $('.error_email').html(response.errors.email);
              } else {
                $('#email').removeClass('is-invalid');
                $('.error_email').html('');
              }
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
    return false;
  });

  $('.formubahpassword').submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Checkout',
      text: "Apakah anda yakin mengubah password ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, lakukan!',
      cancelButtonText: 'Tidak'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: $(this).attr('method'),
          url: $(this).attr('action'),
          data: $(this).serialize(),
          dataType: "json",
          beforeSend: () => {
            $('.btnUbahPass').html('<i class="fa fa-spinner fa-spin"></i>');
            $('.btnUbahPass').attr('disabled', true);
          },
          complete: () => {
            $('.btnUbahPass').html('<i class="fa fa-save"></i> Simpan Perubahan');
            $('.btnUbahPass').removeAttr('disabled');
          },
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
            }
            if (response.errors) {
              if (response.errors.password) {
                $('#password').addClass('is-invalid');
                $('.error_password').html(response.errors.password);
              } else {
                $('#password').removeClass('is-invalid');
                $('.error_password').html('');
              }
              if (response.errors.retype_password) {
                $('#retype_password').addClass('is-invalid');
                $('.error_retype_password').html(response.errors.retype_password);
              } else {
                $('#retype_password').removeClass('is-invalid');
                $('.error_retype_password').html('');
              }
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
    return false;
  });
</script>
<?= $this->endSection('isi'); ?>