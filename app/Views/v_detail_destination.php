<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/lightbox/dist/css/lightbox.min.css">

<style>
  .galeri {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }


  .gambar-galeri {
    width: 30%;
    transition: .5s;
  }

  .gambar-galeri:hover {
    transform: scale(105%);
  }

  .galeri .gambar-galeri .gambar {
    width: 100%;
    height: 300px;
    object-fit: cover;
  }

  /* ukuran tablet */
  @media screen and (max-width: 768px) {
    .galeri {
      display: flex;
      flex-direction: column;
    }

    .gambar-galeri {
      width: 100%;
    }
  }

  /* ukuran smartphone */
  @media screen and (max-width: 567px) {
    .galeri {
      display: flex;
      flex-direction: column;
    }

    .gambar-galeri {
      width: 100%;
    }
  }
</style>

<section class="ftco-section services-section">
  <div class="container">
    <h3><?= $destinasi['nama_wisata']; ?></h3>
    <span class="d-block small"><?= date('d M Y', strtotime($destinasi['created_at'])); ?></span>

    <div class="row d-flex justify-content-center mb-3">
      <div class="col-md-10">
        <div class="alert alert-primary">
          <ul>
            <li><b>Harga Tiket:</b>
              <ul>
                <?php foreach ($tiket as $key => $value) : ?>
                  <li><?= $value['kategori']; ?>: Rp <?= $value['harga'] == 0 ? 'Gratis' : number_format($value['harga'], 0, ",", "."); ?></li>
                <?php endforeach; ?>
              </ul>
            </li>
            <li><b>Jam Buka: </b> <?= date('H:i', strtotime($destinasi['jam_buka'])); ?> WIB s/d <?= date('H:i', strtotime($destinasi['jam_tutup'])); ?> WIB </li>
            <li><b>Alamat:</b> <?= $destinasi['alamat']; ?></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="galeri mt-2 mb-2">
      <?php foreach ($galeri as $g) : ?>
        <div class="gambar-galeri">
          <a href="<?= base_url("/" . $g['gambar']); ?>" class="example-image-link" data-lightbox="example-set"><img src="<?= base_url("/" . $g['gambar']); ?>" class="d-block gambar" alt="Galeri"></a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="row d-flex justify-content-center">
      <div class="col-md-10">
        <?= $destinasi['deskripsi']; ?>
      </div>
    </div>

    <?php if ($destinasi['lokasi_gmap'] !== null && $destinasi['lokasi_gmap'] !== "") : ?>
      <div class="row d-flex justify-content-center mt-2">
        <div class="col-md-10 text-center">
          <h4>Lokasi Wisata</h6>
            <?= $destinasi['lokasi_gmap']; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>


<script src="<?= base_url(); ?>/lightbox/dist/js/lightbox-plus-jquery.min.js"></script>
<?= $this->endSection('isi'); ?>