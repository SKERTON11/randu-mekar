<?php
$page_title = 'Pesan Masuk';

// Server-side email sending removed per user request.
// Balasan kini dilakukan client-side (WhatsApp / mailto). No Composer/PHPMailer usage here.

// Small AJAX endpoint: mark message as replied
if (isset($_GET['mark_replied']) && intval($_GET['mark_replied'])) {
    $mid = intval($_GET['mark_replied']);
    // mark as 'dibaca' to indicate the admin has replied via WhatsApp
    $conn->query("UPDATE kontak SET status='dibaca' WHERE id={$mid}");
    if (ob_get_length()) @ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
}

// Include header after handling POST so redirects work
include 'admin_header.php';

// Tandai semua pesan sebagai dibaca jika ada parameter
if (isset($_GET['baca']) && intval($_GET['baca'])) {
    $bid = intval($_GET['baca']);
    $conn->query("UPDATE kontak SET status='dibaca' WHERE id=$bid");
    redirect('admin/pesan.php');
}

// Endpoint to mark all as read via AJAX
if (isset($_GET['mark_all_read']) && intval($_GET['mark_all_read'])) {
    $conn->query("UPDATE kontak SET status='dibaca' WHERE status='baru'");
    if (ob_get_length()) @ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
}

// Endpoint to toggle favorite
if (isset($_GET['toggle_fav']) && intval($_GET['toggle_fav'])) {
    $fid = intval($_GET['toggle_fav']);
    // toggle favorit value
    $res = $conn->query("SELECT favorit FROM kontak WHERE id={$fid} LIMIT 1");
    $row = $res ? $res->fetch_assoc() : null;
    $new = ($row && intval($row['favorit']) === 1) ? 0 : 1;
    $conn->query("UPDATE kontak SET favorit={$new} WHERE id={$fid}");
    if (ob_get_length()) @ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'favorit' => $new]);
    exit;
}

// Poll favorites status for a set of IDs (used by client to sync in real-time)
if (isset($_GET['poll_fav']) && intval($_GET['poll_fav'])) {
    $idsRaw = $_GET['ids'] ?? '';
    $ids = array_filter(array_map('intval', explode(',', $idsRaw)), function($v){ return $v>0; });
    $result = ['ok' => true, 'items' => [], 'total_fav' => 0];
    if (!empty($ids)) {
        $in = implode(',', $ids);
        $q = $conn->query("SELECT id,favorit FROM kontak WHERE id IN ($in)");
        if ($q) {
            while ($r = $q->fetch_assoc()) {
                $result['items'][$r['id']] = intval($r['favorit']);
            }
        }
    }
    $tf = $conn->query("SELECT COUNT(*) as t FROM kontak WHERE favorit=1");
    $result['total_fav'] = $tf ? intval($tf->fetch_assoc()['t']) : 0;
    if (ob_get_length()) @ob_clean();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Hapus pesan
if (isset($_GET['hapus']) && intval($_GET['hapus'])) {
    $hid = intval($_GET['hapus']);
    $conn->query("DELETE FROM kontak WHERE id=$hid");
    $_SESSION['sukses'] = 'Pesan berhasil dihapus.';
    redirect('admin/pesan.php');
}

$sukses = $_SESSION['sukses'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['sukses'], $_SESSION['error']);

$filter = $_GET['filter'] ?? 'semua';

// Ensure `favorit` column exists (migration fallback)
try {
    $colCheck = $conn->query("SHOW COLUMNS FROM kontak LIKE 'favorit'");
    if (!$colCheck || $colCheck->num_rows === 0) {
        $conn->query("ALTER TABLE kontak ADD COLUMN favorit TINYINT(1) DEFAULT 0");
    }
} catch (Throwable $e) {
    // ignore - if DB user cannot alter, queries referencing favorit may fail
}

$where  = $filter === 'baru' ? "WHERE status='baru'" : ($filter === 'dibaca' ? "WHERE status='dibaca'" : ($filter === 'favorit' ? "WHERE favorit=1" : "WHERE 1=1"));

$per_page = 10;
$page   = max(1, intval($_GET['hal'] ?? 1));
$offset = ($page - 1) * $per_page;
$total  = $conn->query("SELECT COUNT(*) as t FROM kontak $where")->fetch_assoc()['t'];
$total_pages = ceil($total / $per_page);
$pesan  = $conn->query("SELECT * FROM kontak $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");

$total_baru = $conn->query("SELECT COUNT(*) as t FROM kontak WHERE status='baru'")->fetch_assoc()['t'];
// total favorit
$total_fav = $conn->query("SELECT COUNT(*) as t FROM kontak WHERE favorit=1")->fetch_assoc()['t'];
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
    <a href="pesan.php?filter=favorit" class="filter-btn <?= $filter === 'favorit' ? 'active' : '' ?>">
        ⭐ Favorit (<span id="favCount"><?= $total_fav ?></span>)
    </a>
    <a href="pesan.php?filter=dibaca" class="filter-btn <?= $filter === 'dibaca' ? 'active' : '' ?>">
        ✅ Sudah Dibaca
    </a>
</div>

<div style="margin-bottom:1rem;display:flex;gap:8px;align-items:center">
    <button id="markAllBtn" class="btn-primary-custom" style="padding:8px 12px;border-radius:18px;background:linear-gradient(90deg,#8B6B43,#D6B98C);color:#fff;border:none">Tandai semua DIBACA</button>
    <div style="font-size:0.9rem;color:#7A6050;margin-left:8px">Total: <span id="totalCount"><?= $total ?></span> · Baru: <span id="baruCount"><?= $total_baru ?></span></div>
</div>

<div class="admin-card">
    <h6 style="font-weight:700;color:var(--teks-gelap);margin-bottom:1.5rem">✉️ Daftar Pesan Masuk</h6>

    <?php if ($pesan && $pesan->num_rows > 0): ?>
        <div style="display:flex;flex-direction:column;gap:12px">
            <?php while ($pm = $pesan->fetch_assoc()): ?>
                <div class="message-card" data-status="<?= $pm['status'] ?>" style="background:<?= $pm['status'] === 'baru' ? 'rgba(139,107,67,0.04)' : 'var(--abu-muda)' ?>;border-radius:14px;border:1px solid <?= $pm['status'] === 'baru' ? 'rgba(139,107,67,0.2)' : 'transparent' ?>;overflow:hidden">
                    <div class="message-summary" onclick="toggleMessage(<?= $pm['id'] ?>)" style="display:flex;justify-content:space-between;align-items:center;gap:12px;padding:1.2rem 1.5rem;cursor:pointer">
                        <div style="flex:1">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                                <span style="font-weight:700;color:var(--teks-gelap);font-size:0.92rem">
                                    👤 <?= htmlspecialchars($pm['nama']) ?>
                                </span>
                                <?php if ($pm['status'] === 'baru'): ?>
                                    <span id="status-badge-<?= $pm['id'] ?>" style="font-size:0.65rem;background:#ef4444;color:#fff;padding:2px 9px;border-radius:50px;font-weight:700">BARU</span>
                                <?php else: ?>
                                    <span id="status-badge-<?= $pm['id'] ?>" style="font-size:0.65rem;background:rgba(37,211,102,0.15);color:#1a7a3a;padding:2px 9px;border-radius:50px;font-weight:600">DIBACA</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:0.8rem;color:#9CA3AF;">
                                📧 <?= htmlspecialchars($pm['email']) ?> &nbsp;·&nbsp; 🕐 <?= date('d M Y, H:i', strtotime($pm['created_at'])) ?>
                                <?php if (!empty($pm['whatsapp'])): ?>
                                    <br>📱 <?= htmlspecialchars($pm['whatsapp']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
                            <button class="btn-icon" onclick="event.stopPropagation(); toggleFavorite(<?= $pm['id'] ?>)" title="Favoritkan" aria-pressed="<?= (isset($pm['favorit']) && intval($pm['favorit']) === 1) ? 'true' : 'false' ?>" role="button" style="background:none;border:none;cursor:pointer;padding:4px">
                                <i id="star-icon-<?= $pm['id'] ?>" class="bi <?= (isset($pm['favorit']) && intval($pm['favorit']) === 1) ? 'bi-star-fill' : 'bi-star' ?>" style="font-size:1.05rem;color:#D6B98C"></i>
                            </button>
                            <span style="font-size:0.95rem;color:var(--coklat-muda)">Lihat</span>
                            <i class="bi bi-caret-down-fill" id="toggle-icon-<?= $pm['id'] ?>" style="font-size:1.2rem;color:var(--coklat-muda)"></i>
                        </div>
                    </div>
                    <div id="message-details-<?= $pm['id'] ?>" class="message-details" style="display:none;background:var(--putih);padding:0 1.5rem 1.5rem;border-top:1px solid rgba(214,185,140,0.2)">
                        <div style="font-size:0.88rem;color:#5C4027;line-height:1.6;margin-top:1rem">
                            <?= nl2br(htmlspecialchars($pm['pesan'])) ?>
                        </div>

                        <!-- Reply area: client-side WhatsApp / mailto (no server email) -->
                        <div style="margin-top:1rem;">
                            <div style="display:flex;flex-direction:column;gap:8px">
                                <textarea id="reply-text-<?= $pm['id'] ?>" rows="6" class="form-control-custom" placeholder="Tulis pesan balasan untuk dikirim via WhatsApp...">Halo <?= htmlspecialchars($pm['nama']) ?>,

Terima kasih sudah menghubungi Toko Randu Mekar — senang bisa membantu!

[Isikan jawaban di sini]

Salam,
Toko Randu Mekar
</textarea>
                                <div style="display:flex;gap:8px;align-items:center">
                                    <?php if (!empty($pm['whatsapp'])): ?>
                                        <button type="button" class="btn-primary-custom" style="padding:8px 12px" onclick="sendWhatsApp(<?= $pm['id'] ?>, '<?= preg_replace('/[^0-9]/', '', $pm['whatsapp']) ?>', '<?= htmlspecialchars(addslashes($pm['nama'])) ?>')">Balas via WhatsApp</button>
                                    <?php else: ?>
                                        <button type="button" class="btn-primary-custom" disabled style="padding:8px 12px;opacity:0.6">Nomor WA tidak tersedia</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn-primary-custom" onclick="copyReply(<?= $pm['id'] ?>)" style="padding:8px 12px;margin-left:6px">Salin Pesan</button>
                                    <button type="button" class="btn-primary-custom" onclick="markRead(<?= $pm['id'] ?>)" title="Tandai dibaca" style="padding:8px 12px;margin-left:6px">Baca</button>
                                    <a href="pesan.php?hapus=<?= $pm['id'] ?>" class="btn-icon delete" title="Hapus Pesan" onclick="event.stopPropagation(); return confirm('Hapus pesan dari <?= htmlspecialchars($pm['nama']) ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
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

<script>
    function toggleMessage(id) {
        const details = document.getElementById('message-details-' + id);
        const icon = document.getElementById('toggle-icon-' + id);
        if (!details) return;
        const visible = details.style.display === 'block';
        details.style.display = visible ? 'none' : 'block';
        if (icon) {
            icon.classList.toggle('bi-caret-down-fill', visible);
            icon.classList.toggle('bi-caret-up-fill', !visible);
        }
        // if opening and message is 'baru', mark as read
        if (!visible) {
            const card = details.closest('.message-card');
            if (card && card.dataset && card.dataset.status === 'baru') {
                markRead(id);
                card.dataset.status = 'dibaca';
            }
        }
    }

    const API_PATH = window.location.pathname;

    function sendWhatsApp(id, phone, name) {
        const textarea = document.getElementById('reply-text-' + id);
        if (!textarea) return;
        const text = textarea.value || ('Halo ' + name);
        const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(text);
        // open WhatsApp chat in new tab
        window.open(url, '_blank');
        // mark message as replied via AJAX so admin UI updates
        fetch(API_PATH + '?mark_replied=' + id, { method: 'GET', credentials: 'same-origin' })
            .then(resp => resp.json())
            .then(data => {
                if (data && data.ok) {
                    const badge = document.getElementById('status-badge-' + id);
                    if (badge) {
                        badge.textContent = 'DIBACA';
                        badge.style.background = 'rgba(37,211,102,0.15)';
                        badge.style.color = '#1a7a3a';
                        badge.style.fontWeight = '600';
                    }
                    // decrement new count and update UI counts
                    const baruEl = document.getElementById('baruCount');
                    if (baruEl) {
                        const n = parseInt(baruEl.textContent || '0', 10);
                        baruEl.textContent = Math.max(0, n - 1);
                    }
                }
            }).catch(()=>{});
    }

    function markRead(id) {
        fetch(API_PATH + '?mark_replied=' + id, { method: 'GET', credentials: 'same-origin' })
            .then(response => response.json().catch(()=>null))
            .then(data => {
                if (data && data.ok) {
                    const badge = document.getElementById('status-badge-' + id);
                    if (badge) {
                        badge.textContent = 'DIBACA';
                        badge.style.background = 'rgba(37,211,102,0.15)';
                        badge.style.color = '#1a7a3a';
                        badge.style.fontWeight = '600';
                    }
                    const baruEl = document.getElementById('baruCount');
                    if (baruEl) {
                        const n = parseInt(baruEl.textContent || '0', 10);
                        baruEl.textContent = Math.max(0, n - 1);
                    }
                }
            }).catch(()=>{});
    }

    // mark all as read
    const markAllBtn = document.getElementById('markAllBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(){
            if (!confirm('Tandai semua pesan sebagai DIBACA?')) return;
            fetch(API_PATH + '?mark_all_read=1', { method: 'GET', credentials: 'same-origin' })
                .then(r=>r.json()).then(data=>{
                    if (data && data.ok) {
                        // update all badges on page
                        document.querySelectorAll('[id^="status-badge-"]').forEach(function(b){
                            b.textContent = 'DIBACA';
                            b.style.background = 'rgba(37,211,102,0.15)';
                            b.style.color = '#1a7a3a';
                            b.style.fontWeight = '600';
                        });
                        // set counts
                        const baruEl = document.getElementById('baruCount');
                        if (baruEl) baruEl.textContent = '0';
                    }
                }).catch(()=>{});
        });
    }

    function copyReply(id) {
        const textarea = document.getElementById('reply-text-' + id);
        if (!textarea) return;
        const text = textarea.value;
        // Format text similar to WhatsApp (plain text with line breaks)
        const formatted = text.replace(/\r\n|\r/g, '\n');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(formatted).then(()=>{
                showToast('Teks balasan disalin ke clipboard.');
            }).catch(()=>{
                fallbackCopy(formatted);
            });
        } else {
            fallbackCopy(formatted);
        }
    }

    function fallbackCopy(text) {
        const ta = document.createElement('textarea');
        ta.style.position = 'fixed'; ta.style.left = '-9999px';
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); showToast('Teks balasan disalin ke clipboard.'); } catch(e) { alert('Gagal menyalin; silakan salin manual.'); }
        document.body.removeChild(ta);
    }

    function showToast(msg) {
        // simple toast
        const t = document.createElement('div');
        t.textContent = msg;
        t.style.position = 'fixed'; t.style.bottom = '20px'; t.style.right = '20px'; t.style.background = 'rgba(0,0,0,0.7)'; t.style.color = '#fff'; t.style.padding = '10px 14px'; t.style.borderRadius = '8px'; t.style.zIndex = 9999;
        document.body.appendChild(t);
        setTimeout(()=>{ t.style.transition='opacity 0.25s'; t.style.opacity='0'; setTimeout(()=>t.remove(),300); }, 1800);
    }

    // star animation CSS helper
    const style = document.createElement('style');
    style.textContent = `
        .star-bounce {
            transform: scale(1.15);
            transition: transform 0.2s ease;
        }
    `;
    document.head.appendChild(style);

    function toggleFavorite(id) {
        // Optimistic UI update: flip immediately
        const icon = document.getElementById('star-icon-' + id);
        const btn = icon ? icon.closest('button') : null;
        const wasFav = icon && icon.classList.contains('bi-star-fill');
        // apply optimistic toggle
        if (icon) {
            if (wasFav) {
                icon.classList.remove('bi-star-fill'); icon.classList.add('bi-star'); icon.style.color = '#D6B98C';
                if (btn) btn.setAttribute('aria-pressed', 'false');
            } else {
                icon.classList.remove('bi-star'); icon.classList.add('bi-star-fill'); icon.style.color = '#F59E0B';
                if (btn) btn.setAttribute('aria-pressed', 'true');
            }
            icon.classList.add('star-bounce');
            setTimeout(() => icon.classList.remove('star-bounce'), 220);
        }
        const favEl = document.getElementById('favCount');
        if (favEl) {
            let cur = parseInt(favEl.textContent || '0', 10);
            favEl.textContent = wasFav ? Math.max(0, cur - 1) : cur + 1;
        }

        // send request to server; revert if fails or server disagrees
        fetch(API_PATH + '?toggle_fav=' + id, { method: 'GET', credentials: 'same-origin' })
            .then(response => response.text().then(txt => ({ ok: response.ok, status: response.status, text: txt })))
            .then(obj => {
                if (!obj.ok) throw new Error('HTTP ' + obj.status + ' - ' + (obj.text || ''));
                let data;
                try { data = JSON.parse(obj.text); } catch (e) { throw new Error('JSON parse error: ' + e.message + ' => ' + (obj.text ? obj.text.substring(0,200) : '')); }
                if (!data || !data.ok) throw new Error('Server returned error');
                // ensure final state matches server
                const serverFav = parseInt(data.favorit);
                if (icon) {
                    if (serverFav === 1) { icon.classList.remove('bi-star'); icon.classList.add('bi-star-fill'); icon.style.color = '#F59E0B'; if (btn) btn.setAttribute('aria-pressed', 'true'); }
                    else { icon.classList.remove('bi-star-fill'); icon.classList.add('bi-star'); icon.style.color = '#D6B98C'; if (btn) btn.setAttribute('aria-pressed', 'false'); }
                }
                // if unfavorited while viewing favorites, remove the card
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('filter') === 'favorit' && serverFav === 0) {
                    const msgCard = document.getElementById('message-details-' + id);
                    if (msgCard) {
                        const parent = msgCard.closest('.message-card');
                        if (parent) parent.remove();
                    }
                }
            })
            .catch(err => {
                // revert optimistic change
                if (icon) {
                    if (wasFav) { icon.classList.remove('bi-star'); icon.classList.add('bi-star-fill'); icon.style.color = '#F59E0B'; if (btn) btn.setAttribute('aria-pressed', 'true'); }
                    else { icon.classList.remove('bi-star-fill'); icon.classList.add('bi-star'); icon.style.color = '#D6B98C'; if (btn) btn.setAttribute('aria-pressed', 'false'); }
                }
                if (favEl) {
                    let cur2 = parseInt(favEl.textContent || '0', 10);
                    favEl.textContent = wasFav ? cur2 + 1 : Math.max(0, cur2 - 1);
                }
                showToast('Gagal menghubungi server: ' + (err.message || 'network error'));
            });
    }

    // Polling to sync favorite state across clients in near-real-time
    function pollFavorites() {
        const detailEls = document.querySelectorAll('[id^="message-details-"]');
        if (!detailEls || detailEls.length === 0) return;
        const ids = Array.from(detailEls).map(el => {
            const m = el.id.match(/message-details-(\d+)/);
            return m ? m[1] : null;
        }).filter(Boolean);
        if (ids.length === 0) return;
        const param = ids.join(',');
        fetch(API_PATH + '?poll_fav=1&ids=' + encodeURIComponent(param), { method: 'GET', credentials: 'same-origin' })
            .then(response => response.json().catch(()=>null))
            .then(data => {
                if (!data || !data.ok) return;
                // update individual icons
                for (const id of Object.keys(data.items)) {
                    const fav = parseInt(data.items[id]);
                    const icon = document.getElementById('star-icon-' + id);
                    const btn = icon ? icon.closest('button') : null;
                    if (icon) {
                        if (fav === 1 && icon.classList.contains('bi-star')) {
                            icon.classList.remove('bi-star'); icon.classList.add('bi-star-fill'); icon.style.color = '#F59E0B';
                            if (btn) btn.setAttribute('aria-pressed', 'true');
                        } else if (fav === 0 && icon.classList.contains('bi-star-fill')) {
                            icon.classList.remove('bi-star-fill'); icon.classList.add('bi-star'); icon.style.color = '#D6B98C';
                            if (btn) btn.setAttribute('aria-pressed', 'false');
                            // if we are in favorite filter view, remove unfavorited
                            const urlParams = new URLSearchParams(window.location.search);
                            if (urlParams.get('filter') === 'favorit') {
                                const msgCard = document.getElementById('message-details-' + id);
                                if (msgCard) {
                                    const parent = msgCard.closest('.message-card');
                                    if (parent) parent.remove();
                                }
                            }
                        }
                    }
                }
                // update global fav count
                const favEl = document.getElementById('favCount');
                if (favEl) favEl.textContent = parseInt(data.total_fav || 0, 10);
            }).catch(()=>{});
    }

    // start polling every 5 seconds
    setInterval(pollFavorites, 5000);
</script>

<?php include 'admin_footer.php'; ?>