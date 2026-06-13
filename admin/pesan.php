<?php
$page_title = 'Pesan Masuk';
include 'admin_header.php';

// Tandai semua pesan sebagai dibaca jika ada parameter
if (isset($_GET['baca']) && intval($_GET['baca'])) {
    $bid = intval($_GET['baca']);
    $conn->query("UPDATE kontak SET status='dibaca' WHERE id=$bid");
    redirect('admin/pesan.php');
}

// Hapus pesan
if (isset($_GET['hapus']) && intval($_GET['hapus'])) {
    $hid = intval($_GET['hapus']);
    $conn->query("DELETE FROM kontak WHERE id=$hid");
    $_SESSION['sukses'] = 'Pesan berhasil dihapus.';
    redirect('admin/pesan.php');
}

$sukses = $_SESSION['sukses'] ?? '';
unset($_SESSION['sukses']);

$filter = $_GET['filter'] ?? 'semua';
$where  = $filter === 'baru' ? "WHERE status='baru'" : ($filter === 'dibaca' ? "WHERE status='dibaca'" : "WHERE 1=1");

$per_page = 10;
$page   = max(1, intval($_GET['hal'] ?? 1));
$offset = ($page - 1) * $per_page;
$total  = $conn->query("SELECT COUNT(*) as t FROM kontak $where")->fetch_assoc()['t'];
$total_pages = ceil($total / $per_page);
$pesan  = $conn->query("SELECT * FROM kontak $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");

$total_baru = $conn->query("SELECT COUNT(*) as t FROM kontak WHERE status='baru'")->fetch_assoc()['t'];
?>

<?php if ($sukses): ?>
<div class="alert-custom alert-success-custom auto-dismiss mb-3">✅ <?= $sukses ?></div>
<?php endif; ?>

<!-- Filter Tab -->
<div style="display:flex;gap:8px;margin-bottom:1.5rem;flex-wrap:wrap">
    <a href="pesan.php?filter=semua" class="filter-btn <?= $filter === 'semua' ? 'active' : '' ?>">
        📬 Semua (<?= $total ?>)
    </a>
    <a href="pesan.php?filter=baru" class="filter-btn <?= $filter === 'baru' ? 'active' : '' ?>">
        🔴 Belum Dibaca (<?= $total_baru ?>)
    </a>
    <a href="pesan.php?filter=dibaca" class="filter-btn <?= $filter === 'dibaca' ? 'active' : '' ?>">
        ✅ Sudah Dibaca
    </a>
</div>

<div class="admin-card">
    <h6 style="font-weight:700;color:var(--teks-gelap);margin-bottom:1.5rem">✉️ Daftar Pesan Masuk</h6>

    <?php if ($pesan && $pesan->num_rows > 0): ?>
    <div style="display:flex;flex-direction:column;gap:12px">
        <?php while ($pm = $pesan->fetch_assoc()): ?>
        <div style="background:<?= $pm['status']==='baru' ? 'rgba(139,107,67,0.04)' : 'var(--abu-muda)' ?>;border-radius:14px;padding:1.2rem 1.5rem;border:1px solid <?= $pm['status']==='baru' ? 'rgba(139,107,67,0.2)' : 'transparent' ?>">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
                <div style="flex:1">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                        <span style="font-weight:700;color:var(--teks-gelap);font-size:0.92rem">
                            👤 <?= htmlspecialchars($pm['nama']) ?>
                        </span>
                        <?php if ($pm['status'] === 'baru'): ?>
                        <span style="font-size:0.65rem;background:#ef4444;color:#fff;padding:2px 9px;border-radius:50px;font-weight:700">BARU</span>
                        <?php else: ?>
                        <span style="font-size:0.65rem;background:rgba(37,211,102,0.15);color:#1a7a3a;padding:2px 9px;border-radius:50px;font-weight:600">DIBACA</span>
                        <?php endif; ?>
                    </div>
                    <div style="font-size:0.8rem;color:#9CA3AF;margin-bottom:8px">
                        📧 <?= htmlspecialchars($pm['email']) ?> &nbsp;·&nbsp; 🕐 <?= date('d M Y, H:i', strtotime($pm['created_at'])) ?>
                    </div>
                    <div style="font-size:0.88rem;color:#5C4027;line-height:1.6;background:var(--putih);border-radius:10px;padding:12px 14px;border-left:3px solid var(--coklat-muda)">
                        <?= nl2br(htmlspecialchars($pm['pesan'])) ?>
                    </div>
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0">
                    <?php if ($pm['status'] === 'baru'): ?>
                    <a href="pesan.php?baca=<?= $pm['id'] ?>" class="btn-icon edit" title="Tandai Dibaca">
                        <i class="bi bi-check2"></i>
                    </a>
                    <?php endif; ?>
                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Halo+<?= urlencode($pm['nama']) ?>" 
                       class="btn-icon" target="_blank"
                       style="background:rgba(37,211,102,0.1);color:#25D366" title="Balas via WA">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="pesan.php?hapus=<?= $pm['id'] ?>" class="btn-icon delete" title="Hapus Pesan"
                       onclick="return confirm('Hapus pesan dari <?= htmlspecialchars($pm['nama']) ?>?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="d-flex justify-content-center mt-4">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?hal=<?= $i ?>&filter=<?= $filter ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div style="text-align:center;padding:3rem;color:#9CA3AF">
        <div style="font-size:3rem">📭</div>
        <div style="margin-top:8px;font-size:0.9rem">Belum ada pesan masuk</div>
    </div>
    <?php endif; ?>
</div>

<?php include 'admin_footer.php'; ?>
