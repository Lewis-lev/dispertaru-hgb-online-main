<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0c3158">
    <title><?= esc($pageTitle ?? 'Panel petugas') ?> | Dispertaru DIY</title>
    <link rel="icon" href="<?= base_url('user/image/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('user/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/css/admin.css') ?>">
</head>

<body class="admin-body">
    <header class="admin-header">
        <div class="site-container admin-nav">
            <a class="brand" href="<?= base_url('administrator') ?>">
                <img src="<?= base_url('user/image/logo.png') ?>" alt="" width="42" height="48">
                <span><strong>DISPERTARU DIY</strong><small>Panel pengelolaan permohonan</small></span>
            </a>
            <div class="admin-account">
                <span>Petugas: <strong><?= esc(session()->get('username')) ?></strong></span>
                <a class="button button-outline" href="<?= base_url('admin/password') ?>">Ganti kata sandi</a>
                <form action="<?= base_url('admin/logout') ?>" method="post" data-confirm="Apakah Anda yakin ingin keluar?">
                    <?= csrf_field() ?>
                    <button class="button button-logout" type="submit">
                        <img src="<?= base_url('user/image/logout-icon.svg') ?>" alt="">
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="admin-main">
        <div class="site-container admin-container">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="notice notice-error admin-notice" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif ?>
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="notice notice-success admin-notice" role="status"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="site-container">© <?= date('Y') ?> Dinas Pertanahan dan Tata Ruang DIY</div>
    </footer>
    <script src="<?= base_url('admin/js/admin.js') ?>" defer></script>
    <script src="<?= base_url('user/js/confirm-actions.js') ?>" defer></script>
</body>

</html>
