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
  <title>Laporan Transaksi Online</title>
</head>

<body onload="print()">

  <center>
    <h1><?= $setting['nama_web']; ?></h1>
    <h2>Laporan Transaksi Online</h2>
    <p>Periode: <?= date('d F Y', strtotime($_GET['tglawal'])); ?> sampai <?= date('d F Y', strtotime($_GET['tglakhir'])); ?></p>
    <hr style="border-top: 5px double black; margin-top: 25px; margin-bottom: 25px;">

    <table style=" width: 100%;" border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th style="width: 2%;">No.</th>
          <th>ID</th>
          <th>Tgl Transaksi</th>
          <th>Tgl Kunjungan</th>
          <th>Pengunjung</th>
          <th>Detail Pesanan</th>
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
            <td><?= date('d F Y H:i:s', strtotime($t['tgl_transaksi'])); ?></td>
            <td><?= date('d F Y', strtotime($t['tgl_kunjungan'])); ?></td>
            <td><?= $t['nama_user']; ?></td>
            <td>
              <?php
              $totalHarga = 0;
              $detail = $db->table('detail_transaksi_online')->select('detail_transaksi_online.*, tiket.kategori, wisata.nama_wisata')
                ->join('tiket', 'detail_transaksi_online.tiketid=tiket.id')
                ->join('wisata', 'tiket.wisataid=wisata.id')
                ->where('transonlineid', $t['id'])->get()->getResultArray();
              ?>
              <?php foreach ($detail as $d) : ?>
                <?= $d['nama_wisata']; ?>
                <ul>
                  <li>Kategori : <?= $d['kategori']; ?></li>
                  <li>Harga Tiket : <?= number_format($d['harga_tiket'], 0, ",", "."); ?></li>
                  <li>Jumlah : <?= $d['jml']; ?></li>
                  <li>Subtotal : <?= number_format($d['jml'] * $d['harga_tiket'], 0, ",", "."); ?></li>
                </ul>
              <?php
                $totalHarga += $d['jml'] * $d['harga_tiket'];
              endforeach; ?>
            </td>
            <td style="text-align: right;"><?= number_format($totalHarga, 0, ",", "."); ?></td>
          </tr>

        <?php
          $total += $totalHarga;
          $i++;
        endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="6" style="text-align: center;">Total Pemasukan</th>
          <td style="text-align: right;"><?= number_format($total, 0, ",", "."); ?></td>
        </tr>
      </tfoot>
    </table>

  </center>
</body>

</html>