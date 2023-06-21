<!-- Notifications Dropdown Menu -->
<li class="nav-item dropdown">
  <a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <span class="badge badge-warning navbar-badge jmlNotif1"><?= $jmlNotif; ?></span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-item dropdown-header">
      <?= $jmlNotif; ?> Notifications
    </span>
    <div class="dropdown-divider"></div>
    <a href="<?= site_url('/admin/transaksionline/index'); ?>" class="dropdown-item">
      <i class="fas fa-envelope mr-2"></i>
      <?= $jmlNotif; ?> Transaksi Baru
      <span class="float-right text-muted text-sm"><?= $time; ?></span>
    </a>
  </div>
</li>