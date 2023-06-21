<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">

  <script src="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <?= $this->renderSection('heading'); ?>
    </div>
    <div class="card">
      <?= $this->renderSection('isi'); ?>
    </div>
  </div>

  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>

  <script>
    $(document).ready(function() {
      <?php
      $alert = session()->getFlashdata('msg');
      if (!empty($alert)) {
        $alert = explode('#', $alert);
        $icon = $alert[0];
        $title = $alert[1];
      ?>
        Swal.fire({
          position: 'top-end',
          toast: true,
          icon: '<?= $icon; ?>',
          title: '<?= $title; ?>',
          showConfirmButton: false,
          timer: 5000
        })
      <?php } ?>
    });
  </script>
</body>

</html>