<?= $this->extend('template/main'); ?>

<?= $this->section('isi'); ?>

<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      <button class="btn btn-warning" onclick="window.location = '<?= site_url('/admin/transaksioffline/index'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
    </h3>

    <div class="card-tools">
      <button class="btn btn-warning" onclick="return window.location.reload()"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div class="card-body">

    <div class="row">
      <div class="col-lg-4">
        <div class="form-group">
          <label for="">No. Transaksi</label>
          <input type="text" name="transofflineid" id="transofflineid" class="form-control" value="<?= $transofflineid; ?>" readonly>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="form-group">
          <label for="">Tanggal Transaksi</label>
          <input type="date" name="tgltransaksi" id="tgltransaksi" class="form-control" value="<?= date('Y-m-d'); ?>">
        </div>
      </div>
      <div class="col-lg-4">
        <div class="form-group">
          <label for="">Cari Pelanggan</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Nama Pelanggan" name="namapelanggan" id="namapelanggan" readonly>
            <input type="hidden" name="pengunjungid" id="pengunjungid">
            <div class="input-group-append">
              <button class="btn btn-outline-primary" type="button" id="tombolCariPelanggan" name="tombolCariPelanggan" title="Cari Pelanggan"> <i class="fa fa-search"></i> </button>
              <button class="btn btn-outline-success" type="button" id="tombolTambahPelanggan" name="tombolTambahPelanggan" title="Tambah Pelanggan"> <i class="fa fa-plus-square"></i> </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- cari tiket -->
    <div class="row">
      <div class="col-lg-3">
        <div class="form-group">
          <label for="">Tempat wisata</label>
          <div class="input-group mb-3">
            <input type="hidden" id="wisataid" value="">
            <select name="tempat_wisata" id="tempat_wisata" class="form-control" required>
              <option value="">-- Pilih wisata --</option>
              <?php foreach ($wisata as $t) : ?>
                <option value="<?= $t['id']; ?>"><?= $t['nama_wisata']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <label for="">Kategori Tiket</label>
          <div class="input-group mb-3">
            <input type="hidden" id="kategoriid" value="">
            <select name="kategori_tiket" id="kategori_tiket" class="form-control" required disabled>

            </select>
          </div>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="form-group">
          <label for="">Harga Tiket</label>
          <input type="number" name="hargatiket" id="hargatiket" class="form-control" readonly>
        </div>
      </div>
      <div class="col-lg-1">
        <div class="form-group">
          <label for="">Qty</label>
          <input type="number" name="jml" id="jml" class="form-control" value="1">
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <label for="">#</label>
          <div class="input-group mb-3">
            <button type="button" class="btn btn-success" title="Simpan Item" id="tombolSimpanItem">
              <i class="fa fa-save"></i>
            </button> &nbsp;
            <button type="button" class="btn btn-info" title="Selesai Transaksi" id="tombolSelesaiTransaksi">
              Selesai Transaksi
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 tampilDataTemp">

      </div>
    </div>

    <div class="viewmodal" style="display: none;"></div>

  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  function tampilDataTemp() {
    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/transaksioffline/tampilDataTemp'); ?>",
      data: {
        transofflineid: $('#transofflineid').val()
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.tampilDataTemp').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function cekID(tanggal) {
    $.ajax({
      type: "get",
      url: `<?= site_url('/admin/transaksioffline/cekID'); ?>/${tanggal}`,
      dataType: "json",
      success: function(response) {
        if (response.transofflineid) {
          $('#transofflineid').val(response.transofflineid);
          tampilDataTemp();
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function clearInput() {
    $('#kategori_tiket').val("");
    $('#kategori_tiket').attr("disabled", true);
    $('#kategoriid').val("");
    $('#tempat_wisata').val("");
    $('#wisataid').val("");
    $('#hargatiket').val("");
    $('#jml').val(1);
  }

  $('#tgltransaksi').change(function(e) {
    e.preventDefault();
    let tanggal = $(this).val();
    cekID(tanggal);
  });

  $('#tempat_wisata').change(function(e) {
    e.preventDefault();
    let id = $(this, 'option:selected').val();
    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/transaksioffline/tempatWisata'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.kategori_tiket) {
          $('#kategori_tiket').html(response.kategori_tiket);
          $('#kategori_tiket').removeAttr('disabled');
          $('#wisataid').val(id);
        } else {
          $('#kategori_tiket').html("");
          $('#kategori_tiket').attr('disabled', true);
          $('#wisataid').val("");
        }
        $('#hargatiket').val("");
        $('#kategoriid').val("");
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  $('#kategori_tiket').change(function(e) {
    e.preventDefault();
    let id = $(this, 'option:selected').val();
    // let id = this.value;
    // console.log(id);
    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/transaksioffline/kategori_tiket'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.hargatiket) {
          $('#hargatiket').val(response.hargatiket);
          $('#kategoriid').val(id);
        } else {
          $('#hargatiket').val("");
          $('#kategoriid').val("");
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  $('#tombolSimpanItem').click(function(e) {
    e.preventDefault();
    let transofflineid = $('#transofflineid').val();
    let kategoriid = $('#kategoriid').val();
    let wisataid = $('#wisataid').val();
    let hargatiket = $('#hargatiket').val();
    let jml = $('#jml').val();
    if (!transofflineid || !kategoriid || !hargatiket || !jml || !wisataid) {
      Swal.fire('Error', 'Semua input harus terisi !', 'error');
      return false;
    }
    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/transaksioffline/simpanItem'); ?>",
      data: {
        transofflineid: $('#transofflineid').val(),
        kategoriid: $('#kategoriid').val(),
        wisataid: $('#wisataid').val(),
        hargatiket: $('#hargatiket').val(),
        jml: $('#jml').val(),
      },
      dataType: "json",
      success: function(response) {
        if (response.success) {
          Swal.fire("Sukses", response.success, 'success');
          clearInput();
          tampilDataTemp();
        }
        if (response.error) {
          Swal.fire("Error", response.error, 'error');
          clearInput();
          tampilDataTemp();
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  $('#tombolCariPelanggan').click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "get",
      url: "<?= site_url('/admin/users/modalpelanggan'); ?>",
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').show();
          $('.viewmodal').html(response.data);
          $('#modalpelanggan').modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  $(document).ready(function() {
    tampilDataTemp();
  });

  $('#tombolSelesaiTransaksi').click(function(e) {
    e.preventDefault();
    let transofflineid = $('#transofflineid').val();
    let created_at = $('#tgltransaksi').val();
    let pengunjungid = $('#pengunjungid').val();

    if (!transofflineid || !created_at || !pengunjungid || !kategoriid || !hargatiket || !jml) {
      Swal.fire("Error", "Semua inputan harus terisi !", 'error');
      return false;
    }

    $.ajax({
      type: "post",
      url: "<?= site_url('/admin/transaksioffline/modalpembayaran'); ?>",
      data: {
        pengunjungid: pengunjungid,
        transofflineid: transofflineid,
        created_at: created_at
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data);
          $('.viewmodal').show();
          $('#modalpembayaran').modal('show');
        }

        if (response.error) {
          Swal.fire('Error', response.error, 'error');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
    // $.ajax({
    //   type: "post",
    //   url: "<?= site_url('/admin/transaksioffline/simpantransaksi'); ?>",
    //   data: {

    //   },
    //   dataType: "json",
    //   beforeSend: () => {
    //     $('#tombolSelesaiTransaksi').html('<i class="fa fa-spinner fa-spin"></i>');
    //     $('#tombolSelesaiTransaksi').attr('disabled', true);
    //   },
    //   complete: () => {
    //     $('#tombolSelesaiTransaksi').html('Selesai Transaksi');
    //     $('#tombolSelesaiTransaksi').removeAttr('disabled');
    //   },
    //   success: function(response) {

    //   },
    //   error: function(xhr, ajaxOptions, thrownError) {
    //     alert(xhr.status + '\n' + thrownError);
    //   }
    // });
  });
</script>


<?= $this->endSection('isi'); ?>