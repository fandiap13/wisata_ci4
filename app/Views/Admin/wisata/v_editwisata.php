<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>

<style>
  /* #map {
    width: 100%;
    height: 400px;
  } */
</style>

<!-- summernote -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/summernote/summernote-bs4.min.css">
<!-- Summernote -->
<script src="<?= base_url(); ?>/plugins/summernote/summernote-bs4.min.js"></script>


<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/wisata/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <!-- Data wisata -->
    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Data Wisata</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="<?= site_url('/admin/wisata/simpan'); ?>" method="POST" class="formdeskripsi">
          <?= csrf_field(); ?>
          <input type="hidden" name="id" value="<?= $data['id']; ?>">
          <div class="form-group">
            <label class="form-label">Nama Wisata</label>
            <input type="text" name="nama_wisata" value="<?= $data['nama_wisata']; ?>" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Jam Buka / Tutup</label>
            <div class="row">
              <div class="col-md-6">
                <input type="time" name="jam_buka" class="form-control" value="<?= $data['jam_buka']; ?>" required>
              </div>
              <div class="col-md-6">
                <input type="time" name="jam_tutup" class="form-control" value="<?= $data['jam_tutup']; ?>" required>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="summernote"><?= $data['deskripsi']; ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required><?= $data['alamat']; ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Lokasi Google Map</label>
            <textarea name="lokasi_gmap" class="form-control" rows="2"><?= $data['lokasi_gmap']; ?></textarea>
          </div>
          <div class="form-group map">
            <?= $data['lokasi_gmap']; ?>
          </div>
          <div class="form-group">
            <label for="">Status</label>
            <select name="status" id="status" class="form-control" required>
              <option value="">-- Pilih status --</option>
              <option value="0" <?= $data['status'] == 0 ? 'selected' : ''; ?>>Draf</option>
              <option value="1" <?= $data['status'] == 1 ? 'selected' : ''; ?>>Dipublikasi</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btnSimpan"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- end Data wisata -->

    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Tiket Wisata</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="<?= site_url('/admin/wisata/tambahTiket'); ?>" method="POST" class="formtiket">
          <?= csrf_field(); ?>
          <input type="hidden" name="id" value="<?= $data['id']; ?>">
          <input type="hidden" name="tiketid" value="">
          <div class="form-group">
            <label for="">Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="">Harga</label>
            <input type="text" name="harga" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="">Status</label>
            <select name="status_tiket" class="form-control" required>
              <option value="">-- Pilih status --</option>
              <option value="0">Habis</option>
              <option value="1">Tersedia</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btnTiket"><i class="fa fa-save"></i> Tambah tiket</button>
            <button type="button" class="btn btn-warning" onclick="clearTiket()"><i class="fas fa-sync-alt"></i> Refresh input</button>
          </div>
        </form>

        <div class="viewTiket"></div>
      </div>
      <!-- /.card-body -->
    </div>

    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Galeri Wisata</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="" method="POST" class="formgaleri">
          <?= csrf_field(); ?>
          <input type="hidden" name="id" value="<?= $data['id']; ?>">
          <div class="form-group">
            <label for="">Upload Gambar</label>
            <input type="file" name="gambar[]" class="form-control" multiple='multiple' id="gambar">
            <div class="invalid-feedback errorgambar"></div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btnGaleri"><i class="fa fa-save"></i> Tambah Galeri</button>
          </div>
        </form>

        <div class="viewGaleri"></div>
      </div>
      <!-- /.card-body -->
    </div>

  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  const dataGaleri = () => {
    let id = $('input[name=id]').val();
    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/wisata/dataGaleri'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewGaleri').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  const dataTiket = () => {
    let id = $('input[name=id]').val();
    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/wisata/dataTiket'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewTiket').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function clearTiket() {
    $('.formtiket').attr('action', '<?= site_url('/admin/wisata/tambahTiket'); ?>');
    $('.btnTiket').html('<i class="fa fa-save"></i> Tambah tiket');
    $('input[name=tiketid]').val("");
    $('input[name=kategori]').val("");
    $('input[name=harga]').val("");
    $('select[name=status_tiket]').val("");
  }

  $(document).ready(function() {
    $('.summernote').summernote({
      height: '200px'
    })

    dataGaleri();
    dataTiket();
  });

  $('.formdeskripsi').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: () => {
        $('.btnSimpan').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnSimpan').attr('disabled', true);
      },
      complete: () => {
        $('.btnSimpan').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('.btnSimpan').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          $('.map').html(response.map);
          Swal.fire('Sukses', response.success, 'success');
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => {
            window.location.reload();
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
    return false;
  });

  $('.formgaleri').submit(function(e) {
    e.preventDefault();
    let form = $('.formgaleri')[0];
    let data = new FormData(form);
    $.ajax({
      type: "POST",
      url: "<?= site_url('/admin/wisata/simpan_galeri'); ?>",
      data: data,
      enctype: "multipart/form-data",
      cache: false,
      processData: false,
      contentType: false,
      dataType: "json",
      beforeSend: () => {
        $('.btnGaleri').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnGaleri').attr('disabled', true);
      },
      complete: () => {
        $('.btnGaleri').html('<i class="fa fa-save"></i> Tambah Galeri');
        $('.btnGaleri').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success');
          $('#gambar').val("").removeClass('is-invalid');
          $('.errorgambar').html("");
          dataGaleri();
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => {
            window.location.reload();
          });
        }
        if (response.errors) {
          if (response.errors.gambar) {
            $('#gambar').addClass('is-invalid');
            $('.errorgambar').html(response.errors.gambar);
          } else {
            $('#gambar').removeClass('is-invalid');
            $('.errorgambar').html("");
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
    return false;
  });

  $('.formtiket').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: () => {
        $('.btnTiket').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnTiket').attr('disabled', true);
      },
      complete: () => {
        $('.btnTiket').html('<i class="fa fa-save"></i> Tambah tiket');
        $('.btnTiket').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success');
          clearTiket();
          dataTiket();
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error');
          clearTiket();
          dataTiket();
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
    return false;
  });
</script>

<?= $this->endSection('isi'); ?>