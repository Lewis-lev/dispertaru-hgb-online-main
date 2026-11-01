<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<section class="page-intro">
    <div class="site-container">
        <span class="eyebrow">PEMANTAUAN PERMOHONAN</span>
        <h1>Cek status permohonan</h1>
        <p>Gunakan kode yang diberikan setelah permohonan berhasil dikirim.</p>
    </div>
</section>

<section class="site-container status-section">
    <form class="status-search" action="<?= base_url('cek-dokumen') ?>" method="get">
        <label for="kode">Kode permohonan</label>
        <div class="status-search-row">
            <input type="text" id="kode" name="kode" value="<?= esc($kode) ?>" placeholder="Contoh: P12AB34CD56EF7890" maxlength="17" pattern="P[A-Fa-f0-9]{16}" autocomplete="off" required>
            <button class="button button-primary" type="submit">Cari status <span aria-hidden="true">→</span></button>
        </div>
        <small>Kode terdiri dari huruf P dan 16 karakter. Kode bersifat pribadi, jangan bagikan kepada orang lain.</small>
    </form>

    <?php if ($data !== null) : ?>
        <?php
            $statusLabels = ['Belum diproses', 'Sedang diproses', 'Selesai'];
            $status = (int) $data['status'];
            $statusClass = ['status-pending', 'status-progress', 'status-done'][$status] ?? 'status-pending';
        ?>
        <article class="status-result">
            <div class="result-topline">
                <div>
                    <span class="eyebrow">HASIL PENCARIAN</span>
                    <h2><?= esc($data['kdpermohonan']) ?></h2>
                </div>
                <span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabels[$status] ?? 'Belum diproses') ?></span>
            </div>
            <div class="result-details">
                <div><span>Tanggal pengajuan</span><strong><?= esc(date('d M Y', strtotime($data['tgl_masuk']))) ?></strong></div>
                <div><span>Pembaruan terakhir</span><strong><?= esc(date('d M Y, H:i', strtotime($data['tgl_update']))) ?></strong></div>
            </div>
            <p class="result-note">Status dapat berubah setelah berkas diperiksa oleh petugas. Simpan kode permohonan untuk pengecekan berikutnya.</p>
        </article>
    <?php endif ?>
</section>

<?= $this->endSection() ?>
