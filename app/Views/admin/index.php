<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<section class="admin-page-heading">
    <div>
        <span class="eyebrow">MANAJEMEN LAYANAN</span>
        <h1>Permohonan masuk</h1>
        <p>Periksa data dan dokumen pemohon, lalu perbarui status proses.</p>
    </div>
    <span class="record-count"><?= count($data) ?> hasil</span>
</section>

<section class="admin-card">
    <form action="<?= base_url('administrator') ?>" method="get" class="filter-form">
        <div>
            <label for="status">Status permohonan</label>
            <select name="status" id="status">
                <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Semua status</option>
                <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Belum diproses</option>
                <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Sedang diproses</option>
                <option value="2" <?= $status === '2' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>
        <div class="filter-search">
            <label for="q">Cari permohonan</label>
            <input type="search" id="q" name="q" value="<?= esc($search) ?>" maxlength="100" placeholder="Kode, nama, atau NIK">
        </div>
        <button class="button button-primary" type="submit">Terapkan filter</button>
        <?php if ($search !== '' || $status !== 'all') : ?>
            <a class="filter-reset" href="<?= base_url('administrator') ?>">Reset</a>
        <?php endif ?>
    </form>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Kode / tanggal</th>
                    <th scope="col">Pemohon</th>
                    <th scope="col">Dokumen</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="visually-hidden">Tindakan</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $item) : ?>
                    <?php
                        $statusLabels = ['Belum diproses', 'Sedang diproses', 'Selesai'];
                        $itemStatus = (int) $item['status'];
                        $statusClass = ['status-pending', 'status-progress', 'status-done'][$itemStatus] ?? 'status-pending';
                    ?>
                    <tr>
                        <td data-label="Kode / tanggal">
                            <strong class="application-code"><?= esc($item['kdpermohonan']) ?></strong>
                            <small><?= esc(date('d M Y, H:i', strtotime($item['tgl_masuk']))) ?></small>
                        </td>
                        <td data-label="Pemohon">
                            <strong><?= esc($item['nama']) ?></strong>
                            <small>NIK <?= esc($item['nik']) ?></small>
                        </td>
                        <td data-label="Dokumen">
                            <div class="document-links">
                                <a href="<?= base_url('admin/document/' . $item['p3_id'] . '/ktp') ?>" target="_blank" rel="noopener">Lihat KTP</a>
                                <a href="<?= base_url('admin/document/' . $item['p3_id'] . '/permohonan') ?>" target="_blank" rel="noopener">Lihat PDF</a>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabels[$itemStatus] ?? 'Belum diproses') ?></span>
                        </td>
                        <td data-label="Tindakan">
                            <div class="row-actions">
                                <a class="action-link" href="<?= base_url('p3/' . $item['p3_id']) ?>">Detail</a>
                                <a class="action-link" href="<?= base_url('p3/' . $item['p3_id'] . '/edit') ?>">Edit</a>
                                <form action="<?= base_url('p3/' . $item['p3_id'] . '/delete') ?>" method="post" data-confirm="Hapus permohonan ini beserta data pemohon dan berkasnya?">
                                    <?= csrf_field() ?>
                                    <button class="action-link action-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if ($data === []) : ?>
                    <tr><td class="empty-state" colspan="5"><?= $search !== '' ? 'Tidak ada permohonan yang cocok dengan pencarian.' : 'Belum ada permohonan untuk filter ini.' ?></td></tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>

<?= $this->endSection() ?>
