<?php

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

  <link rel="stylesheet" href="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.css">
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
          <li class="nav-item active"><a href="<?= site_url('/'); ?>" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="<?= site_url('/about-us'); ?>" class="nav-link">About Us</a></li>
          <li class="nav-item"><a href="<?= site_url('/destination'); ?>" class="nav-link">Destination</a></li>
          <li class="nav-item"><a href="<?= site_url('/blog'); ?>" class="nav-link">Blog</a></li>
          <li class="nav-item"><a href="<?= site_url('/contact'); ?>" class="nav-link">Contact</a></li>
          <?php if (empty(session('LoggedUserData'))) : ?>
            <li class="nav-item"><a href="<?= site_url('/login'); ?>" class="nav-link"><i class="fa fa-sign-in"></i> Login</a></li>
          <?php endif; ?>
          <?php if (!empty(session()->get('LoggedUserData')) && session('LoggedUserData')['role'] == 'Pengunjung') : ?>
            <li class="nav-item dropdown">
              <a href="#" data-toggle="dropdown" aria-expanded="false" class="nav-link">
                <i class="fa fa-money"></i> Transaksi
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= site_url('/transaksi/keranjang'); ?>"><i class="fa fa-shopping-cart"></i> Keranjang</a>
                <a class="dropdown-item" href="<?= site_url('/transaksi/daftar-transaksi'); ?>"><i class="fa fa-money"></i> Transaksi</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a href="#" data-toggle="dropdown" aria-expanded="false" class="nav-link">
                <i class="fa fa-user"></i> <?= session('LoggedUserData')['name']; ?>
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= site_url('/profile'); ?>"><i class="fa fa-user"></i> Profile</a>
                <a class="dropdown-item" href="#" id="keluar"><i class="fa fa-sign-out"></i> Log Out</a>
              </div>
            </li>
          <?php endif; ?>

          <?php if (!empty(session()->get('LoggedUserData')) && session('LoggedUserData')['role'] == 'Admin') : ?>
            <li class="nav-item"><a href="<?= site_url('/admin/dashboard/index'); ?>" class="nav-link"><?= session('LoggedUserData')['name']; ?></a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->

  <div class="hero-wrap js-fullheight" style="background-image: url('<?= base_url($setting['gambar_carousel']); ?>">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text js-fullheight align-items-center" data-scrollax-parent="true">
        <div class="col-md-7 ftco-animate">
          <span class="subheading">Welcome to <?= $setting['nama_web']; ?></span>
          <h1 class="mb-4"><?= $setting['caption_carousel_1']; ?></h1>
          <p class="caps"><?= $setting['caption_carousel_2']; ?></p>
        </div>
        <a href="<?= $setting['cinematic_link']; ?>" class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
          <span class="fa fa-play"></span>
        </a>
      </div>
    </div>
  </div>

  <?php if ($setting['pembelian_tiket'] == 1) : ?>
    <section class="ftco-section ftco-no-pb ftco-no-pt">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="ftco-search d-flex justify-content-center">
              <div class="col-md-12 tab-wrap">
                <div class="tab-content" id="v-pills-tabContent">

                  <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-nextgen-tab">
                    <form action="<?= site_url('/transaksi/pesan-tiket'); ?>" method="POST" class="search-property-1" id="formpesantiket">
                      <div class="row no-gutters">
                        <div class="col-md d-flex">
                          <div class="form-group p-4">
                            <label for="#">Pilih Wisata</label>
                            <div class="form-field">
                              <!-- <div class="select-wrap"> -->
                              <!-- <div class="icon"><span class="fa fa-chevron-down"></span></div> -->
                              <select name="wisataid" id="wisataid" class="form-control">
                                <option value="">-- Pilih wisata --</option>
                                <?php foreach ($wisata as $w) : ?>
                                  <option value="<?= $w['id']; ?>"><?= $w['nama_wisata']; ?></option>
                                <?php endforeach; ?>
                              </select>
                              <!-- </div> -->
                            </div>
                          </div>
                        </div>
                        <div class="col-md d-flex">
                          <div class="form-group p-4">
                            <label for="#">Jenis Tiket</label>
                            <div class="form-field">
                              <!-- <div class="select-wrap"> -->
                              <!-- <div class="icon"><span class="fa fa-chevron-down"></span></div> -->
                              <select name="tiketid" id="tiketid" class="form-control" disabled>
                                <option value="">-- Pilih Jenis --</option>
                              </select>
                              <!-- </div> -->
                            </div>
                          </div>
                        </div>
                        <div class="col-md d-flex">
                          <div class="form-group p-4">
                            <label for="#">Jumlah</label>
                            <div class="form-field">
                              <!-- <div class="icon"><span class="fa fa-ticket"></span></div> -->
                              <input type="number" class="form-control" name="jml" id="jml" placeholder="Jumlah tiket" min="1" value="1">
                            </div>
                          </div>
                        </div>
                        <!-- <div class="col-md d-flex">
                        <div class="form-group p-4">
                          <label for="#">Tanggal Kunjungan</label>
                          <div class="form-field">
                            <div class="icon"><span class="fa fa-calendar"></span></div>
                            <input type="date" class="form-control" name="tgl_kunjungan" id="tgl_kunjungan" placeholder="Check In Date">
                          </div>
                        </div>
                      </div> -->
                        <div class="col-md d-flex">
                          <div class="form-group d-flex w-100 border-0">
                            <div class="form-field w-100 align-items-center d-flex">
                              <?php if (!empty(session('LoggedUserData')['role']) && session('LoggedUserData')['role'] == 'Pengunjung') : ?>
                                <button type="submit" class="align-self-stretch form-control btn btn-primary btnPesanTiket">Pesan Tiket</button>
                              <?php else : ?>
                                <button type="button" class="align-self-stretch form-control btn btn-primary" onclick="return window.location = '<?= site_url('/login'); ?>'">Pesan Tiket</button>
                              <?php endif; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
  <?php endif; ?>

  <section class="ftco-section services-section">
    <div class="container">
      <div class="row d-flex">
        <div class="col-md-6 order-md-last heading-section pl-md-5 ftco-animate d-flex align-items-center">
          <div class="w-100">
            <span class="subheading">Welcome to <?= $setting['nama_web']; ?></span>
            <h2 class="mb-4">Deskripsi</h2>
            <?= $setting['deskripsi_web']; ?>
            <p><a href="#" class="btn btn-primary py-3 px-4">Search Destination</a></p>
          </div>
        </div>
        <div class="col-md-6 d-flex align-items-stretch">
          <div class="img d-flex w-100 align-items-center justify-content-center" style="background-image:url(<?= base_url($setting['gambar']); ?>);">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section img ftco-select-destination" style="background-image: url(<?= base_url(); ?>/template_pengunjung/images/bg_3.jpg);">
    <div class="container">
      <div class="row justify-content-center pb-4">
        <div class="col-md-12 heading-section text-center ftco-animate">
          <!-- <span class="subheading">Pacific Provide Places</span> -->
          <h2 class="mb-4">Pilih Destinasi Wisata</h2>
        </div>
      </div>
    </div>
    <div class="container container-2">
      <div class="row">
        <div class="col-md-12">
          <div class="carousel-destination owl-carousel ftco-animate">
            <?php foreach ($wisata as $w) : ?>
              <?php
              $builder = \Config\Database::connect();
              $galeri = $builder->table('galeri')->where('wisataid', $w['id'])->limit(1)->get()->getRowArray();
              ?>
              <div class="item">
                <div class="project-destination">
                  <a href="<?= site_url('/destination/' . $w['id']); ?>" class="img" style="background-image: url(<?= base_url(!empty($galeri['gambar']) ? $galeri['gambar'] : "/gambar/default.png"); ?>);">
                    <div class="text">
                      <h3><?= $w['nama_wisata']; ?></h3>
                    </div>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center pb-4">
        <div class="col-md-12 heading-section text-center ftco-animate">
          <span class="subheading">Our Blog</span>
          <h2 class="mb-4">Recent Post</h2>
        </div>
      </div>
      <div class="row d-flex">
        <?php foreach ($blog as $b) : ?>
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry justify-content-end" style="width: 100%;">
              <a href="<?= site_url('/blog/' . $b['id']); ?>" class="block-20" style="background-image: url('<?= base_url($b['gambar']); ?>');">
              </a>
              <div class="text">
                <div class="d-flex align-items-center mb-4 topp">
                  <div class="one">
                    <span class="day"><?= date("d", strtotime($b['created_at'])); ?></span>
                  </div>
                  <div class="two">
                    <span class="yr"><?= date("M", strtotime($b['created_at'])); ?></span>
                    <span class="mos"><?= date("Y", strtotime($b['created_at'])); ?></span>
                  </div>
                </div>
                <h3 class="heading"><a href="<?= site_url('/blog/' . $b['id']); ?>"><?= $b['judul']; ?></a></h3>
                <!-- <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p> -->
                <p><a href="<?= site_url('/blog/' . $b['id']); ?>" class="btn btn-primary">Baca selengkapnya</a></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="ftco-section ftco-about ftco-no-pt img">
    <div class="container">
      <div class="row d-flex">
        <div class="col-md-12 about-intro">
          <div class="row">
            <div class="col-md-6 d-flex align-items-stretch">
              <div class="img d-flex w-100 align-items-center justify-content-center" style="background-image:url(<?= base_url($setting['gambar']); ?>);">
              </div>
            </div>
            <div class="col-md-6 pl-md-5 py-5">
              <div class="row justify-content-start pb-3">
                <div class="col-md-12 heading-section ftco-animate">
                  <span class="subheading">About Us</span>
                  <h2 class="mb-4"><?= $setting['motto']; ?></h2>
                  <?= $setting['about_us']; ?>
                  <p><a href="#" class="btn btn-primary">Book Your Destination</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- <section class="ftco-intro ftco-section ftco-no-pt">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text-center">
          <div class="img" style="background-image: url(<?= base_url($setting['gambar']); ?>);">
            <div class="overlay"></div>
            <h2>We Are Pacific A Travel Agency</h2>
            <p>We can manage your dream building A small river named Duden flows by their place</p>
            <p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Ask For A Quote</a></p>
          </div>
        </div>
      </div>
    </div>
  </section> -->

  <!-- <section class="ftco-intro ftco-section ftco-no-pt">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text-center">
          <div class="img" style="background-image: url(<?= base_url(); ?>/template_pengunjung/images/bg_2.jpg);">
            <div class="overlay"></div>
            <h2>We Are Pacific A Travel Agency</h2>
            <p>We can manage your dream building A small river named Duden flows by their place</p>
            <p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Ask For A Quote</a></p>
          </div>
        </div>
      </div>
    </div>
  </section> -->

  <footer style="background-color: #343a40; height: 100px;
		display: flex;
    justify-content: center;
		align-items: center;">
    <p class="text-center">
      <a href="<?= site_url('/'); ?>"><?= $setting['nama_web']; ?></a>. Developed By <a href="https://github.com/fandiap13" target="_blank" class="modalDeveloped">Fandi Aziz Pratama</a>
    </p>
  </footer>



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
    </svg></div>

  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery.min.js"></script>
  <script src="<?= base_url(); ?>/template_pengunjung/js/jquery-migrate-3.0.1.min.js"></script>

  <script src="<?= base_url(); ?>/sweetalert2/dist/sweetalert2.min.js"></script>

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
    $('#wisataid').change(function(e) {
      e.preventDefault();
      let wisataid = $(this, 'option:selected').val();
      $.ajax({
        type: "post",
        url: "<?= site_url('/tampil-tiket'); ?>",
        data: {
          wisataid: wisataid
        },
        dataType: "json",
        success: function(response) {
          if (response.data) {
            $('#tiketid').removeAttr('disabled');
            $('#tiketid').html(response.data);
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });
    });

    $('#formpesantiket').submit(function(e) {
      e.preventDefault();
      let wisataid = $('#wisataid').attr('selected', 'selected').val();
      let tiketid = $('#tiketid').attr('selected', 'selected').val();
      // let tgl_kunjungan = $('#tgl_kunjungan').val();
      let jml = $('#jml').val();

      if (!wisataid || !tiketid || !jml) {
        Swal.fire('Error', 'Semua inputan tidak boleh kosong !', 'error');
        return false;
      }

      let session = "<?= empty(session('LoggedUserData')) ? null : session('LoggedUserData')['id']; ?>";
      if (!session) {
        window.location = '<?= site_url('/login'); ?>';
        return false;
      }

      $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: {
          wisataid: wisataid,
          tiketid: tiketid,
          jml: jml,
        },
        dataType: "json",
        beforeSend: () => {
          $('.btnPesanTiket').html('<i class="fa fa-spinner fa-spin"></i>');
          $('.btnPesanTiket').attr('disabled', true);
        },
        complete: () => {
          $('.btnPesanTiket').html('PESAN TIKET');
          $('.btnPesanTiket').removeAttr('disabled');
        },
        success: function(response) {
          if (response.success) {
            Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
          }
          if (response.error) {
            Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });
      return false;
    });

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

      // getbutton.io
      var options = {
        whatsapp: "<?= $setting['no_wa']; ?>", // WhatsApp number
        email: "<?= $setting['email']; ?>", // Email
        call_to_action: "Hubungi Kami!!!", // Call to action
        button_color: "#129BF4", // Color of button
        position: "right", // Position may be 'right' or 'left'
        order: "whatsapp,email", // Order of buttons
        // pre_filled_message: "Halo", // WhatsApp pre-filled message
      };
      var proto = document.location.protocol,
        host = "getbutton.io",
        url = proto + "//static." + host;
      var s = document.createElement('script');
      s.type = 'text/javascript';
      s.async = true;
      s.src = url + '/widget-send-button/js/init.js';
      s.onload = function() {
        WhWidgetSendButton.init(host, proto, options);
      };
      var x = document.getElementsByTagName('script')[0];
      x.parentNode.insertBefore(s, x);
    });
  </script>

</body>

</html>