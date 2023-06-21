<option value="">-- Pilih --</option>
<?php foreach ($kategori as $w) : ?>
  <option value="<?= $w['id']; ?>"><?= $w['kategori']; ?></option>
<?php endforeach; ?>