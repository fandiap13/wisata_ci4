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
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/wisata/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">
    <form action="<?= site_url('/admin/wisata/tambah_wisata'); ?>" method="POST" class="formdeskripsi">
      <?= csrf_field(); ?>
      <div class="form-group">
        <label class="form-label">Nama Wisata</label>
        <input type="text" name="nama_wisata" class="form-control" required>
      </div>
      <div class="form-group">
        <label class="form-label">Jam Buka / Tutup</label>
        <div class="row">
          <div class="col-md-6">
            <input type="time" name="jam_buka" class="form-control" required>
          </div>
          <div class="col-md-6">
            <input type="time" name="jam_tutup" class="form-control" required>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="summernote"></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="2" required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Lokasi Google Map</label>
        <textarea name="lokasi_gmap" class="form-control" rows="2"></textarea>
      </div>
      <!-- <div class="form-group map">

        </div> -->
      <div class="form-group">
        <button type="submit" class="btn btn-primary btnSimpan"><i class="fa fa-save"></i> Tambah wisata</button>
      </div>
    </form>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  $(document).ready(function() {
    $('.summernote').summernote({
      height: '200px'
    })
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
          Swal.fire('Sukses', response.success, 'success').then(() => {
            window.location = `<?= site_url('/admin/wisata/lengkapi_data/'); ?>/${response.id}`;
          });
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
</script>

<?= $this->endSection('isi'); ?>