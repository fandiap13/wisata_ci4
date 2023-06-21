<div class="modal" id="modalpembayaran">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Pembayaran</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('/admin/transaksioffline/simpanTransaksi'); ?>" method="POST" class="formpembayaran">
        <?= csrf_field(); ?>
        <input type="hidden" name="tgltransaksi" value="<?= $created_at; ?>">
        <input type="hidden" name="pengunjungid" value="<?= $pengunjungid; ?>">
        <div class="modal-body">
          <div class="form-group">
            <label for="">ID Transaksi</label>
            <input type="text" name="idtransaksi" value="<?= $transofflineid; ?>" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="">Total Bayar</label>
            <input type="text" name="totalbayar" id="totalbayar" class="form-control" value="<?= $totalbayar; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="">Dibayar</label>
            <input type="text" name="dibayar" id="dibayar" class="form-control">
          </div>
          <div class="form-group">
            <label for="">Kembalian</label>
            <input type="text" name="kembalian" id="kembalian" class="form-control" readonly>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success btnSimpan"><i class="fa fa-save"></i> Simpan Transaksi</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script src="<?= base_url('dist/js/autoNumeric.js'); ?>"></script>
<script>
  $(document).ready(function() {
    $('#totalbayar').autoNumeric('init', {
      mDec: 0,
      aSep: '.',
      aDec: ','
    });
    $('#dibayar').autoNumeric('init', {
      mDec: 0,
      aSep: '.',
      aDec: ','
    });
    $('#kembalian').autoNumeric('init', {
      mDec: 0,
      aSep: '.',
      aDec: ','
    });

    // input jumlah uang
    $('#dibayar').keyup(function(e) {
      let totalbayar = $('#totalbayar').autoNumeric('get');
      let dibayar = $('#dibayar').autoNumeric('get');
      let sisauang;

      if (parseInt(dibayar) < parseInt(totalbayar)) {
        sisauang = 0;
      } else {
        sisauang = parseInt(dibayar) - parseInt(totalbayar);
      }
      $('#kembalian').autoNumeric('set', sisauang);
    });

    $('.formpembayaran').submit(function(e) {
      e.preventDefault();

      let dibayar = $('#dibayar').autoNumeric('get');
      let kembalian = $('#kembalian').autoNumeric('get');
      let totalbayar = $('#totalbayar').autoNumeric('get');
      if (!dibayar || !kembalian) {
        Swal.fire('Error', "Semua inputan harus terisi", 'error');
        return false;
      }

      if (dibayar < totalbayar) {
        Swal.fire('Error', "Pembayaran anda masih kurang", 'error');
        return false;
      }

      let form = $('.formpembayaran')[0];
      let data = new FormData(form);
      data.append('dibayar', dibayar);
      data.append('kembalian', kembalian);
      data.append('totalbayar', totalbayar);

      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        dataType: "json",
        beforeSend: function() {
          $('.btnSimpan').prop('disabled', true);
          $('.btnSimpan').html('<i class="fa fa-spin fa-spinner"></i>');
        },
        complete: function() {
          $('.btnSimpan').prop('disabled', false);
          $('.btnSimpan').html('Simpan');
        },
        success: function(response) {
          console.log(response);
          if (response.error) {
            Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
          }

          if (response.success) {
            Swal.fire({
              title: 'Cetak Transaksi',
              text: response.success,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Cetak !',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (result.isConfirmed) {
                let windowCetak = window.open(response.cetaktransaksi, "Cetak Faktur Barang Keluar", "width=300,height=500");
                windowCetak.focus();
                window.location.reload();
              } else {
                window.location.reload();
              }
            });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });

      return false;
    });
  });
</script>