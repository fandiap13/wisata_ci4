<?= $this->extend('template/auth_template'); ?>

<?= $this->section('heading'); ?>
<a href=""><b><?= $title; ?></b></a>
<?= $this->endSection('heading'); ?>

<?= $this->section('isi'); ?>
<div class="card-body login-card-body">
  <?php if (session()->getFlashdata('msg')) { ?>
    <?= session()->getFlashdata('msg'); ?>
  <?php } ?>
  <form action="<?= site_url('/login/simpan-ubah-password'); ?>" method="post">
    <?= csrf_field(); ?>
    <input type="hidden" name="email_user" value="<?= $email; ?>">
    <input type="hidden" name="token_ganti_pass" value="<?= $token; ?>">
    <div class="input-group mb-3">
      <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : ''; ?>" name="password" placeholder="Password">
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
      <input type="password" class="form-control <?= $validation->hasError('retype_password') ? 'is-invalid' : ''; ?>" name="retype_password" placeholder="Confirm Password">
      <div class="input-group-append">
        <div class="input-group-text">
          <span class="fas fa-lock"></span>
        </div>
      </div>
      <div class="invalid-feedback">
        <?= $validation->getError('retype_password'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Change password</button>
      </div>
      <!-- /.col -->
    </div>
  </form>

  <p class="mt-3 mb-1">
    <a href="<?= site_url('/login'); ?>">Login</a>
  </p>
</div>
<?= $this->endSection('isi'); ?>