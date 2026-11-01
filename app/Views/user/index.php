<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<section class="hero">
    <div class="site-container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">PELAYANAN PERTANAHAN DIY</span>
            <h1>Perpanjangan hak, <span>lebih mudah dipantau.</span></h1>
            <p>Ajukan perpanjangan atau pembaruan Hak Guna Bangunan dan Hak Pakai secara daring, lalu pantau prosesnya dengan kode permohonan Anda.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="<?= base_url('form') ?>">Mulai pengajuan <span aria-hidden="true">→</span></a>
                <a class="button button-secondary" href="<?= base_url('cek-dokumen') ?>">Cek status permohonan</a>
            </div>
            <div class="hero-note"><span class="note-mark" aria-hidden="true">i</span> Siapkan data pemohon, foto KTP, dan dokumen permohonan dalam format PDF.</div>
        </div>
        <div class="hero-art" aria-label="Informasi layanan perpanjangan hak tanah">
            <div class="art-orbit orbit-one"></div>
            <div class="art-orbit orbit-two"></div>
            <div class="art-card">
                <span class="art-label">LAYANAN DIGITAL</span>
                <div class="art-icon" aria-hidden="true">⌂</div>
                <h2>Hak atas tanah<br>terjaga, proses<br>lebih transparan.</h2>
                <div class="art-rule"></div>
                <span>HGB <i>•</i> Hak Pakai <i>•</i> DIY</span>
            </div>
            <div class="art-stamp" aria-hidden="true">DIY</div>
        </div>
    </div>
</section>

<section class="section section-process">
    <div class="site-container">
        <div class="section-heading">
            <span class="eyebrow">LANGKAH PENGAJUAN</span>
            <h2>Alur permohonan</h2>
            <p>Ikuti proses berikut untuk mengirim dan memantau permohonan Anda.</p>
        </div>
        <div class="process-grid">
            <article class="process-card">
                <span class="process-number">01</span>
                <h3>Siapkan berkas</h3>
                <p>Lengkapi data pemohon, foto KTP yang terbaca, dan surat permohonan dalam format PDF.</p>
            </article>
            <article class="process-card">
                <span class="process-number">02</span>
                <h3>Kirim permohonan</h3>
                <p>Isi formulir daring dan unggah dokumen. Pastikan informasi yang diberikan sudah benar.</p>
            </article>
            <article class="process-card">
                <span class="process-number">03</span>
                <h3>Pantau status</h3>
                <p>Simpan kode permohonan yang ditampilkan setelah pengiriman untuk mengecek perkembangan.</p>
            </article>
        </div>
    </div>
</section>

<section class="section requirements-section">
    <div class="site-container requirements-grid">
        <div class="requirements-copy">
            <span class="eyebrow">SEBELUM MENGAJUKAN</span>
            <h2>Persiapkan dokumen lebih awal</h2>
            <p>Agar pengajuan berjalan lancar, pastikan berkas berikut tersedia dan informasinya sesuai.</p>
            <ul class="check-list">
                <li><span aria-hidden="true">✓</span> Foto atau scan KTP pemohon (JPG / PNG, maksimal 5 MB)</li>
                <li><span aria-hidden="true">✓</span> Surat atau dokumen permohonan (PDF, maksimal 10 MB)</li>
                <li><span aria-hidden="true">✓</span> Data diri dan kontak yang aktif</li>
            </ul>
            <a class="text-link" href="<?= base_url('form') ?>">Lihat formulir pengajuan <span aria-hidden="true">→</span></a>
        </div>
        <aside class="help-card">
            <span class="help-icon" aria-hidden="true">?</span>
            <h3>Perlu menyiapkan sesuatu?</h3>
            <p>Periksa kelengkapan dokumen sebelum mengirim. Simpan kode permohonan Anda untuk melihat status secara privat.</p>
            <a class="button button-light" href="<?= base_url('cek-dokumen') ?>">Cek permohonan</a>
        </aside>
    </div>
</section>

<?= $this->endSection() ?>
