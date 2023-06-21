<?= $this->extend('template/auth_template'); ?>

<?= $this->section('heading'); ?>
<a href="<?= site_url('/registrasi'); ?>"><b><?= $title; ?></b></a>
<?= $this->endSection('heading'); ?>

<?= $this->section('isi'); ?>
<div class="card-body register-card-body">
  <?= session()->getFlashdata('pesan'); ?>

  <form action="<?= site_url('/login/save_register'); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="input-group mb-3">
      <input type="text" class="form-control <?= $validation->hasError('nama') ? 'is-invalid' : ''; ?>" placeholder="Full name" name="nama" value="<?= old('nama'); ?>">
      <div class="input-group-append">
        <div class="input-group-text">
          <span class="fas fa-user"></span>
        </div>
      </div>
      <div class="invalid-feedback">
        <?= $validation->getError('nama'); ?>
      </div>
    </div>
    <div class="input-group mb-3">
      <input type="email" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : ''; ?>" placeholder="Email" name="email" value="<?= old('email'); ?>">
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
    <div class="input-group mb-3">
      <input type="password" class="form-control <?= $validation->hasError('retype_password') ? 'is-invalid' : ''; ?>" placeholder="Retype password" name="retype_password">
      <div class="input-group-append">
        <div class="input-group-text">
          <span class="fas fa-lock"></span>
        </div>
      </div>
      <div class="invalid-feedback">
        <?= $validation->getError('retype_password'); ?>
      </div>
    </div>

    <div class="md-5 mb-2">
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </div>
  </form>

  <a href="<?= site_url('/login'); ?>" class="text-center">I already have an account</a>
</div>

<?= $this->endSection('isi'); ?>