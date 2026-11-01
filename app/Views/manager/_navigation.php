<?php
    $activePage = $activePage ?? '';
?>
<div class="manager-header-tools">
    <nav class="manager-nav-links" aria-label="Navigasi manajer">
        <a class="<?= $activePage === 'activity' ? 'active' : '' ?>" href="<?= base_url('manager') ?>" <?= $activePage === 'activity' ? 'aria-current="page"' : '' ?>>Aktivitas</a>
        <a class="<?= $activePage === 'admins' ? 'active' : '' ?>" href="<?= base_url('manager/admins') ?>" <?= $activePage === 'admins' ? 'aria-current="page"' : '' ?>>Kelola petugas</a>
    </nav>
    <div class="admin-account">
        <span>Manajer: <strong><?= esc(session()->get('managerUsername')) ?></strong></span>
        <form action="<?= base_url('manager/logout') ?>" method="post" data-confirm="Apakah Anda yakin ingin keluar?">
            <?= csrf_field() ?>
            <button class="button button-logout" type="submit">
                <img src="<?= base_url('user/image/logout-icon.svg') ?>" alt="">
                <span>Keluar</span>
            </button>
        </form>
    </div>
    <script src="<?= base_url('user/js/confirm-actions.js') ?>" defer></script>
</div>
