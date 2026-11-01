<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0c3158">
    <title>Riwayat pekerjaan | Dispertaru DIY</title>
    <link rel="icon" href="<?= base_url('user/image/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('user/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/css/admin.css') ?>">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="site-container admin-nav">
            <a class="brand" href="<?= base_url('manager') ?>">
                <img src="<?= base_url('user/image/logo.png') ?>" alt="" width="42" height="48">
                <span><strong>DISPERTARU DIY</strong><small>Panel review manajer</small></span>
            </a>
            <?= $this->include('manager/_navigation', ['activePage' => 'activity']) ?>
        </div>
    </header>
    <main class="admin-main">
        <div class="site-container admin-container">
            <a class="action-link detail-back" href="<?= base_url('manager') ?>">← Kembali ke review pekerjaan</a>
            <section class="admin-page-heading">
                <div>
                    <span class="eyebrow">RIWAYAT PEKERJAAN</span>
                    <h1><?= esc($application['kdpermohonan']) ?></h1>
                    <p>Pemohon: <?= esc($application['nama']) ?> · Masuk <?= esc(date('d M Y, H:i', strtotime($application['tgl_masuk']))) ?></p>
                </div>
                <?php
                    $statusLabels = ['Belum diproses', 'Sedang diproses', 'Selesai'];
                    $applicationStatus = (int) $application['status'];
                    $statusClass = ['status-pending', 'status-progress', 'status-done'][$applicationStatus] ?? 'status-pending';
                ?>
                <span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabels[$applicationStatus] ?? 'Belum diproses') ?></span>
            </section>
            <section class="admin-card">
                <div class="card-heading">
                    <div><span class="eyebrow">CATATAN AUDIT</span><h2>Aktivitas petugas</h2></div>
                    <span class="record-count"><?= count($history) ?> aktivitas</span>
                </div>
                <div class="table-wrap">
                    <table class="admin-table manager-table">
                        <thead><tr><th scope="col">Waktu</th><th scope="col">Petugas</th><th scope="col">Aktivitas</th><th scope="col">Perubahan status</th></tr></thead>
                        <tbody>
                            <?php foreach ($history as $event) : ?>
                                <?php
                                    $actionLabel = $event['action'] === 'application_received' ? 'Permohonan diterima dan ditugaskan' : 'Status permohonan diperbarui';
                                    $fromLabel = $event['from_status'] === null ? '—' : ($statusLabels[(int) $event['from_status']] ?? 'Tidak diketahui');
                                    $toLabel = $event['to_status'] === null ? '—' : ($statusLabels[(int) $event['to_status']] ?? 'Tidak diketahui');
                                ?>
                                <tr>
                                    <td data-label="Waktu"><strong><?= esc(date('d M Y, H:i', strtotime($event['created_at']))) ?></strong></td>
                                    <td data-label="Petugas"><strong><?= esc($event['username'] ?? 'Tidak diketahui') ?></strong></td>
                                    <td data-label="Aktivitas"><strong><?= esc($actionLabel) ?></strong></td>
                                    <td data-label="Perubahan status"><strong><?= esc($fromLabel . ' → ' . $toLabel) ?></strong></td>
                                </tr>
                            <?php endforeach ?>
                            <?php if ($history === []) : ?><tr><td class="empty-state" colspan="4">Belum ada aktivitas.</td></tr><?php endif ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
    <footer class="admin-footer"><div class="site-container">© <?= date('Y') ?> Dinas Pertanahan dan Tata Ruang DIY</div></footer>
</body>
</html>
