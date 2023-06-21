<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?= $title; ?></h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h4>New Orders</h4>

              <h4><?= $jmlOrder; ?></h4>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?= site_url('/admin/transaksionline/index'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h4>Total Pemasukan</h4>

              <h4>Rp. <?= number_format($totalOnline + $totalOffline, 0, ",", "."); ?></h4>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill"></i>
            </div>
            <a href="<?= site_url('/admin/laporan/index'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.card -->

<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Profile User</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="container-fluid">
      <form action="<?= site_url('/admin/profile/ubah-profile'); ?>" method="POST" class="formubahprofile">
        <?= csrf_field(); ?>
        <div class="form-group">
          <label for="">E-mail User</label>
          <input type="email" name="email_user" class="form-control" value="<?= $user['email_user']; ?>">
          <div class="invalid-feedback error_email_user"></div>
        </div>
        <div class="form-group">
          <label for="">Nama User</label>
          <input type="text" name="nama_user" class="form-control" value="<?= $user['nama_user']; ?>">
          <div class="invalid-feedback error_nama_user"></div>
        </div>
        <div class="form-group">
          <label for="">Telp User</label>
          <input type="number" name="telp_user" class="form-control" value="<?= $user['telp_user']; ?>">
          <div class="invalid-feedback error_telp_user"></div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="">Password User</label>
              <input type="password" name="password_user" class="form-control">
              <div class="invalid-feedback error_password_user"></div>
            </div>
            <div class="col-md-6">
              <label for="">Retype Password</label>
              <input type="password" name="retype_password" class="form-control">
              <div class="invalid-feedback error_retype_password"></div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary btnKirim"><i class="fa fa-save"></i> Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.card -->

<script>
  $('.formubahprofile').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: () => {
        $('.btnKirim').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnKirim').attr('disabled', true);
      },
      complete: () => {
        $('.btnKirim').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('.btnKirim').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
        }

        if (response.errors) {
          if (response.errors.email_user) {
            $('input[name=email_user]').addClass('is-invalid');
            $('.error_email_user').html(response.errors.email_user);
          } else {
            $('input[name=email_user]').removeClass('is-invalid');
            $('.error_email_user').html('');
          }
          if (response.errors.nama_user) {
            $('input[name=nama_user]').addClass('is-invalid');
            $('.error_nama_user').html(response.errors.nama_user);
          } else {
            $('input[name=nama_user]').removeClass('is-invalid');
            $('.error_nama_user').html('');
          }
          if (response.errors.telp_user) {
            $('input[name=telp_user]').addClass('is-invalid');
            $('.error_telp_user').html(response.errors.telp_user);
          } else {
            $('input[name=telp_user]').removeClass('is-invalid');
            $('.error_telp_user').html('');
          }
          if (response.errors.password_user) {
            $('input[name=password_user]').addClass('is-invalid');
            $('.error_password_user').html(response.errors.password_user);
          } else {
            $('input[name=password_user]').removeClass('is-invalid');
            $('.error_password_user').html('');
          }
          if (response.errors.retype_password) {
            $('input[name=retype_password]').addClass('is-invalid');
            $('.error_retype_password').html(response.errors.retype_password);
          } else {
            $('input[name=retype_password]').removeClass('is-invalid');
            $('.error_retype_password').html('');
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });
</script>
<?= $this->endSection('isi'); ?>