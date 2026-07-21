<!-- ADS / CREATIVES -->
<section id="tab-creatives" class="d-none">
    <div class="card">
        <h5 class="fw-bold mb-4">Ads Management</h5>
        <form id="form-creative" class="row g-3 mb-4">
            <input type="hidden" id="cr-edit-id">
            <div class="col-md-4">
                <label class="form-label">Mapping Ad Set</label>
                <select class="form-select" id="cr-adset" required></select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ads Name</label>
                <input class="form-control" id="cr-name" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Format</label>
                <select class="form-select" id="cr-format">
                    <option value="Video">Video</option>
                    <option value="Image">Image</option>
                    <option value="Carousel">Carousel</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Media No.</label>
                <input class="form-control text-center" id="cr-no" value="01">
            </div>
            <div class="col-12 text-end d-flex justify-content-end gap-2">
                <button type="button" class="btn-gold" style="background:var(--text-muted);display:none;" id="cr-btn-cancel" onclick="cancelEditCreative()">Batal</button>
                <button class="btn-gold" type="submit" id="cr-btn-submit">Seal Ads Identity</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table" id="tbl-creatives">
                <thead>
                    <tr>
                        <th>Ref Token</th>
                        <th>Ads Name</th>
                        <th>Creative</th>
                        <th>Ad Set</th>
                        <th>Daily Spend</th>
                        <th>WA Copy</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="tbl-creatives-body">
                    <tr><td colspan="7" class="text-center text-muted py-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
/* =====================================================
 *  CREATIVES (ADS) — DB-Connected CRUD
 * ===================================================== */
const CREATIVE_URL   = "{{ route('leads-tracker.creative.getData') }}";
const CREATIVE_STORE = "{{ route('leads-tracker.creative.store') }}";
const WA_MSG = (ref) => `Halo kak, saya ingin konsultasi untuk develop brand & produk.\nRef: ${ref}`;

function loadCreatives() {
    fetch(CREATIVE_URL)
        .then(r => r.json())
        .then(res => {
            if (!res.success) { notify('Gagal memuat ads'); return; }
            renderCreativeTable(res.data);
            // Sync ke state
            state.creatives = res.data.map(c => ({
                id: String(c.id), ref: c.ref, adset_id: String(c.adset_id),
                name: c.name, format: c.format, no: c.no, spend: c.spend,
            }));
        })
        .catch(() => notify('Error memuat ads'));
}

function renderCreativeTable(data) {
    const tbody = document.getElementById('tbl-creatives-body');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada ads.</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(c => `
        <tr>
            <td><span class="badge-code" onclick="copy('${c.ref}')">${c.ref}</span></td>
            <td class="fw-bold">${c.name}</td>
            <td><span class="badge bg-light text-dark">${c.format}-${c.no}</span></td>
            <td class="small text-muted">${c.adset_name}</td>
            <td>
                <input type="number" class="form-control form-control-sm w-75 bg-transparent"
                    value="${c.spend}"
                    onchange="updateCreativeSpend(${c.id}, this.value)">
            </td>
            <td>
                <button class="btn btn-sm btn-outline-warning px-3 rounded-pill"
                    onclick="copy(\`${WA_MSG(c.ref)}\`)">Copy WA</button>
            </td>
            <td class="text-end">
                <button class="btn-action me-1" onclick="editCreative(${c.id},${c.adset_id},'${escapeAttr(c.name)}','${c.format}','${c.no}')">
                    <i data-lucide="edit"></i>
                </button>
                <button class="btn-action" onclick="deleteCreative(${c.id},'${escapeAttr(c.name)}')">
                    <i data-lucide="trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    lucide.createIcons();
}

function populateCreativeAdsetSelector() {
    fetch("{{ route('leads-tracker.adset.getData') }}")
        .then(r => r.json())
        .then(res => {
            const sel = document.getElementById('cr-adset');
            if (sel && res.success) {
                sel.innerHTML = '<option value="">Pilih Ad Set</option>' +
                    res.data.map(s => `<option value="${s.id}">${s.code} | ${s.name}</option>`).join('');
            }
        });
}

document.getElementById('form-creative').addEventListener('submit', function(e) {
    e.preventDefault();
    const editId = document.getElementById('cr-edit-id').value;
    const payload = {
        adset_id: document.getElementById('cr-adset').value,
        name:     document.getElementById('cr-name').value.trim(),
        format:   document.getElementById('cr-format').value,
        no:       document.getElementById('cr-no').value.trim(),
        _token:   CSRF_TOKEN,
    };
    const url = editId ? `/leads-tracker/creative/${editId}` : CREATIVE_STORE;

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ ...payload, _method: editId ? 'PUT' : undefined }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            notify(res.message); this.reset();
            document.getElementById('cr-edit-id').value = '';
            document.getElementById('cr-no').value = '01';
            document.getElementById('cr-btn-cancel').style.display = 'none';
            document.getElementById('cr-btn-submit').textContent = 'Seal Ads Identity';
            loadCreatives();
        } else notify('Gagal: ' + (res.message || 'Error'));
    })
    .catch(err => notify('Request error: ' + err));
});

window.editCreative = function(id, adsetId, name, format, no) {
    document.getElementById('cr-edit-id').value = id;
    document.getElementById('cr-adset').value   = adsetId;
    document.getElementById('cr-name').value    = name;
    document.getElementById('cr-format').value  = format;
    document.getElementById('cr-no').value      = no;
    document.getElementById('cr-btn-cancel').style.display = 'inline-flex';
    document.getElementById('cr-btn-submit').textContent = 'Update Ads';
    window.scrollTo({ top: document.getElementById('tab-creatives').offsetTop - 120, behavior: 'smooth' });
};

window.cancelEditCreative = function() {
    document.getElementById('form-creative').reset();
    document.getElementById('cr-edit-id').value = '';
    document.getElementById('cr-no').value = '01';
    document.getElementById('cr-btn-cancel').style.display = 'none';
    document.getElementById('cr-btn-submit').textContent = 'Seal Ads Identity';
};

window.updateCreativeSpend = function(id, val) {
    fetch(`/leads-tracker/creative/${id}/spend`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'PATCH', spend: val }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) notify('Spend diperbarui'); });
};

window.deleteCreative = function(id, name) {
    if (!confirm(`Hapus ads "${name}"?`)) return;
    fetch(`/leads-tracker/creative/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'DELETE' }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) { notify(res.message); loadCreatives(); } else notify('Gagal: ' + res.message); });
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('[data-tab="creatives"]')?.addEventListener('click', () => {
        setTimeout(() => { loadCreatives(); populateCreativeAdsetSelector(); }, 50);
    });
    loadCreatives();
    populateCreativeAdsetSelector();
});
</script>