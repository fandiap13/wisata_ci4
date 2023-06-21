<div class="modal" tabindex="-1" id="modalpembayaran">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal Pembayaran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('/transaksi/pembayaran'); ?>" method="POST" class="formpembayaran" enctype="multipart/form-data">
        <div class="modal-body">
          <p>Silahkan melakukan pembayaran sebesar <b>Rp. <?= number_format($totalbayar, 0, ",", "."); ?></b> ke nomor rekening <b><?= $norek; ?></b></p>
          <div class="form-group">
            <label for="">ID Transaksi</label>
            <input type="text" name="transonlineid" value="<?= $transaksi['id']; ?>" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="">Total Bayar (Rp)</label>
            <input type="text" name="totalbayar" value="<?= number_format($totalbayar, 0, ",", "."); ?>" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="">Upload Bukti Pembayaran</label>
            <div class="row">
              <div class="col-4">
                <?php if ($transaksi['buktipembayaran'] !== NULL && $transaksi['buktipembayaran'] !== "") { ?>
                  <img src="<?= base_url($transaksi['buktipembayaran']); ?>" class="img-thumbnail img-preview w-100">
                <?php } else { ?>
                  <img src="<?= base_url(); ?>/gambar/default.png" class="img-thumbnail img-preview w-100">
                <?php } ?>
              </div>
              <div class="col-8">
                <input type="hidden" name="buktipembayaran_lama" value="<?= $transaksi['buktipembayaran']; ?>">
                <input type="file" id="buktipembayaran" name="buktipembayaran" class="form-control">
                <div class="invalid-feedback error_buktipembayaran"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <!-- data-dismiss="modal" -->
          <button type="button" class="btn btn-secondary btnClose" onclick="closeModal()">Close</button>
          <button type="submit" class="btn btn-primary btnKirim"><i class="fa fa-save"></i> Kirim</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $('input[name=buktipembayaran]').change(function(e) {
    e.preventDefault();
    const foto = this;
    const imgPreview = this.parentElement.parentElement.querySelector(".img-preview");

    const fileFoto = new FileReader();
    fileFoto.readAsDataURL(foto.files[0]);
    fileFoto.onload = function(e) {
      imgPreview.src = e.target.result;
    }
  });

  $('.formpembayaran').submit(function(e) {
    e.preventDefault();

    let form = $('.formpembayaran')[0];
    let data = new FormData(form);

    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action'),
      data: data,
      enctype: 'multipart/form-data',
      cache: false,
      processData: false,
      contentType: false,
      dataType: "json",
      beforeSend: () => {
        $('.btnKirim').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnKirim').attr('disabled', true);
      },
      complete: () => {
        $('.btnKirim').html('<i class="fa fa-save"></i> Kirim');
        $('.btnKirim').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
        }
        if (response.errors) {
          if (response.errors.buktipembayaran) {
            $('.error_buktipembayaran').html(response.errors.buktipembayaran);
            $('input[name=buktipembayaran]').addClass('is-invalid');
          } else {
            $('input[name=buktipembayaran]').removeClass('is-invalid');
            $('.error_buktipembayaran').html('');
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  // $(document).ready(function() {
  //   $('.btnClose').click(function(e) {
  //     e.preventDefault();
  //     $('#modalpembayaran').on('shown.bs.modal', function(e) {
  //       $("#modalpembayaran").modal('hide');
  //     })

  //     // $("#modalpembayaran").removeClass("in");
  //     // $(".modal-backdrop").remove();
  //     // $("#modalpembayaran").hide();

  //     // $("#modalpembayaran").modal("hide");
  //     // e.stopPropagation(); //This line would take care of it
  //   });
  // });
</script>