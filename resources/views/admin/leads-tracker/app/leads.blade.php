<!-- LEADS -->
<section id="tab-leads" class="d-none">
    <div class="card">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <h5 class="fw-bold m-0">Conversion Desk</h5>
            <input type="text" id="lead-search" class="form-control rounded-pill px-4" style="width: 300px"
                placeholder="Search nama atau WA...">
        </div>
        <form id="form-lead" class="row g-3 mb-4 p-4 rounded-4" style="background: var(--bg-cream)">
            <input type="hidden" id="l-edit-id">
            <div class="col-md-2">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" id="l-date">
            </div>
            <div class="col-md-2">
                <label class="form-label">Ref Token</label>
                <input class="form-control" id="l-ref" placeholder="e.g. A3X7">
            </div>
            <div class="col-md-2">
                <label class="form-label">Title</label>
                <select class="form-select" id="l-title">
                    <option>Mr.</option>
                    <option>Mrs.</option>
                    <option>Ms.</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Prospect Name</label>
                <input class="form-control" id="l-name" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">WhatsApp Contact</label>
                <input class="form-control" id="l-wa" placeholder="628xxx">
            </div>
            <div class="col-md-6">
                <label class="form-label">Pipeline Status</label>
                <select class="form-select" id="l-status">
                    <option>Fresh Lead</option>
                    <option>In Discussion</option>
                    <option>Qualified</option>
                    <option>Closed Won</option>
                    <option>Closed Lost</option>
                </select>
            </div>
            <div class="col-12 text-end d-flex justify-content-end gap-2">
                <button type="button" class="btn-gold" style="background:var(--text-muted);display:none;" id="l-btn-cancel" onclick="cancelEditLtLead()">Batal</button>
                <button class="btn-gold px-5" type="submit" id="l-btn-submit">Log Entry</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table" id="tbl-leads">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Date</th>
                        <th>Prospect</th>
                        <th>WhatsApp</th>
                        <th style="min-width: 160px">Pipeline</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="tbl-leads-body">
                    <tr><td colspan="6" class="text-center text-muted py-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
/* =====================================================
 *  LT LEADS — DB-Connected CRUD
 * ===================================================== */
const LEAD_URL   = "{{ route('leads-tracker.lt-lead.getData') }}";
const LEAD_STORE = "{{ route('leads-tracker.lt-lead.store') }}";
const STATUSES = ['Fresh Lead','In Discussion','Qualified','Closed Won','Closed Lost'];

function loadLtLeads(q = '') {
    const url = q ? `${LEAD_URL}?q=${encodeURIComponent(q)}` : LEAD_URL;
    fetch(url)
        .then(r => r.json())
        .then(res => {
            if (!res.success) { notify('Gagal memuat leads'); return; }
            renderLeadTable(res.data);
            // Sync ke state lokal
            state.leads = res.data.map(l => ({
                id: String(l.id), ref: l.ref, date: l.lead_date,
                title: l.title, name: l.name, wa: l.wa, status: l.status,
            }));
        })
        .catch(() => notify('Error memuat leads'));
}

function renderLeadTable(data) {
    const tbody = document.getElementById('tbl-leads-body');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada lead.</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(l => {
        const opts = STATUSES.map(s => `<option ${l.status === s ? 'selected' : ''}>${s}</option>`).join('');
        const statusClass = {
            'Fresh Lead': 'text-secondary', 'In Discussion': 'text-primary',
            'Qualified': 'text-warning', 'Closed Won': 'text-success', 'Closed Lost': 'text-danger'
        }[l.status] || '';
        return `
        <tr>
            <td><span class="badge-code" onclick="copy('${l.ref || '-'}')">${l.ref || '-'}</span></td>
            <td class="small text-muted">${l.lead_date || '-'}</td>
            <td class="fw-bold">${l.title} ${l.name}</td>
            <td class="small mono">${l.wa || '-'}</td>
            <td>
                <select class="form-select form-select-sm ${statusClass}"
                    onchange="updateLtLeadStatus(${l.id}, this.value)">${opts}</select>
            </td>
            <td class="text-end">
                <button class="btn-action me-1"
                    onclick="editLtLead(${l.id},'${l.lead_date||''}','${escapeAttr(l.ref||'')}','${l.title}','${escapeAttr(l.name)}','${escapeAttr(l.wa||'')}','${l.status}')">
                    <i data-lucide="edit"></i>
                </button>
                <button class="btn-action" onclick="deleteLtLead(${l.id},'${escapeAttr(l.name)}')">
                    <i data-lucide="trash"></i>
                </button>
            </td>
        </tr>`;
    }).join('');
    lucide.createIcons();
}

document.getElementById('form-lead').addEventListener('submit', function(e) {
    e.preventDefault();
    const editId = document.getElementById('l-edit-id').value;
    const payload = {
        lead_date: document.getElementById('l-date').value || null,
        ref:       document.getElementById('l-ref').value.trim(),
        title:     document.getElementById('l-title').value,
        name:      document.getElementById('l-name').value.trim(),
        wa:        document.getElementById('l-wa').value.trim(),
        status:    document.getElementById('l-status').value,
        _token:    CSRF_TOKEN,
    };
    const url = editId ? `/leads-tracker/lt-lead/${editId}` : LEAD_STORE;

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ ...payload, _method: editId ? 'PUT' : undefined }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            notify(res.message); this.reset();
            document.getElementById('l-edit-id').value = '';
            document.getElementById('l-btn-cancel').style.display = 'none';
            document.getElementById('l-btn-submit').textContent = 'Log Entry';
            loadLtLeads();
        } else notify('Gagal: ' + (res.message || 'Error'));
    })
    .catch(err => notify('Request error: ' + err));
});

window.editLtLead = function(id, date, ref, title, name, wa, status) {
    document.getElementById('l-edit-id').value = id;
    document.getElementById('l-date').value    = date;
    document.getElementById('l-ref').value     = ref;
    document.getElementById('l-title').value   = title;
    document.getElementById('l-name').value    = name;
    document.getElementById('l-wa').value      = wa;
    document.getElementById('l-status').value  = status;
    document.getElementById('l-btn-cancel').style.display = 'inline-flex';
    document.getElementById('l-btn-submit').textContent = 'Update Lead';
    window.scrollTo({ top: document.getElementById('tab-leads').offsetTop - 120, behavior: 'smooth' });
};

window.cancelEditLtLead = function() {
    document.getElementById('form-lead').reset();
    document.getElementById('l-edit-id').value = '';
    document.getElementById('l-btn-cancel').style.display = 'none';
    document.getElementById('l-btn-submit').textContent = 'Log Entry';
};

window.updateLtLeadStatus = function(id, status) {
    fetch(`/leads-tracker/lt-lead/${id}/status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'PATCH', status: status }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) notify('Status: ' + status); });
};

window.deleteLtLead = function(id, name) {
    if (!confirm(`Hapus lead "${name}"?`)) return;
    fetch(`/leads-tracker/lt-lead/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ _method: 'DELETE' }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) { notify(res.message); loadLtLeads(); } else notify('Gagal: ' + res.message); });
};

// Search handler — override yang lama dari index.blade
document.getElementById('lead-search').addEventListener('input', function() {
    loadLtLeads(this.value);
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('[data-tab="leads"]')?.addEventListener('click', () => setTimeout(loadLtLeads, 50));
    loadLtLeads();
});
</script>