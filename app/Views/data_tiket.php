<option value="">-- Pilih Jenis --</option>
<?php foreach ($tiket as $t) : ?>
  <option value="<?= $t['id']; ?>"><?= $t['kategori']; ?> | Rp. <?= number_format($t['harga'], 0, ",", "."); ?></option>
<?php endforeach; ?>