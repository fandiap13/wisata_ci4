<?php

$request = \Config\Services::request();
$uri1 = empty($request->uri->getSegment(2)) ? "" : $request->uri->getSegment(2);
$uri2 = empty($request->uri->getSegment(3)) ? "" : $request->uri->getSegment(3);

$db = \Config\Database::connect();
$setting = $db->table('setting')->select('nama_web, favicon')->get()->getRowArray();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | <?= $title; ?></title>

  <link rel="icon" type="image/x-icon" href="<?= base_url($setting['favicon']); ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">

  <!-- jQuery -->
  <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>

  <!-- sweetalert -->
  <script src="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <div class="dropdownNotif">
          <!-- Notifications Dropdown Menu -->
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge"></span>
            </a>
          </li>
        </div>
        <li class="nav-item">
          <a href="" class="nav-link text-danger keluar" role="button" title="Keluar">
            <i class="fas fa-sign-out-alt"></i> Keluar
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url("/admin/dashboard/index"); ?>" class="brand-link">
        <img src="<?= base_url($setting['favicon']); ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $setting['nama_web']; ?></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image text-white h4">
            <i class="fa fa-user nav-icon ml-1"></i>
          </div>
          <div class="info">
            <a href="" class="d-block"><?= session('LoggedUserData')['name']; ?></a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->

        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= site_url('/'); ?>" class="nav-link">
                <i class="nav-icon fas fa-globe"></i>
                <p>
                  Buka Website
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= site_url('/admin/dashboard'); ?>" class="nav-link <?= $uri1 == 'dashboard' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-item <?= $uri1 == 'users' ? 'menu-is-opening menu-open' : ''; ?>">
              <a href="" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Data User
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('/admin/users/admin'); ?>" class="nav-link <?= $uri2 == 'admin' ? 'active' : ''; ?>">
                    <i class="fa fa-user-tie nav-icon"></i>
                    <p>Admin</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('/admin/users/pengunjung'); ?>" class="nav-link <?= $uri2 == 'pengunjung' ? 'active' : ''; ?>">
                    <i class="fa fa-user nav-icon"></i>
                    <p>Pengunjung</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="<?= site_url('/admin/wisata/index'); ?>" class="nav-link <?= $uri1 == 'wisata' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-location-arrow"></i>
                <p>
                  Data Wisata
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= site_url('/admin/blog/index'); ?>" class="nav-link <?= $uri1 == 'blog' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-newspaper"></i>
                <p>
                  Blog
                </p>
              </a>
            </li>

            <li class="nav-item <?= $uri1 == 'transaksioffline' || $uri1 == "transaksionline" ? 'menu-is-opening menu-open' : ''; ?>">
              <a href="" class="nav-link">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                  Transaksi Tiket
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('/admin/transaksioffline/index'); ?>" class="nav-link <?= $uri1 == 'transaksioffline' ? 'active' : ''; ?>">
                    <i class="fa fa-cash-register nav-icon"></i>
                    <p>Transaksi Offline</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('/admin/transaksionline/index'); ?>" class="nav-link <?= $uri1 == 'transaksionline' ? 'active' : ''; ?>">
                    <i class="fa fa-credit-card nav-icon"></i>
                    <p>Transaksi Online</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="<?= site_url('/admin/laporan/index'); ?>" class="nav-link <?= $uri1 == 'laporan' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-print"></i>
                <p>
                  Laporan
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= site_url('/admin/setting/index'); ?>" class="nav-link <?= $uri1 == 'setting' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                  Setting Web
                </p>
              </a>
            </li>


            <!-- <li class="nav-item">
              <a href="../widgets.html" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  Widgets
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="" class="nav-link keluar">
                <i class="nav-icon fa fa-sign-out-alt"></i>
                <p>
                  Keluar
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?= $title; ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= site_url('/dashboard'); ?>">Home</a></li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <?= $this->renderSection('isi'); ?>

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.1.0-rc
      </div>
      <strong>
        <!-- Copyright &copy; <?= date('Y'); ?> <a href="<?= site_url('/'); ?>"><?= $setting['nama_web']; ?></a>.</strong> All rights reserved | -->
        <a href="<?= site_url('/'); ?>"><?= $setting['nama_web']; ?></a>.
      </strong>
      Developed By <strong><a href="https://github.com/fandiap13" target="_blank" class="modalDeveloped">Fandi Aziz Pratama</a></strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= base_url(); ?>/dist/js/demo.js"></script>

  <script>
    function getNotif() {
      $.ajax({
        url: "<?= site_url('/admin/transaksionline/getNotif'); ?>",
        dataType: "json",
        success: function(response) {
          if (response.data) {
            $('.dropdownNotif').html(response.data);
          }
          if (response.error) {
            $('.dropdownNotif').html(`<li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge"></span>
            </a>
          </li>`);
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          // alert(xhr.status + '\n' + thrownError);
          Swal.fire('Error', 'User sedang tidak login !', 'error').then(() => window.location.reload());
        }
      });
    }

    $(document).ready(function() {
      getNotif();

      setInterval(() => {
        getNotif();
      }, 10000);

      // showing alert
      <?php $alert = session()->getFlashData("msg") ?>
      <?php if (!empty($alert)) : ?>
        <?php $alert = explode("#", $alert) ?>
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 5000
        });
        setTimeout(function() {
          Toast.fire({
            icon: "<?php echo $alert[0] ?>",
            title: "<?php echo $alert[1] ?>"
          });
        }, 1000);
      <?php endif ?>

      $('.keluar').click(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Keluar ?',
          text: "Apakah anda yakin ingin keluar ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, keluar !',
          cancelButtonText: 'tidak'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location = "<?= site_url('/keluar'); ?>"
          }
        })
      });
    });
  </script>

</body>

</html>