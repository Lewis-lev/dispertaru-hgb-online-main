<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<section class="page-intro">
    <div class="site-container">
        <span class="eyebrow">AKUN PEMOHON</span>
        <h1>Permohonan saya</h1>
        <p>Riwayat permohonan yang dikirim menggunakan <?= esc(session()->get('userEmail')) ?>.</p>
    </div>
</section>

<section class="site-container account-section">
    <div class="account-heading">
        <div>
            <h2>Riwayat pengajuan</h2>
            <p>Data identitas dan dokumen diisi kembali untuk setiap pengajuan.</p>
        </div>
        <a class="button button-primary" href="<?= base_url('form') ?>">Ajukan permohonan baru</a>
    </div>
    <?php if ($applications === []) : ?>
        <div class="account-empty">
            <h3>Belum ada permohonan</h3>
            <p>Mulai pengajuan pertama Anda. Pastikan seluruh data dan dokumen sudah siap.</p>
            <a class="button button-primary" href="<?= base_url('form') ?>">Mulai pengajuan</a>
        </div>
    <?php else : ?>
        <div class="account-applications">
            <?php foreach ($applications as $application) : ?>
                <?php
                    $statusLabels = ['Belum diproses', 'Sedang diproses', 'Selesai'];
                    $applicationStatus = (int) $application['status'];
                    $statusClass = ['status-pending', 'status-progress', 'status-done'][$applicationStatus] ?? 'status-pending';
                ?>
                <article class="account-application">
                    <div class="account-application-main">
                        <span class="eyebrow">KODE PERMOHONAN</span>
                        <strong class="account-code"><?= esc($application['kdpermohonan']) ?></strong>
                        <small>Dikirim <?= esc(date('d M Y, H:i', strtotime($application['tgl_masuk']))) ?></small>
                    </div>
                    <span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabels[$applicationStatus] ?? 'Belum diproses') ?></span>
                    <a class="account-check" href="<?= base_url('cek-dokumen?kode=' . rawurlencode($application['kdpermohonan'])) ?>">Lihat status</a>
                </article>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</section>

<?= $this->endSection() ?>
