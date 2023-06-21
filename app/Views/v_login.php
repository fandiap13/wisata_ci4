<?= $this->extend('template/auth_template'); ?>

<?= $this->section('heading'); ?>
<a href="<?= site_url('/login'); ?>"><b><?= $title; ?></b></a>
<?= $this->endSection('heading'); ?>

<?= $this->section('isi'); ?>
<div class="card-body login-card-body">
  <form action="<?= base_url('/login/cek-login'); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="input-group mb-3">
      <input type="text" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : ''; ?>" placeholder="Email" name="email" value="<?= old('email'); ?>">
      <div class="input-group-append">
        <div class="input-group-text">
          <span class="fas fa-envelope"></span>
        </div>
      </div>
      <div class="invalid-feedback">
        <?= $validation->getError('email'); ?>
      </div>
    </div>
    <div class="input-group mb-3">
      <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : ''; ?>" placeholder="Password" name="password">
      <div class="input-group-append">
        <div class="input-group-text">
          <span class="fas fa-lock"></span>
        </div>
      </div>
      <div class="invalid-feedback">
        <?= $validation->getError('password'); ?>
      </div>
    </div>
    <div class="md-3">
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </div>
  </form>

  <!-- <div class="social-auth-links text-center mb-3">
    <p>- OR -</p>
    <a href="" class="btn btn-block btn-danger">
      <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
    </a>
  </div> -->
  <!-- /.social-auth-links -->

  <p class="mb-1">
    <a href="<?= site_url('/lupa-password'); ?>">I forgot my password</a>
  </p>
  <p class="mb-1">
    <a href="<?= site_url('/register'); ?>">Register</a>
  </p>
  <p class="mb-1">
    <a href="<?= site_url('/'); ?>">Kembali ke beranda</a>
  </p>
</div>

<?= $this->endSection('isi'); ?>