<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Layanan informasi dan pengajuan perpanjangan Hak Guna Bangunan dan Hak Pakai.">
    <meta name="theme-color" content="#0c3158">
    <title><?= esc($pageTitle ?? 'Layanan Pertanahan DIY') ?> | Dispertaru DIY</title>
    <link rel="icon" href="<?= base_url('user/image/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('user/css/main.css') ?>">
</head>

<body>
    <header class="site-header">
        <div class="topline">
            <div class="site-container topline-inner">
                <span>Layanan pertanahan daerah</span>
                <span>Daerah Istimewa Yogyakarta</span>
            </div>
        </div>
        <nav class="site-nav" aria-label="Navigasi utama">
            <div class="site-container nav-inner">
                <a class="brand" href="<?= base_url('/') ?>">
                    <img src="<?= base_url('user/image/logo.png') ?>" alt="" width="42" height="48">
                    <span><strong>DISPERTARU DIY</strong><small>Layanan Perpanjangan HGB / Hak Pakai</small></span>
                </a>
                <div class="nav-links">
                    <a class="<?= url_is('/') ? 'active' : '' ?>" href="<?= base_url('/') ?>">Beranda</a>
                    <?php if (session()->get('userLoggedIn') === true) : ?>
                        <a class="<?= url_is('form') ? 'active' : '' ?>" href="<?= base_url('form') ?>">Ajukan permohonan</a>
                        <a class="<?= url_is('account') ? 'active' : '' ?>" href="<?= base_url('account') ?>">Akun saya</a>
                        <form class="nav-logout" action="<?= base_url('logout') ?>" method="post" data-confirm="Apakah Anda yakin ingin keluar?">
                            <?= csrf_field() ?>
                            <button type="submit" aria-label="Keluar" title="Keluar">
                                <img src="<?= base_url('user/image/logout-icon.svg') ?>" alt="">
                                <span>Keluar</span>
                            </button>
                        </form>
                    <?php else : ?>
                        <a class="<?= url_is('login') ? 'active' : '' ?>" href="<?= base_url('login') ?>">Masuk</a>
                    <?php endif ?>
                    <a class="<?= url_is('cek-dokumen') ? 'active' : '' ?>" href="<?= base_url('cek-dokumen') ?>">Cek status</a>
                </div>
            </div>
        </nav>
    </header>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="site-container flash-area">
            <div class="notice notice-error" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('application_code')) : ?>
        <div class="site-container flash-area">
            <div class="notice notice-success" role="status">
                <strong>Permohonan berhasil dikirim.</strong>
                <span>Simpan kode permohonan ini untuk mengecek status Anda:</span>
                <code><?= esc(session()->getFlashdata('application_code')) ?></code>
            </div>
        </div>
    <?php endif ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="site-footer">
        <div class="site-container footer-inner">
            <div>
                <strong>Dinas Pertanahan dan Tata Ruang DIY</strong>
                <p>Layanan informasi permohonan perpanjangan dan pembaruan HGB / Hak Pakai.</p>
            </div>
            <span>© <?= date('Y') ?> shofa.</span>
        </div>
    </footer>
    <script src="<?= base_url('user/js/confirm-actions.js') ?>" defer></script>
</body>

</html>
