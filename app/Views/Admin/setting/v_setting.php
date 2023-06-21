<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>

<!-- summernote -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/summernote/summernote-bs4.min.css">
<!-- Summernote -->
<script src="<?= base_url(); ?>/plugins/summernote/summernote-bs4.min.js"></script>


<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">

    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <!-- Data wisata -->
    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Data Web</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="<?= site_url('/admin/setting/simpan_setting'); ?>" method="POST" class="formsetting">
          <?= csrf_field(); ?>
          <input type="hidden" name="id" value="<?= $data['id']; ?>">
          <div class="form-group">
            <label for="">Nama Website</label>
            <input type="text" class="form-control" name="nama_web" value="<?= $data['nama_web']; ?>" required>
            <div class="invalid-feedback error_nama_web"></div>
          </div>
          <div class="form-group">
            <label for="">Rekening Website</label>
            <input type="text" class="form-control" name="rekening" value="<?= $data['rekening']; ?>" required>
            <div class="invalid-feedback error_rekening"></div>
          </div>
          <div class="form-group">
            <label for="">Motto Website</label>
            <input type="text" class="form-control" name="motto" value="<?= $data['motto']; ?>" required>
            <div class="invalid-feedback error_motto"></div>
          </div>
          <div class="form-group">
            <label for="">Gambar Deskripsi</label>
            <div class="row">
              <div class="col-4">
                <img src="<?= base_url($data['gambar']); ?>" class="img-thumbnail img-preview w-100">
              </div>
              <div class="col-8">
                <input type="hidden" name="gambarLama" value="<?= $data['gambar']; ?>">
                <input type="file" id="gambar" name="gambar" class="form-control gambar">
                <div class="invalid-feedback error_gambar"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="">Logo Website</label>
            <div class="row">
              <div class="col-4">
                <img src="<?= base_url($data['favicon']); ?>" class="img-thumbnail img-preview w-100">
              </div>
              <div class="col-8">
                <input type="hidden" name="faviconLama" value="<?= $data['favicon']; ?>">
                <input type="file" id="favicon" name="favicon" class="form-control gambar">
                <div class="invalid-feedback error_favicon"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="">Deskripsi Website</label>
            <textarea name="deskripsi_web" class="summernote"><?= $data['deskripsi_web']; ?></textarea>
          </div>
          <div class="form-group">
            <label for="">About us</label>
            <textarea name="about_us" class="summernote"><?= $data['about_us']; ?></textarea>
          </div>
          <div class="form-group">
            <label for="pembelian_tiket">Aktifkan Pembelian Tiket</label>
            <select name="pembelian_tiket" id="pembelian_tiket" class="form-control" required>
              <option value="">-- Pilih --</option>
              <option value="1" <?= $data['pembelian_tiket'] == 1 ? 'selected' : ''; ?>>Aktif</option>
              <option value="2" <?= $data['pembelian_tiket'] == 2 ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btnSetting"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- end Data wisata -->

    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Carousel</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="<?= site_url('/admin/setting/simpan_carousel'); ?>" method="POST" class="formcarousel">
          <?= csrf_field(); ?>
          <input type="hidden" name="id" value="<?= $data['id']; ?>">
          <div class="form-group">
            <label for="">Gambar Carousel</label>
            <div class="row">
              <div class="col-4">
                <img src="<?= base_url($data['gambar_carousel']); ?>" class="img-thumbnail img-preview w-100">
              </div>
              <div class="col-8">
                <input type="hidden" name="gambar_carouselLama" value="<?= $data['gambar_carousel']; ?>">
                <input type="file" id="gambar_carousel" name="gambar_carousel" class="form-control gambar" id="gambar_carousel">
                <div class="invalid-feedback error_gambar_carousel"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="caption_carousel_1">Caption Carousel 1</label>
            <textarea name="caption_carousel_1" id="caption_carousel_1" rows="3" class="form-control" required><?= $data['caption_carousel_1']; ?></textarea>
          </div>
          <div class="form-group">
            <label for="caption_carousel_2">Caption Carousel 2</label>
            <textarea name="caption_carousel_2" id="caption_carousel_2" rows="3" class="form-control" required><?= $data['caption_carousel_2']; ?></textarea>
          </div>
          <div class="form-group">
            <label for="cinematic_link">Link Cinematic Video</label>
            <textarea name="cinematic_link" id="cinematic_link" rows="3" class="form-control"><?= $data['cinematic_link']; ?></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btnCarousel"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>

    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Kontak Web</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="<?= site_url('/admin/setting/simpan_kontak'); ?>" method="POST" class="formkontak">
          <?= csrf_field(); ?>
          <div class="form-group">
            <label for="">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $data['email']; ?>" required>
            <div class="invalid-feedback error_email"></div>
          </div>
          <div class="form-group">
            <label for="">No. Telp / Wa</label>
            <input type="number" class="form-control" name="no_wa" value="<?= $data['no_wa']; ?>" required>
            <div class="invalid-feedback error_no_wa"></div>
          </div>
          <div class="form-group">
            <label for="">Link Instagram</label>
            <input type="text" class="form-control" name="instagram" value="<?= $data['instagram']; ?>" required>
            <div class="invalid-feedback error_instagram"></div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btnKontak"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>

  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  function cek_regex(field, regex) {
    return field.match(regex);
  }

  $('.gambar').change(function(e) {
    e.preventDefault();
    const foto = this;
    const imgPreview = this.parentElement.parentElement.querySelector(".img-preview");

    const fileFoto = new FileReader();
    fileFoto.readAsDataURL(foto.files[0]);
    fileFoto.onload = function(e) {
      imgPreview.src = e.target.result;
    }
  });

  $(document).ready(function() {
    $('.summernote').summernote({
      height: '200px',
    });
  });

  $('.formsetting').submit(function(e) {
    e.preventDefault();
    // let no_wa = $('input[name=no_wa]').val();
    // let regex_nowa = /62[0-9]{11,12}/gs;

    // if (!cek_regex(no_wa, regex_nowa)) {
    //   $('input[name=no_wa]').addClass('is-invalid');
    //   $('.error_no_wa').html("No.Telp/Wa tidak valid [contoh: 6285293444555]");
    //   return false;
    // } else {
    //   $('input[name=no_wa]').removeClass('is-invalid');
    //   $('.error_no_wa').html('');
    // }

    let form = $('.formsetting')[0];
    let data = new FormData(form);

    $.ajax({
      type: "POST",
      url: $(this).attr('action'),
      data: data,
      cache: false,
      enctype: "multipart/form-data",
      processData: false,
      contentType: false,
      dataType: "json",
      beforeSend: () => {
        $('.btnSetting').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnSetting').attr('disabled', true);
      },
      complete: () => {
        $('.btnSetting').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('.btnSetting').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          // Swal.fire('Sukses', response.success, 'success');
          // $('input[name=gambar]').val("");
          // $('input[name=favicon]').val("");
          Swal.fire('Sukses', response.success, 'success').then(() => {
            window.location.reload();
          });
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => {
            window.location.reload();
          });
        }

        if (response.errors) {
          if (response.errors.gambar) {
            $('.error_gambar').html(response.errors.gambar);
            $('input[name=gambar]').addClass('is-invalid');
          } else {
            $('input[name=gambar]').removeClass('is-invalid');
            $('.error_gambar').html('');
          }
          if (response.errors.favicon) {
            $('.error_favicon').html(response.errors.favicon);
            $('input[name=favicon]').addClass('is-invalid');
          } else {
            $('input[name=favicon]').removeClass('is-invalid');
            $('.error_favicon').html('');
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
    return false;
  });

  $('.formcarousel').submit(function(e) {
    e.preventDefault();

    let form = $('.formcarousel')[0];
    let data = new FormData(form);

    $.ajax({
      type: "POST",
      url: $(this).attr('action'),
      data: data,
      cache: false,
      enctype: "multipart/form-data",
      processData: false,
      contentType: false,
      dataType: "json",
      beforeSend: () => {
        $('.btnCarousel').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnCarousel').attr('disabled', true);
      },
      complete: () => {
        $('.btnCarousel').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('.btnCarousel').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => {
            window.location.reload();
          });
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => {
            window.location.reload();
          });
        }

        if (response.errors) {
          if (response.errors.gambar_carousel) {
            $('.error_gambar_carousel').html(response.errors.gambar_carousel);
            $('input[name=gambar_carousel]').addClass('is-invalid');
          } else {
            $('input[name=gambar_carousel]').removeClass('is-invalid');
            $('.error_gambar_carousel').html('');
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
    return false;
  });

  $('.formkontak').submit(function(e) {
    e.preventDefault();
    let no_wa = $('input[name=no_wa]').val();
    let regex_nowa = /62[0-9]{11,12}/gs;

    if (!cek_regex(no_wa, regex_nowa)) {
      $('input[name=no_wa]').addClass('is-invalid');
      $('.error_no_wa').html("No.Telp/Wa tidak valid [contoh: 6285293444555]");
      return false;
    } else {
      $('input[name=no_wa]').removeClass('is-invalid');
      $('.error_no_wa').html('');
    }

    $.ajax({
      type: "POST",
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: () => {
        $('.btnKontak').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnKontak').attr('disabled', true);
      },
      complete: () => {
        $('.btnKontak').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('.btnKontak').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => {
            window.location.reload();
          });
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => {
            window.location.reload();
          });
        }

        if (response.errors) {
          if (response.errors.email) {
            $('.error_email').html(response.errors.email);
            $('input[name=email]').addClass('is-invalid');
          } else {
            $('input[name=email]').removeClass('is-invalid');
            $('.error_email').html('');
          }
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