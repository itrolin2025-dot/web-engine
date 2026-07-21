<!-- CAMPAIGNS -->
<section id="tab-campaigns" class="d-none">
    <div class="card">
        <h5 class="fw-bold mb-4">Campaign Portfolio</h5>
        <form id="form-campaign" class="row g-3 mb-4">
            <input type="hidden" id="c-edit-id">
            <div class="col-md-3">
                <label class="form-label">Brand</label>
                <input class="form-control" id="c-brand" maxlength="10" placeholder="e.g. ROLINSKIN" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Objective</label>
                <select class="form-select" id="c-obj">
                    <option value="AW">Awareness (AW)</option>
                    <option value="TR">Traffic (TR)</option>
                    <option value="EN">Engagement (EN)</option>
                    <option value="LD">Leads (LD)</option>
                    <option value="SL">Sales (SL)</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Internal Name</label>
                <input class="form-control" id="c-name" placeholder="Nama internal campaign" required>
            </div>
            <div class="col-12 text-end d-flex justify-content-end gap-2">
                <button type="button" class="btn-gold" style="background:var(--text-muted);display:none;" id="c-btn-cancel" onclick="cancelEditCampaign()">Batal</button>
                <button class="btn-gold" type="submit" id="c-btn-submit">Commit Campaign</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table" id="tbl-campaigns">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Brand</th>
                        <th>Obj</th>
                        <th>Name</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="tbl-campaigns-body">
                    <tr><td colspan="5" class="text-center text-muted py-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
/* =====================================================
 *  CAMPAIGNS — DB-Connected CRUD
 * ===================================================== */
const CAMPAIGN_URL   = "{{ route('leads-tracker.campaign.getData') }}";
const CAMPAIGN_STORE = "{{ route('leads-tracker.campaign.store') }}";

function loadCampaigns(cb) {
    fetch(CAMPAIGN_URL)
        .then(r => r.json())
        .then(res => {
            if (!res.success) { notify('Gagal memuat campaign'); return; }
            renderCampaignTable(res.data);
            // Sync ke state lokal supaya selector adset bisa dipakai
            state.campaigns = res.data.map(c => ({
                id: String(c.id), brand: c.brand,
                objective: c.objective, name: c.name, _code: c.code,
            }));
            updateSelectors();
            if (cb) cb(res.data);
        })
        .catch(() => notify('Error memuat campaign'));
}

function renderCampaignTable(data) {
    const tbody = document.getElementById('tbl-campaigns-body');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada campaign.</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(c => `
        <tr>
            <td><span class="badge-code" onclick="copy('${c.code}')">${c.code}</span></td>
            <td class="fw-bold text-warning">${c.brand}</td>
            <td>${c.objective}</td>
            <td>${c.name}</td>
            <td class="text-end">
                <button class="btn-action me-1" onclick="editCampaign(${c.id},'${escapeAttr(c.brand)}','${c.objective}','${escapeAttr(c.name)}')">
                    <i data-lucide="edit"></i>
                </button>
                <button class="btn-action" onclick="deleteCampaign(${c.id},'${escapeAttr(c.name)}')">
                    <i data-lucide="trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    lucide.createIcons();
}

document.getElementById('form-campaign').addEventListener('submit', function(e) {
    e.preventDefault();
    const editId = document.getElementById('c-edit-id').value;
    const payload = {
        brand: document.getElementById('c-brand').value.trim(),
        objective: document.getElementById('c-obj').value,
        name: document.getElementById('c-name').value.trim(),
        _token: CSRF_TOKEN,
    };
    const url    = editId ? `/leads-tracker/campaign/${editId}` : CAMPAIGN_STORE;
    const method = editId ? 'PUT' : 'POST';

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ ...payload, _method: editId ? 'PUT' : undefined }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            notify(res.message);
            this.reset(); document.getElementById('c-edit-id').value = '';
            document.getElementById('c-btn-cancel').style.display = 'none';
            document.getElementById('c-btn-submit').textContent = 'Commit Campaign';
            loadCampaigns();
        } else { notify('Gagal: ' + (res.message || 'Error')); }
    })
    .catch(err => notify('Request error: ' + err));
});

window.editCampaign = function(id, brand, objective, name) {
    document.getElementById('c-edit-id').value = id;
    document.getElementById('c-brand').value   = brand;
    document.getElementById('c-obj').value     = objective;
    document.getElementById('c-name').value    = name;
    document.getElementById('c-btn-cancel').style.display = 'inline-flex';
    document.getElementById('c-btn-submit').textContent = 'Update Campaign';
    window.scrollTo({ top: document.getElementById('tab-campaigns').offsetTop - 120, behavior: 'smooth' });
};

window.cancelEditCampaign = function() {
    document.getElementById('form-campaign').reset();
    document.getElementById('c-edit-id').value = '';
    document.getElementById('c-btn-cancel').style.display = 'none';
    document.getElementById('c-btn-submit').textContent = 'Commit Campaign';
};

window.deleteCampaign = function(id, name) {
    if (!confirm(`Hapus campaign "${name}"?`)) return;
    fetch(`/leads-tracker/campaign/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'DELETE' }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) { notify(res.message); loadCampaigns(); } else notify('Gagal: ' + res.message); });
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('[data-tab="campaigns"]')?.addEventListener('click', () => setTimeout(loadCampaigns, 50));
    loadCampaigns();
});
</script>