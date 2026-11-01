<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<section class="page-intro">
    <div class="site-container">
        <span class="eyebrow">LAYANAN PENGAJUAN</span>
        <h1>Formulir permohonan</h1>
        <p>Lengkapi data pemohon dan unggah dokumen pendukung untuk memulai proses.</p>
    </div>
</section>

<section class="site-container form-section">
    <form class="application-form" action="<?= base_url('simpan') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($error = session()->getFlashdata('error')) : ?>
            <div class="notice notice-error" role="alert">
                <?= esc($error) ?>
            </div>
        <?php endif ?>
        <?php $errors = session()->getFlashdata('form_errors') ?? []; ?>
        <?php if ($errors !== []) : ?>
            <div class="notice notice-error" role="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <div class="form-heading">
            <span class="form-step">01</span>
            <div>
                <h2>Data pemohon</h2>
                <p>Isi sesuai identitas dan pastikan kontak dapat dihubungi.</p>
            </div>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="nama">Nama lengkap <span>*</span></label>
                <input type="text" id="nama" name="nama" value="<?= esc(old('nama')) ?>" maxlength="255" autocomplete="name" required>
            </div>
            <div class="field">
                <label for="nik">NIK <span>*</span></label>
                <input type="text" id="nik" name="nik" value="<?= esc(old('nik')) ?>" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" minlength="16" autocomplete="off" required>
                <small>Masukkan 16 angka sesuai KTP.</small>
            </div>
            <div class="field">
                <label for="email">Email aktif <span>*</span></label>
                <input type="email" id="email" name="email" value="<?= esc(old('email', session()->get('userEmail'))) ?>" maxlength="255" autocomplete="email" required>
            </div>
            <div class="field">
                <label for="no_hp">Nomor handphone <span>*</span></label>
                <input type="tel" id="no_hp" name="no_hp" value="<?= esc(old('no_hp')) ?>" inputmode="tel" maxlength="20" autocomplete="tel" required>
            </div>
            <div class="field">
                <label for="tempat_lahir">Tempat lahir <span>*</span></label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?= esc(old('tempat_lahir')) ?>" maxlength="255" required>
            </div>
            <div class="field">
                <label for="tanggal_lahir">Tanggal lahir <span>*</span></label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?= esc(old('tanggal_lahir')) ?>" max="<?= date('Y-m-d') ?>" autocomplete="bday" aria-describedby="tanggal-lahir-help" required>
                <small id="tanggal-lahir-help">Pilih tanggal lahir menggunakan kalender. Tanggal tidak boleh melewati hari ini.</small>
            </div>
            <div class="field">
                <label for="pekerjaan">Pekerjaan <span>*</span></label>
                <input type="text" id="pekerjaan" name="pekerjaan" value="<?= esc(old('pekerjaan')) ?>" maxlength="255" autocomplete="organization-title" required>
            </div>
            <div class="field field-wide">
                <label for="alamat">Alamat lokasi tanah <span>*</span></label>
                <textarea id="alamat" name="alamat" rows="3" maxlength="255" required><?= esc(old('alamat')) ?></textarea>
            </div>
        </div>

        <div class="form-heading upload-heading">
            <span class="form-step">02</span>
            <div>
                <h2>Dokumen pendukung</h2>
                <p>Unggah berkas yang jelas dan dapat dibuka.</p>
            </div>
        </div>
        <div class="form-grid upload-grid">
            <div class="field upload-field">
                <label for="ktp">Foto KTP <span>*</span></label>
                <input type="file" id="ktp" name="ktp" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required>
                <small>Format JPG atau PNG, maksimal 5 MB.</small>
            </div>
            <div class="field upload-field">
                <label for="file_permohonan">Dokumen permohonan <span>*</span></label>
                <input type="file" id="file_permohonan" name="file_permohonan" accept=".pdf,application/pdf" required>
                <small>Format PDF, maksimal 10 MB.</small>
            </div>
        </div>

        <div class="form-notice">
            <span aria-hidden="true">i</span>
            <p>Periksa kembali data sebelum mengirim. Setelah berhasil, kode permohonan akan ditampilkan satu kali, simpan kode tersebut untuk mengecek status.</p>
        </div>
        <div class="form-submit">
            <a class="text-link" href="<?= base_url('/') ?>">Kembali ke beranda</a>
            <button class="button button-primary" type="submit">Kirim permohonan <span aria-hidden="true">→</span></button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>
