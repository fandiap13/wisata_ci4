<?= $this->extend('template/auth_template'); ?>

<?= $this->section('heading'); ?>
<a href="<?= site_url('/lupa-password'); ?>"><b><?= $title; ?></b></a>
<?= $this->endSection('heading'); ?>

<?= $this->section('isi'); ?>
<div class="card-body login-card-body">
  <?php if (session()->getFlashdata('pesan')) { ?>
    <?= session()->getFlashdata('pesan'); ?>
  <?php } ?>
  <form action="<?= site_url('/login/recover-passsword'); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="input-group mb-3">
      <input type="email" class="form-control" placeholder="Email" name="email" value="<?= old('email'); ?>" required>
      <div class="input-group-append">
        <div class="input-group-text">
          <span class="fas fa-envelope"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Request new password</button>
      </div>
      <!-- /.col -->
    </div>
  </form>

  <p class="mt-3 mb-1">
    <a href="<?= site_url('/login'); ?>">Login</a>
  </p>
</div>

<?= $this->endSection('isi'); ?>