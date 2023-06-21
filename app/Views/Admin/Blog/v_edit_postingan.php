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
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/blog/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <form action="<?= site_url('/admin/blog/update'); ?>" method="POST" class="formsimpan" enctype="multipart/form-data">
      <?= csrf_field(); ?>
      <input type="hidden" name="id" value="<?= $data['id']; ?>">
      <div class="form-group">
        <label for="">Judul</label>
        <input type="text" name="judul" class="form-control" placeholder="Judul..." maxlength="150" value="<?= $data['judul']; ?>" required>
        <div class="invalid-feedback error_judul"></div>
      </div>
      <div class="form-group">
        <label for="">Gambar</label>
        <div class="row">
          <div class="col-4">
            <img src="<?= base_url($data['gambar']); ?>" class="img-thumbnail img-preview w-100">
          </div>
          <div class="col-8">
            <input type="hidden" name="gambarLama" value="<?= $data['gambar']; ?>">
            <input type="file" id="gambar" name="gambar" class="form-control">
            <div class="invalid-feedback error_gambar"></div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="">Status Postingan</label>
        <select name="status" id="" class="form-control" required>
          <option value="">-- Pilih status --</option>
          <option value="0" <?= $data['status'] == 0 ? 'selected' : ""; ?>>Draf</option>
          <option value="1" <?= $data['status'] == 1 ? 'selected' : ""; ?>>Dipublikasi</option>
        </select>
      </div>
      <div class="form-group">
        <label for="">Deskripsi</label>
        <textarea name="deskripsi" class="summernote"><?= $data['deskripsi']; ?></textarea>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btnTambah"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  function cek_regex(field, regex) {
    return field.match(regex);
  }

  $(document).ready(function() {
    $('.summernote').summernote({
      height: '300px',
    });
  });

  $('input[name=gambar]').change(function(e) {
    e.preventDefault();
    const foto = this;
    const imgPreview = this.parentElement.parentElement.querySelector(".img-preview");

    const fileFoto = new FileReader();
    fileFoto.readAsDataURL(foto.files[0]);
    fileFoto.onload = function(e) {
      imgPreview.src = e.target.result;
    }
  });

  $('.formsimpan').submit(function(e) {
    e.preventDefault();

    let form = $('.formsimpan')[0];
    let data = new FormData(form);

    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action'),
      data: data,
      enctype: "multipart/form-data",
      cache: false,
      processData: false,
      contentType: false,
      dataType: "json",
      beforeSend: () => {
        $('.btnTambah').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnTambah').attr('disabled', true);
      },
      complete: () => {
        $('.btnTambah').html('<i class="fa fa-save"></i> Simpan');
        $('.btnTambah').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => {
            window.location = "<?= site_url('/admin/blog/index'); ?>";
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
          if (response.errors.judul) {
            $('.error_judul').html(response.errors.judul);
            $('input[name=judul]').addClass('is-invalid');
          } else {
            $('input[name=judul]').removeClass('is-invalid');
            $('.error_judul').html('');
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