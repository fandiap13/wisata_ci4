<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>

<section class="ftco-section ftco-no-pb contact-section mb-5">
  <div class="container">
    <div class="row justify-content-center pb-4">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <span class="subheading">Our Contact</span>
        <h2 class="mb-4">Contact Us</h2>
      </div>
    </div>
    <div class="row d-flex contact-info justify-content-center">
      <div class="col-md-3 d-flex">
        <div class="align-self-stretch box p-4 text-center">
          <div class="icon d-flex align-items-center justify-content-center">
            <span class="fa fa-phone"></span>
          </div>
          <h3 class="mb-2">No. Telp / Whatsapp</h3>
          <p><a href="https://wa.me/<?= $setting['no_wa']; ?>" target="_blank"><?= $setting['no_wa']; ?></a></p>
        </div>
      </div>
      <div class="col-md-3 d-flex">
        <div class="align-self-stretch box p-4 text-center">
          <div class="icon d-flex align-items-center justify-content-center">
            <span class="fa fa-paper-plane"></span>
          </div>
          <h3 class="mb-2">E-mail</h3>
          <p><a href="mailto:<?= $setting['email']; ?>"><?= $setting['email']; ?></a></p>
        </div>
      </div>
      <div class="col-md-3 d-flex">
        <div class="align-self-stretch box p-4 text-center">
          <div class="icon d-flex align-items-center justify-content-center">
            <span class="fa fa-instagram"></span>
          </div>
          <h3 class="mb-2">Instagram</h3>
          <p><a href="<?= $setting['instagram']; ?>" target="_blank">Klik menuju ke instagram</a></p>
        </div>
      </div>
    </div>
  </div>
</section>


<?= $this->endSection('isi'); ?>