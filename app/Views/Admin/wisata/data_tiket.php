<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col" style="width: 50%;">Kategori</th>
        <th scope="col">Harga (Rp)</th>
        <th scope="col">Status</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($tiket as $t) : ?>
        <tr>
          <th scope="row"><?= $i; ?>. </th>
          <td><?= $t['kategori']; ?></td>
          <td class="text-right"><?= number_format($t['harga'], 0, ',', '.'); ?></td>
          <td><?= $t['status_tiket'] == 0 ? ' <span class="badge badge-danger">Habis</span>' : ' <span class="badge badge-success">Tersedia</span>'; ?></td>
          <td>
            <div class="btn-group">
              <button type="button" class="btn btn-sm btn-warning" onclick="editTiket('<?= $t['id']; ?>', '<?= $t['kategori']; ?>', '<?= $t['harga']; ?>', <?= $t['status_tiket']; ?>)"><i class="fa fa-edit"></i> Edit</button>
              <button type="button" class="btn btn-sm btn-danger" onclick="hapusTiket('<?= $t['id']; ?>')"><i class="fa fa-trash-alt"></i> Hapus</button>
            </div>
          </td>
        </tr>
      <?php
        $i++;
      endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  function hapusTiket(id) {
    Swal.fire({
      title: 'Hapus',
      text: "Apakah anda yakin menghapus tiket ini ?",
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
          url: "<?= site_url('/admin/wisata/hapusTiket'); ?>",
          data: {
            id: id
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire(
                'Sukses',
                response.success,
                'success'
              )
              clearTiket();
              dataTiket();
            }

            if (response.error) {
              Swal.fire(
                'Error',
                response.error,
                'error'
              )
              clearTiket();
              dataTiket();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  }

  // ketika edit di klik
  function editTiket(id, kategori, harga, status_tiket) {
    $('.formtiket').attr('action', '<?= site_url('/admin/wisata/editTiket'); ?>');
    $('.btnTiket').html('<i class="fa fa-save"></i> Edit tiket');
    $('input[name=tiketid]').val(id);
    $('input[name=kategori]').val(kategori);
    $('input[name=harga]').val(harga);
    if (status_tiket == 0) {
      $('select[name=status_tiket]').html(`
      <option value="">-- Pilih status --</option>
      <option value="0" selected>Habis</option>
      <option value="1">Tersedia</option>
    `);
    } else {
      $('select[name=status_tiket]').html(`
      <option value="">-- Pilih status --</option>
      <option value="0">Habis</option>
      <option value="1" selected>Tersedia</option>
    `);
    }
  }
</script>