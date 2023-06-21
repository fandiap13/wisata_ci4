<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>

<section class="ftco-section services-section">
  <div class="container">
    <div class="row justify-content-center pb-4">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <span class="subheading">Our Blog</span>
        <h2 class="mb-4">Recent Post</h2>
      </div>
    </div>

    <form action="<?= site_url('/blog'); ?>" method="GET" class="formcari">
      <div class="input-group mb-5">
        <input type="text" class="form-control" placeholder="Cari blog..." aria-label="Cari blog..." aria-describedby="cari" name="cari" value="<?= isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="submit" id="cari"><i class="fa fa-search"></i> Cari</button>
        </div>
      </div>
    </form>

    <?php if ($blog) : ?>
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
    <?php else : ?>
      <div class="row">
        <h3 style="font-style: italic;">Pencarian tidak ditemukan...</h3>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($pager->getPageCount('blog') > 1) { ?>
    <?= $pager->links('blog', 'paging_blog'); ?>
  <?php } ?>

</section>

<?= $this->endSection('isi'); ?>