<?php

$db = \Config\Database::connect();
$setting = $db->table('setting')->select('nama_web, no_wa')->limit(1)->orderBy('id', 'ASC')->get()->getRowArray();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Transaksi <?= $transaksi['id']; ?></title>
</head>

<body onload="print()">
  <center>
    <h2><?= $setting['nama_web']; ?></h2>
    <h3>Invoice Transaksi Offline <?= $transaksi['id']; ?></h3>
    <hr style="border-top: 5px double black; margin-top: 25px; margin-bottom: 25px;">
  </center>

  <table>
    <tr>
      <th style="text-align: left;">ID Transaksi</th>
      <td>:</td>
      <td><?= $transaksi['id']; ?></td>
    </tr>
    <tr>
      <th style="text-align: left;">Tanggal Transaksi</th>
      <td>:</td>
      <td><?= date('d F Y H:i:s', strtotime($transaksi['created_at'])); ?></td>
    </tr>
    <tr>
      <th style="text-align: left;">Pengunjung</th>
      <td>:</td>
      <td><?= $transaksi['nama_pembeli']; ?></td>
    </tr>
    <tr>
      <th style="text-align: left;">E-mail</th>
      <td>:</td>
      <td><?= $transaksi['email_user']; ?></td>
    </tr>
    <tr>
      <th style="text-align: left;">No. Wa</th>
      <td>:</td>
      <td><?= $transaksi['telp_user']; ?></td>
    </tr>
    <tr>
      <th style="text-align: left;">Kasir</th>
      <td>:</td>
      <td><?= $transaksi['nama_kasir']; ?></td>
    </tr>
  </table>

  <h3>Detail Transaksi</h3>

  <table style="width: 100%;" border="1" cellspacing="0">
    <tr>
      <th>No.</th>
      <th>Nama Wisata</th>
      <th>Kategori</th>
      <th>Jumlah Tiket</th>
      <th>Harga (Rp)</th>
      <th>Subtotal</th>
    </tr>
    <?php
    $i = 1;
    foreach ($detail as $d) : ?>
      <tr>
        <td><?= $i; ?></td>
        <td><?= $d['nama_wisata']; ?></td>
        <td><?= $d['kategori']; ?></td>
        <td><?= $d['jml']; ?></td>
        <td style="text-align: right;"><?= number_format($d['hargatiket'], 0, ",", "."); ?></td>
        <td style="text-align: right;"><?= number_format($d['hargatiket'] * $d['jml'], 0, ",", "."); ?></td>
      </tr>
    <?php
      $i++;
    endforeach ?>
    <tr>
      <th colspan="5">Total Bayar (Rp)</th>
      <td style="text-align: right;"><?= number_format($transaksi['totalbayar'], 0, ",", "."); ?></td>
    </tr>
    <tr>
      <th colspan="5">Dibayar (Rp)</th>
      <td style="text-align: right;"><?= number_format($transaksi['dibayar'], 0, ",", "."); ?></td>
    </tr>
    <tr>
      <th colspan="5">Kembalian (Rp)</th>
      <td style="text-align: right;"><?= number_format($transaksi['kembalian'], 0, ",", "."); ?></td>
    </tr>
  </table>
</body>

</html>