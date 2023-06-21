<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>

<section class="ftco-section services-section">
  <div class="container">
    <div class="row d-flex">
      <div class="col-md-12 about-intro">
        <div class="row">
          <ddiv class="col-md-6 d-flex align-items-stretch">
            <div class="img d-flex w-100 align-items-center justify-content-center" style="background-image:url(<?= base_url($setting['gambar']); ?>);">
            </div>
          </ddiv>
          <div class="col-md-6 pl-md-5 py-5">
            <div class="row justify-content-start pb-3">
              <div class="col-md-12 heading-section ftco-animate">
                <span class="subheading">About Us</span>
                <h2 class="mb-4"><?= $setting['motto']; ?></h2>
                <?= $setting['about_us']; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="ftco-section ftco-about img" style="background-image: url(<?= base_url($setting['gambar_carousel']); ?>);">
  <div class="overlay"></div>
  <div class="container py-md-5">
    <div class="row py-md-5">
      <div class="col-md d-flex align-items-center justify-content-center">
        <a href="<?= $setting['cinematic_link']; ?>" class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
          <span class="fa fa-play"></span>
        </a>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection('isi'); ?>