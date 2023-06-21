<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>

<section class="ftco-section services-section">
  <div class="container">
    <h3><?= $blog['judul']; ?></h3>
    <p><span class="d-inline-block small"><?= date('d M Y', strtotime($blog['created_at'])); ?></span> / <span style="color: rgba(0, 0, 0, 0.8);"><?= $blog['nama_user']; ?></span></p>
    <div class="row col-12">
      <div class="col-md-12 d-flex justify-content-center">
        <img src="<?= base_url($blog['gambar']); ?>" alt="Gambar" style="width: 50%;">
      </div>
    </div>
    <div class="row d-flex justify-content-center">
      <div class="col-md-10">
        <?= $blog['deskripsi']; ?>
      </div>
    </div>
  </div>
</section>

<hr>

<section class="ftco-section services-section">
  <div class="container">
    <div class="row justify-content-center pb-4">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <h2 class="mb-4">Artikel Terkait</h2>
      </div>
    </div>

    <div class="row d-flex">
      <?php foreach ($terkait as $b) : ?>
        <div class="col-md-4 d-flex ftco-animate">
          <div class="blog-entry justify-content-end" style="width: 100%;">
            <a href="<?= site_url('/blog/' . $b['id']); ?>" class="block-20" style="background-image: url('<?= base_url($b['gambar']); ?>');">
            </a>
            <div class=" text">
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

    <a href="<?= site_url('/blog'); ?>">Lihat selengkapnya >></a>

  </div>
</section>
<?= $this->endSection('isi'); ?>