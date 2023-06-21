<?php

$request = \Config\Services::request();
$uri1 = $request->uri->getSegment(1);

$db = \Config\Database::connect();
$setting = $db->table('setting')->limit(1)->orderBy('id', 'ASC')->get()->getRowArray();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title><?= $title; ?></title>

  <link rel="icon" type="image/x-icon" href="<?= base_url($setting['favicon']); ?>">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Arizonia&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/animate.css">

  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/magnific-popup.css">

  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/jquery.timepicker.css">


  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/flaticon.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/template_pengunjung/css/style.css">

  <!-- jquery -->
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery-migrate-3.0.1.min.js"></script>

  <script src="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.css">

  <style>
    .ftco-navbar-light {
      background: #343a40 !important;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="<?= site_url('/'); ?>"><?= $setting['nama_web']; ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="<?= site_url('/'); ?>" class="nav-link">Home</a></li>
          <li class="nav-item <?= $uri1 == 'about-us' ? 'active' : ''; ?>"><a href="<?= site_url('/about-us'); ?>" class="nav-link">About Us</a></li>
          <li class="nav-item <?= $uri1 == 'destination' ? 'active' : ''; ?>"><a href="<?= site_url('/destination'); ?>" class="nav-link">Destination</a></li>
          <li class="nav-item <?= $uri1 == 'blog' ? 'active' : ''; ?>"><a href="<?= site_url('/blog'); ?>" class="nav-link">Blog</a></li>
          <li class="nav-item <?= $uri1 == 'contact' ? 'active' : ''; ?>"><a href="<?= site_url('/contact'); ?>" class="nav-link">Contact</a></li>
          <?php if (empty(session('LoggedUserData'))) : ?>
            <li class="nav-item"><a href="<?= site_url('/login'); ?>" class="nav-link"><i class="fa fa-sign-in"></i> Login</a></li>
          <?php endif; ?>

          <?php if (!empty(session()->get('LoggedUserData')) && session('LoggedUserData')['role'] == 'Pengunjung') : ?>
            <li class="nav-item dropdown <?= $uri1 == 'transaksi' ? 'active' : ''; ?>">
              <a href="#" data-toggle="dropdown" aria-expanded="false" class="nav-link">
                <i class="fa fa-money"></i> Transaksi
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= site_url('/transaksi/keranjang'); ?>"><i class="fa fa-shopping-cart"></i> Keranjang</a>
                <a class="dropdown-item" href="<?= site_url('/transaksi/daftar-transaksi'); ?>"><i class="fa fa-money"></i> Transaksi</a>
              </div>
            </li>
            <li class="nav-item dropdown  <?= $uri1 == 'profile' ? 'active' : ''; ?>">
              <a href="#" data-toggle="dropdown" aria-expanded="false" class="nav-link">
                <i class="fa fa-user"></i> <?= session('LoggedUserData')['name']; ?>
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= site_url('/profile'); ?>"><i class="fa fa-user"></i> Profile</a>
                <a class="dropdown-item" href="#" id="keluar"><i class="fa fa-sign-out"></i> Log Out</a>
              </div>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->

  <?= $this->renderSection('carousel'); ?>
  <?= $this->renderSection('isi'); ?>

  <!-- <footer style="background-color: #343a40; height: 100px;
		display: flex;
    justify-content: center;
		align-items: center;">
    <div class="container">
      <div class="row">
        <p>Copyright &copy;
          <script>
            document.write(new Date().getFullYear());
          </script> All rights reserved | <a href="<?= site_url('/'); ?>"><?= $setting['nama_web']; ?></a>
        </p>
      </div>
    </div>
  </footer> -->

  <footer style="background-color: #343a40; height: 100px;
		display: flex;
    justify-content: center;
		align-items: center;">
    <p class="text-center">
      <a href="<?= site_url('/'); ?>"><?= $setting['nama_web']; ?></a>. Developed By <a href="https://github.com/fandiap13" target="_blank" class="modalDeveloped">Fandi Aziz Pratama</a>
    </p>
  </footer>


  <!-- loader -->
  <!-- <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
    </svg></div> -->


  <script src="<?= base_url(); ?>/template_pengunjung/js/popper.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/bootstrap.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.easing.1.3.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.waypoints.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.stellar.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/owl.carousel.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.magnific-popup.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.animateNumber.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/bootstrap-datepicker.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/google-map.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/main.js"></script>


  <script>
    function closeModal() {
      $("#modalpembayaran").hide();
      $('.modal-backdrop').remove()
      $(document.body).removeClass("modal-open");
      $(document.body).css("padding-right", 0);
    }

    $(document).ready(function() {
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

      $('#keluar').click(function(e) {
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