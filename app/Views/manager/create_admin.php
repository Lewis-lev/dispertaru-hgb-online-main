<?php
    $formErrors = session()->getFlashdata('form_errors') ?? [];
    $errorMessage = session()->getFlashdata('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0c3158">
    <title>Buat akun petugas | Dispertaru DIY</title>
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
            <a class="action-link detail-back" href="<?= base_url('manager/admins') ?>">← Kembali ke akun petugas</a>
            <section class="admin-page-heading">
                <div>
                    <span class="eyebrow">MANAJEMEN AKUN</span>
                    <h1>Buat akun petugas</h1>
                    <p>Akun baru hanya dapat dibuat oleh manajer.</p>
                </div>
            </section>
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
            <section class="admin-card manager-create-card">
                <form action="<?= base_url('manager/admins') ?>" method="post" class="manager-create-form">
                    <?= csrf_field() ?>
                    <div class="field">
                        <label for="username">Nama pengguna</label>
                        <input type="text" id="username" name="username" value="<?= esc(old('username')) ?>" minlength="3" maxlength="100" autocomplete="username" required autofocus>
                        <small>Gunakan 3–100 karakter. Nama pengguna tidak boleh sama dengan akun manajer.</small>
                    </div>
                    <div class="field">
                        <label for="password">Kata sandi sementara</label>
                        <input type="password" id="password" name="password" maxlength="128" autocomplete="new-password" required>
                        <small>Kata sandi petugas dapat menggunakan satu karakter atau lebih. Berikan secara aman.</small>
                    </div>
                    <div class="field">
                        <label for="password_confirm">Konfirmasi kata sandi</label>
                        <input type="password" id="password_confirm" name="password_confirm" maxlength="128" autocomplete="new-password" required>
                    </div>
                    <div class="manager-create-actions">
                        <a class="button button-outline" href="<?= base_url('manager/admins') ?>">Batal</a>
                        <button class="button button-primary" type="submit">Buat akun petugas</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
    <footer class="admin-footer"><div class="site-container">© <?= date('Y') ?> Dinas Pertanahan dan Tata Ruang DIY</div></footer>
</body>
</html>
