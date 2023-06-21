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
  <title>Laporan Transaksi Offline</title>
</head>

<body onload="print()">

  <center>
    <h1><?= $setting['nama_web']; ?></h1>
    <h2>Laporan Transaksi Offline</h2>
    <p>Periode: <?= date('d F Y', strtotime($_GET['tglawal'])); ?> sampai <?= date('d F Y', strtotime($_GET['tglakhir'])); ?></p>
    <hr style="border-top: 5px double black; margin-top: 25px; margin-bottom: 25px;">

    <table style=" width: 100%;" border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th style="width: 2%;">No.</th>
          <th>ID</th>
          <th>Tgl Transaksi</th>
          <th>Kasir</th>
          <th>Pengunjung</th>
          <th>Detail Pesanan</th>
          <th>Dibayar</th>
          <th>Kembalian</th>
          <th>Total Bayar (Rp)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        $total = 0;
        foreach ($transaksi as $t) :
        ?>
          <tr>
            <td><?= $i; ?></td>
            <td><?= $t['id']; ?></td>
            <td><?= date('d F Y H:i:s', strtotime($t['created_at'])); ?></td>
            <td><?= $t['nama_kasir']; ?></td>
            <td><?= $t['nama_pembeli']; ?></td>
            <td>
              <?php
              $totalHarga = 0;
              $detail = $db->table('detail_transaksi_offline')->select('detail_transaksi_offline.*, tiket.kategori, wisata.nama_wisata')
                ->join('tiket', 'detail_transaksi_offline.tiketid=tiket.id')
                ->join('wisata', 'tiket.wisataid=wisata.id')
                ->where('transofflineid', $t['id'])->get()->getResultArray();
              ?>
              <?php foreach ($detail as $d) : ?>
                <?= $d['nama_wisata']; ?>
                <ul>
                  <li>Kategori : <?= $d['kategori']; ?></li>
                  <li>Harga Tiket : <?= number_format($d['hargatiket'], 0, ",", "."); ?></li>
                  <li>Jumlah : <?= $d['jml']; ?></li>
                  <li>Subtotal : <?= number_format($d['jml'] * $d['hargatiket'], 0, ",", "."); ?></li>
                </ul>
              <?php
                $totalHarga += $d['jml'] * $d['hargatiket'];
              endforeach; ?>
            </td>
            <td style="text-align: right;"><?= number_format($t['dibayar'], 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($t['kembalian'], 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($t['totalbayar'], 0, ",", "."); ?></td>
          </tr>

        <?php
          $total += $t['totalbayar'];
          $i++;
        endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="8" style="text-align: center;">Total Pemasukan</th>
          <td style="text-align: right;"><?= number_format($total, 0, ",", "."); ?></td>
        </tr>
      </tfoot>
    </table>

  </center>
</body>

</html>