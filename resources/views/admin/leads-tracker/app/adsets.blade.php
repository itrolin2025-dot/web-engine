<!-- ADSETS -->
<section id="tab-adsets" class="d-none">
    <div class="card">
        <h5 class="fw-bold mb-4">Ad Set Structure</h5>
        <form id="form-adset" class="row g-3 mb-4">
            <input type="hidden" id="s-edit-id">
            <div class="col-md-6">
                <label class="form-label">Source Campaign</label>
                <select class="form-select" id="s-camp" required></select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Targeting Segment</label>
                <select class="form-select" id="s-target" required></select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Ad Set Name</label>
                <input class="form-control" id="s-name" placeholder="Specific audience target name" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Conversion</label>
                <select class="form-select" id="s-conversion">
                    <option>Website</option>
                    <option>Instagram Visit</option>
                    <option>WhatsApp</option>
                </select>
            </div>
            <div class="col-12 text-end d-flex justify-content-end gap-2">
                <button type="button" class="btn-gold" style="background:var(--text-muted);display:none;" id="s-btn-cancel" onclick="cancelEditAdset()">Batal</button>
                <button class="btn-gold" type="submit" id="s-btn-submit">Map Ad Set</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table" id="tbl-adsets">
                <thead>
                    <tr>
                        <th>Ad Set Code</th>
                        <th>Name</th>
                        <th>Campaign</th>
                        <th>Targeting</th>
                        <th>Conversion</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="tbl-adsets-body">
                    <tr><td colspan="6" class="text-center text-muted py-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
/* =====================================================
 *  ADSETS — DB-Connected CRUD
 * ===================================================== */
const ADSET_URL   = "{{ route('leads-tracker.adset.getData') }}";
const ADSET_STORE = "{{ route('leads-tracker.adset.store') }}";

function loadAdsets() {
    fetch(ADSET_URL)
        .then(r => r.json())
        .then(res => {
            if (!res.success) { notify('Gagal memuat adsets'); return; }
            renderAdsetTable(res.data);
            // Sync ke state lokal
            state.adsets = res.data.map(s => ({
                id: String(s.id), campaign_id: String(s.camp_id),
                targeting_id: String(s.targeting_id), name: s.name,
                conversion: s.conversion, _code: s.code,
            }));
            updateSelectors();
        })
        .catch(() => notify('Error memuat adsets'));
}

function renderAdsetTable(data) {
    const tbody = document.getElementById('tbl-adsets-body');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada ad set.</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(s => `
        <tr>
            <td><span class="badge-code" onclick="copy('${s.code}')">${s.code}</span></td>
            <td class="fw-bold">${s.name}</td>
            <td class="small mono">${s.camp_code}</td>
            <td class="small">${s.targeting_label}</td>
            <td class="small fw-bold text-warning">${s.conversion}</td>
            <td class="text-end">
                <button class="btn-action me-1" onclick="editAdset(${s.id},${s.camp_id},${s.targeting_id},'${escapeAttr(s.name)}','${escapeAttr(s.conversion)}')">
                    <i data-lucide="edit"></i>
                </button>
                <button class="btn-action" onclick="deleteAdset(${s.id},'${escapeAttr(s.name)}')">
                    <i data-lucide="trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    lucide.createIcons();
}

/* Populate selectors dari DB */
function populateAdsetSelectors() {
    Promise.all([
        fetch("{{ route('leads-tracker.campaign.getData') }}").then(r => r.json()),
        fetch("{{ route('leads-tracker.targeting.getData') }}").then(r => r.json()),
    ]).then(([camps, targets]) => {
        const sCamp = document.getElementById('s-camp');
        const sTarget = document.getElementById('s-target');
        if (sCamp && camps.success) {
            sCamp.innerHTML = '<option value="">Pilih Campaign</option>' +
                camps.data.map(c => `<option value="${c.id}">${c.code} — ${c.name}</option>`).join('');
        }
        if (sTarget && targets.success) {
            sTarget.innerHTML = '<option value="">Pilih Targeting</option>' +
                targets.data.map(t => `<option value="${t.id}">${t.code} — ${t.label}</option>`).join('');
        }
    });
}

document.getElementById('form-adset').addEventListener('submit', function(e) {
    e.preventDefault();
    const editId = document.getElementById('s-edit-id').value;
    const payload = {
        campaign_id:  document.getElementById('s-camp').value,
        targeting_id: document.getElementById('s-target').value,
        name:         document.getElementById('s-name').value.trim(),
        conversion:   document.getElementById('s-conversion').value,
        _token: CSRF_TOKEN,
    };
    const url = editId ? `/leads-tracker/adset/${editId}` : ADSET_STORE;

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ ...payload, _method: editId ? 'PUT' : undefined }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            notify(res.message); this.reset();
            document.getElementById('s-edit-id').value = '';
            document.getElementById('s-btn-cancel').style.display = 'none';
            document.getElementById('s-btn-submit').textContent = 'Map Ad Set';
            loadAdsets(); populateAdsetSelectors();
        } else notify('Gagal: ' + (res.message || 'Error'));
    })
    .catch(err => notify('Request error: ' + err));
});

window.editAdset = function(id, campId, targetId, name, conversion) {
    document.getElementById('s-edit-id').value      = id;
    document.getElementById('s-camp').value         = campId;
    document.getElementById('s-target').value       = targetId;
    document.getElementById('s-name').value         = name;
    document.getElementById('s-conversion').value   = conversion;
    document.getElementById('s-btn-cancel').style.display = 'inline-flex';
    document.getElementById('s-btn-submit').textContent = 'Update Ad Set';
    window.scrollTo({ top: document.getElementById('tab-adsets').offsetTop - 120, behavior: 'smooth' });
};

window.cancelEditAdset = function() {
    document.getElementById('form-adset').reset();
    document.getElementById('s-edit-id').value = '';
    document.getElementById('s-btn-cancel').style.display = 'none';
    document.getElementById('s-btn-submit').textContent = 'Map Ad Set';
};

window.deleteAdset = function(id, name) {
    if (!confirm(`Hapus ad set "${name}"?`)) return;
    fetch(`/leads-tracker/adset/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'DELETE' }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) { notify(res.message); loadAdsets(); } else notify('Gagal: ' + res.message); });
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('[data-tab="adsets"]')?.addEventListener('click', () => {
        setTimeout(() => { loadAdsets(); populateAdsetSelectors(); }, 50);
    });
    loadAdsets();
});
</script>