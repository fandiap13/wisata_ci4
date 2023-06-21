<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col" style="width: 5%;">#</th>
      <th scope="col">Tempat Wisata</th>
      <th scope="col">Kategori Tiket</th>
      <th scope="col">Harga Tiket (Rp)</th>
      <th scope="col">Qty</th>
      <th scope="col">Subtotal (Rp)</th>
      <th scope="col" style="width: 15%;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    foreach ($detail as $key => $value) : ?>
      <tr>
        <th scope="row"><?= $i; ?></th>
        <td><?= $value['nama_wisata']; ?></td>
        <td><?= $value['kategori']; ?></td>
        <td class="text-right"><?= number_format($value['hargatiket'], 0, ",", "."); ?></td>
        <td class="text-center"><?= $value['jml']; ?></td>
        <td class="text-right"><?= number_format($value['jml'] * $value['hargatiket'], 0, ",", "."); ?></td>
        <td>
          <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem('<?= $value['id']; ?>')"><i class="fa fa-trash-alt"></i></button>
        </td>
      </tr>
    <?php
      $i++;
    endforeach; ?>
  </tbody>
</table>

<script>
  function hapusItem(id) {
    Swal.fire({
      title: 'Hapus',
      text: `Apakah anda yakin menghapus item ini ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Tidak',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= site_url('/admin/transaksioffline/hapusItem'); ?>",
          data: {
            id: id
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success')
              tampilDataTemp();
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error')
              tampilDataTemp();
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