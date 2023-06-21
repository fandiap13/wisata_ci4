<link rel="stylesheet" href="<?= base_url(); ?>/lightbox/dist/css/lightbox.min.css">
<script src="<?= base_url(); ?>/lightbox/dist/js/lightbox-plus-jquery.min.js"></script>

<div class="mb-3">
  <h4>Gambar Utama</h4>
  <?php if ($gambar_utama == "") { ?>
    <p style="font-style: italic; color: red;">Belum dipilih</p>
  <?php } else { ?>
    <img src="<?= base_url($gambar_utama); ?>" alt="Gambar Utama" title="Gambar Utama" class="img-thumbnail" style="width: 30%;">
  <?php } ?>
</div>

<hr>
<h4>Daftar Gambar</h4>
<div class="row">
  <?php foreach ($galeri as $key => $value) : ?>
    <div class="col-md-4 d-flex flex-column mb-3">
      <a class="example-image-link" href="<?= site_url($value['gambar']); ?>" data-lightbox="example-set">
        <img src="<?= site_url($value['gambar']); ?>" style="width: 100%; display: inline-block;" class="example-image img-thumbnail">
      </a>
      <button type="button" class="btn btn-sm btn-info" onclick="gambarUtama('<?= $value['id']; ?>', '<?= $value['wisataid']; ?>')"><i class="fa fa-key"></i> Jadikan gambar utama</button>
      <button type="button" class="btn btn-sm btn-danger" onclick="hapusGambar('<?= $value['id']; ?>')"><i class="fa fa-trash-alt"></i> Hapus gambar</button>
    </div>
  <?php endforeach; ?>
</div>

<script>
  function hapusGambar(id) {
    Swal.fire({
      title: 'Hapus',
      text: "Apakah anda yakin menghapus gambar ini ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Tidak!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= site_url('/admin/wisata/hapusGaleri'); ?>",
          data: {
            id: id
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire(
                'Sukses',
                'Gambar berhasil dihapus.',
                'success'
              )
              dataGaleri();
            }

            if (response.error) {
              Swal.fire(
                'Error',
                'Terdapat kesalahan pada sistem.',
                'error'
              )
              dataGaleri();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  }

  function gambarUtama(id, wisataid) {
    Swal.fire({
      title: 'Pilih Gambar Utama',
      text: "Apakah anda memilih gambar ini ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, pilih!',
      cancelButtonText: 'Tidak!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= site_url('/admin/wisata/gambarUtama'); ?>",
          data: {
            id: id,
            wisataid: wisataid,
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire(
                'Sukses',
                'Gambar berhasil dipilih.',
                'success'
              )
              dataGaleri();
            }

            if (response.error) {
              Swal.fire(
                'Error',
                'Terdapat kesalahan pada sistem.',
                'error'
              )
              dataGaleri();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  }
</script>