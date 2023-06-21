<?= $this->extend('template/pengunjung'); ?>

<?= $this->section('isi'); ?>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center pb-4">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <span class="subheading">Destination</span>
        <h2 class="mb-4">Destinations list</h2>
      </div>
    </div>
    <div class="row">
      <?php if ($destinasi) : ?>
        <?php foreach ($destinasi as $d) : ?>
          <div class="col-md-4 ftco-animate">
            <div class="project-wrap">
              <?php
              $db = \Config\Database::connect();
              $dataGambar = $db->table('galeri')->where('wisataid', $d['id'])->get()->getResultArray();
              if ($dataGambar) {
                $gambarUtama = $db->table('galeri')->where('wisataid', $d['id'])->where('status', 1)->get()->getRowArray();
                if ($gambarUtama) {
                  $gambar = $gambarUtama['gambar'];
                } else {
                  $gambar = $db->table('galeri')->where('wisataid', $d['id'])->limit(1)->orderBy('id', 'ASC')->get()->getRowArray()['gambar'];
                }
              } else {
                $gambar = 'gambar/default.png';
              }
              ?>
              <a href="#" class="img" style="background-image: url(<?= $gambar; ?>);">
                <!-- <span class="price">$550/person</span> -->
              </a>
              <div class="text p-4">
                <!-- <span class="days">8 Days Tour</span> -->
                <h3><a href="<?= site_url('/destination/' . $d['id']); ?>"><?= $d['nama_wisata']; ?></a></h3>
                <p class="location"><span class="fa fa-map-marker"></span> <?= $d['alamat']; ?></p>
                <!-- <ul>
                <li><span class="flaticon-shower"></span>2</li>
                <li><span class="flaticon-king-size"></span>3</li>
                <li><span class="flaticon-mountains"></span>Near Mountain</li>
              </ul> -->
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <h4>Coming soon...</h4>
      <?php endif; ?>
    </div>
</section>

<?= $this->endSection('isi'); ?>