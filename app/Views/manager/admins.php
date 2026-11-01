<?php
    $successMessage = session()->getFlashdata('success');
    $errorMessage = session()->getFlashdata('error');
    $formErrors = session()->getFlashdata('form_errors') ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0c3158">
    <title>Kelola petugas | Dispertaru DIY</title>
    <link rel="icon" href="<?= base_url('user/image/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('user/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/css/admin.css') ?>">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="site-container admin-nav">
            <a class="brand" href="<?= base_url('manager') ?>">
                <img src="<?= base_url('user/image/logo.png') ?>" alt="" width="42" height="48">
                <span><strong>DISPERTARU DIY</strong><small>Panel manajer</small></span>
            </a>
            <?= $this->include('manager/_navigation', ['activePage' => 'admins']) ?>
        </div>
    </header>
    <main class="admin-main">
        <div class="site-container admin-container">
            <section class="admin-page-heading">
                <div>
                    <span class="eyebrow">MANAJEMEN AKUN</span>
                    <h1>Akun petugas</h1>
                    <p>Kelola akses, nama pengguna, dan kata sandi petugas.</p>
                </div>
                <a class="button button-primary" href="<?= base_url('manager/admins/new') ?>">+ Buat akun petugas</a>
            </section>
            <?php if ($successMessage) : ?>
                <div class="notice notice-success manager-notice" role="status"><?= esc($successMessage) ?></div>
            <?php endif ?>
            <?php if ($errorMessage) : ?>
                <div class="notice notice-error manager-notice" role="alert"><?= esc($errorMessage) ?></div>
            <?php endif ?>
            <?php if ($formErrors !== []) : ?>
                <div class="notice notice-error manager-notice" role="alert">
                    <?php foreach ($formErrors as $formError) : ?>
                        <div><?= esc($formError) ?></div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            <p class="manager-help">Status online berdasarkan aktivitas petugas dalam lima menit terakhir. Menonaktifkan akun akan menghentikan akses pada permintaan berikutnya dan tetap menyimpan riwayat audit.</p>
            <section class="manager-admin-cards" aria-label="Daftar akun petugas">
                <?php foreach ($admins as $admin) : ?>
                    <?php
                        $lastSeen = $admin['last_seen_at'] === null ? false : strtotime($admin['last_seen_at']);
                        $isOnline = (int) $admin['is_active'] === 1
                            && $lastSeen !== false
                            && $lastSeen >= time() - 300;
                    ?>
                    <article class="manager-admin-card">
                        <div class="manager-admin-card-heading">
                            <div>
                                <span class="eyebrow">PETUGAS #<?= esc($admin['adminid']) ?></span>
                                <h2><?= esc($admin['username']) ?></h2>
                            </div>
                            <span class="account-state <?= (int) $admin['is_active'] === 1 ? 'account-state-active' : 'account-state-inactive' ?>">
                                <?= (int) $admin['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>
                        <div class="manager-presence">
                            <span class="presence-state <?= $isOnline ? 'presence-online' : 'presence-offline' ?>">
                                <?= $isOnline ? 'Online' : 'Offline' ?>
                            </span>
                            <small><?= $lastSeen === false ? 'Belum pernah aktif' : 'Terakhir aktif ' . esc(date('d M Y, H:i', $lastSeen)) ?></small>
                        </div>
                        <form action="<?= base_url('manager/admins/' . $admin['adminid']) ?>" method="post" class="manager-admin-edit">
                            <?= csrf_field() ?>
                            <div class="field">
                                <label for="admin-username-<?= esc($admin['adminid']) ?>">Nama pengguna</label>
                                <input id="admin-username-<?= esc($admin['adminid']) ?>" type="text" name="username" value="<?= esc($admin['username']) ?>" minlength="3" maxlength="100" required>
                            </div>
                            <div class="field">
                                <label for="admin-password-<?= esc($admin['adminid']) ?>">Kata sandi baru</label>
                                <input id="admin-password-<?= esc($admin['adminid']) ?>" type="password" name="password" maxlength="128" autocomplete="new-password" placeholder="Kosongkan jika tetap">
                            </div>
                            <button class="button button-outline" type="submit">Simpan perubahan</button>
                        </form>
                        <form action="<?= base_url('manager/admins/' . $admin['adminid'] . '/status') ?>" method="post" class="manager-admin-status" data-confirm="<?= esc(((int) $admin['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan') . ' akun ' . $admin['username'] . '?', 'attr') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="is_active" value="<?= (int) $admin['is_active'] === 1 ? '0' : '1' ?>">
                            <button class="button <?= (int) $admin['is_active'] === 1 ? 'button-danger' : 'button-primary' ?>" type="submit">
                                <?= (int) $admin['is_active'] === 1 ? 'Nonaktifkan akun' : 'Aktifkan akun' ?>
                            </button>
                        </form>
                    </article>
                <?php endforeach ?>
                <?php if ($admins === []) : ?>
                    <div class="admin-card manager-empty">
                        <h2>Belum ada akun petugas</h2>
                        <p>Buat akun petugas untuk mulai menerima permohonan.</p>
                        <a class="button button-primary" href="<?= base_url('manager/admins/new') ?>">Buat akun petugas</a>
                    </div>
                <?php endif ?>
            </section>
        </div>
    </main>
    <footer class="admin-footer"><div class="site-container">© <?= date('Y') ?> Dinas Pertanahan dan Tata Ruang DIY</div></footer>
</body>
</html>
