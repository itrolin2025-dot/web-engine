<!-- TARGETING -->
<section id="tab-targeting" class="d-none">
    <div class="card">
        <h5 class="fw-bold mb-4">Targeting Segment Desk</h5>
        <form id="form-targeting" class="row g-3 mb-4">
            <input type="hidden" id="t-edit-id">
            <div class="col-md-6">
                <label class="form-label">Segment Label</label>
                <input class="form-control" id="t-label" placeholder="e.g. Wanita 25-40 Jabodetabek" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Area Coverage</label>
                <input class="form-control" id="t-area" placeholder="e.g. Jakarta, Surabaya, Bandung" required>
            </div>
            <div class="col-12">
                <label class="form-label">Interests & Behaviors</label>
                <textarea class="form-control" id="t-interest" rows="2" placeholder="e.g. Skincare, Beauty, Health conscious..." required></textarea>
            </div>
            <div class="col-12 text-end d-flex justify-content-end gap-2">
                <button type="button" class="btn-gold" style="background: var(--text-muted); display:none;" id="t-btn-cancel" onclick="cancelEditTargeting()">Batal</button>
                <button class="btn-gold" type="submit" id="t-btn-submit">Save Targeting</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table" id="tbl-targeting">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Label</th>
                        <th>Area</th>
                        <th>Interest/Behavior</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="tbl-targeting-body">
                    <tr><td colspan="5" class="text-center text-muted py-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
/* =====================================================
 *  TARGETING — DB-Connected CRUD
 * ===================================================== */

const TARGETING_URL   = "{{ route('leads-tracker.targeting.getData') }}";
const TARGETING_STORE = "{{ route('leads-tracker.targeting.store') }}";


function loadTargetings() {
    fetch(TARGETING_URL)
        .then(r => r.json())
        .then(res => {
            if (!res.success) { notify('Gagal memuat targeting'); return; }
            renderTargetingTable(res.data);
            // Sync ke state lokal supaya selector lain (adsets, dll) masih bisa pakai
            state.targeting = res.data.map(t => ({
                id: String(t.id),
                label: t.label,
                area: t.area,
                interest: t.interest,
                _code: t.code,
            }));
            updateSelectors();
        })
        .catch(() => notify('Error memuat targeting'));
}

function renderTargetingTable(data) {
    const tbody = document.getElementById('tbl-targeting-body');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada data targeting.</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(t => `
        <tr>
            <td><span class="badge-code" onclick="copy('${t.code}')">${t.code}</span></td>
            <td class="fw-bold">${t.label}</td>
            <td>${t.area}</td>
            <td>
                <div class="badge-code" onclick="copyInterest(${t.id})" style="cursor:pointer;">
                    <i data-lucide="copy" style="width:12px"></i> Copy Logic
                </div>
            </td>
            <td class="text-end">
                <button class="btn-action me-1" onclick="editTargeting(${t.id}, '${escapeAttr(t.label)}', '${escapeAttr(t.area)}', '${escapeAttr(t.interest)}')">
                    <i data-lucide="edit"></i>
                </button>
                <button class="btn-action" onclick="deleteTargeting(${t.id}, '${escapeAttr(t.label)}')">
                    <i data-lucide="trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    lucide.createIcons();
}


// Simpan interest di map untuk copy logic
let _interestMap = {};
function copyInterest(id) {
    const t = state.targeting.find(x => String(x.id) === String(id));
    if (t) copy(t.interest);
}

/* ---- Form Submit (Store / Update) ---- */
document.getElementById('form-targeting').addEventListener('submit', function(e) {
    e.preventDefault();

    const editId = document.getElementById('t-edit-id').value;
    const payload = {
        label:    document.getElementById('t-label').value.trim(),
        area:     document.getElementById('t-area').value.trim(),
        interest: document.getElementById('t-interest').value.trim(),
        _token:   CSRF_TOKEN,
    };

    let url    = TARGETING_STORE;
    let method = 'POST';

    if (editId) {
        url    = `/leads-tracker/targeting/${editId}`;
        method = 'PUT';
        payload['_method'] = 'PUT';
    }

    fetch(url, {
        method: 'POST', // always POST, spoof via _method
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ ...payload, _method: editId ? 'PUT' : undefined }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            notify(res.message);
            this.reset();
            document.getElementById('t-edit-id').value = '';
            document.getElementById('t-btn-cancel').style.display = 'none';
            document.getElementById('t-btn-submit').textContent = 'Save Targeting';
            loadTargetings();
        } else {
            notify('Gagal: ' + (res.message || 'Error'));
        }
    })
    .catch(err => notify('Request error: ' + err));
});

/* ---- Edit ---- */
window.editTargeting = function(id, label, area, interest) {
    document.getElementById('t-edit-id').value  = id;
    document.getElementById('t-label').value    = label;
    document.getElementById('t-area').value     = area;
    document.getElementById('t-interest').value = interest;
    document.getElementById('t-btn-cancel').style.display = 'inline-flex';
    document.getElementById('t-btn-submit').textContent = 'Update Targeting';
    window.scrollTo({ top: document.getElementById('tab-targeting').offsetTop - 120, behavior: 'smooth' });
};

window.cancelEditTargeting = function() {
    document.getElementById('form-targeting').reset();
    document.getElementById('t-edit-id').value = '';
    document.getElementById('t-btn-cancel').style.display = 'none';
    document.getElementById('t-btn-submit').textContent = 'Save Targeting';
};

/* ---- Delete ---- */
window.deleteTargeting = function(id, label) {
    if (!confirm(`Hapus targeting "${label}"?`)) return;

    fetch(`/leads-tracker/targeting/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'DELETE' }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            notify(res.message);
            loadTargetings();
        } else {
            notify('Gagal hapus: ' + (res.message || 'Error'));
        }
    })
    .catch(err => notify('Request error: ' + err));
};

/* ---- Auto-load saat tab targeting dibuka ---- */
document.addEventListener('DOMContentLoaded', function () {
    // Patch tab click untuk load targeting otomatis
    const targetingLink = document.querySelector('[data-tab="targeting"]');
    if (targetingLink) {
        targetingLink.addEventListener('click', function() {
            setTimeout(loadTargetings, 50);
        });
    }
    // Juga load saat halaman pertama kali, supaya selector adset terisi
    loadTargetings();
});
</script>