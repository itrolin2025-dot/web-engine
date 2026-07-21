<section id="tab-dashboard">
    <div class="dashboard-welcome">

        {{-- Greeting --}}
        <div class="welcome-hero">
            <div class="welcome-glow"></div>
            <div class="welcome-content">
                <div class="welcome-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="welcome-text">
                    <p class="welcome-sub">Selamat datang kembali 👋</p>
                    <h1 class="welcome-name">{{ Auth::user()->name }}</h1>
                    <p class="welcome-role">{{ Auth::user()->role->name ?? 'User' }} &mdash; ROLIN Meta Tracker</p>
                </div>
            </div>
            <div class="welcome-date">
                <i data-lucide="calendar" style="width:16px;display:inline"></i>
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>

        <!-- {{-- Quick Stats --}}
        <div class="row g-4 mt-1 mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="stat-label"><i data-lucide="target" style="width:13px"></i> Targeting</div>
                    <div class="stat-val dash-count" data-key="targeting">—</div>
                    <div class="stat-foot">Segment aktif</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="stat-label"><i data-lucide="megaphone" style="width:13px"></i> Campaigns</div>
                    <div class="stat-val dash-count" data-key="campaigns">—</div>
                    <div class="stat-foot">Campaign terdaftar</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="stat-label"><i data-lucide="layers" style="width:13px"></i> Ad Sets</div>
                    <div class="stat-val dash-count" data-key="adsets">—</div>
                    <div class="stat-foot">Ad Set aktif</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="stat-label"><i data-lucide="user-plus" style="width:13px"></i> Leads</div>
                    <div class="stat-val dash-count" data-key="leads">—</div>
                    <div class="stat-foot">Lead masuk</div>
                </div>
            </div>
        </div>

        {{-- Quick Access --}}
        <div class="row g-3">
            <div class="col-12">
                <p class="stat-label mb-3"><i data-lucide="zap" style="width:13px"></i> Akses Cepat</p>
            </div>
            @if(isset($canDetail) && $canDetail)
            <div class="col-6 col-md-3">
                <div class="quick-card" onclick="switchTab('overview')">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Overview</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="quick-card" onclick="switchTab('targeting')">
                    <i data-lucide="target"></i>
                    <span>Targeting</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="quick-card" onclick="switchTab('campaigns')">
                    <i data-lucide="megaphone"></i>
                    <span>Campaigns</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="quick-card" onclick="switchTab('adsets')">
                    <i data-lucide="layers"></i>
                    <span>Ad Sets</span>
                </div>
            </div>
            @endif
            @if(isset($canAdd) && $canAdd)
            <div class="col-6 col-md-3">
                <div class="quick-card" onclick="switchTab('leads')">
                    <i data-lucide="user-plus"></i>
                    <span>Input Lead</span>
                </div>
            </div>
            @endif
        </div> -->

    </div>
</section>

<style>
/* ---- Welcome Hero ---- */
.dashboard-welcome { padding: 8px 0 24px; }

.welcome-hero {
    position: relative;
    background: linear-gradient(135deg, var(--brand-dark) 0%, #3a1a4a 100%);
    border-radius: 24px;
    padding: 36px 40px;
    margin-bottom: 0;
    overflow: hidden;
    border: 1px solid rgba(194,146,67,0.3);
}

.welcome-glow {
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(194,146,67,0.25) 0%, transparent 70%);
    pointer-events: none;
}

.welcome-content {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.welcome-avatar {
    width: 66px; height: 66px;
    border-radius: 18px;
    background: var(--accent-gold);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem;
    font-weight: 800;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(194,146,67,0.35);
}

.welcome-sub {
    color: rgba(255,255,255,0.55);
    font-size: 0.82rem;
    font-weight: 600;
    margin: 0 0 4px;
    letter-spacing: 0.5px;
}

.welcome-name {
    font-size: 1.8rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
    line-height: 1.1;
}

.welcome-role {
    color: var(--accent-gold);
    font-size: 0.82rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.welcome-date {
    margin-top: 20px;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.45);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.stat-foot {
    font-size: 0.68rem;
    color: var(--text-muted);
    margin-top: 4px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

/* ---- Quick Cards ---- */
.quick-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    padding: 22px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
}

.quick-card:hover {
    border-color: var(--accent-gold);
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(194,146,67,0.15);
}

.quick-card i {
    width: 28px; height: 28px;
    color: var(--accent-gold);
}

.quick-card span {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-main);
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

@media (max-width: 576px) {
    .welcome-hero { padding: 24px 20px; }
    .welcome-name { font-size: 1.4rem; }
    .welcome-avatar { width: 52px; height: 52px; font-size: 1.4rem; border-radius: 14px; }
}
</style>

<script>
/* ---- Quick Access Tab Switcher ---- */
window.switchTab = function(tabName) {
    document.querySelectorAll('.nav-link-item').forEach(x => x.classList.remove('active'));
    document.querySelectorAll('section').forEach(s => s.classList.add('d-none'));

    const tabEl = document.getElementById('tab-' + tabName);
    if (tabEl) tabEl.classList.remove('d-none');

    const navLink = document.querySelector(`.nav-link-item[data-tab="${tabName}"]`);
    if (navLink) navLink.classList.add('active');

    refreshUI(tabName === 'dashboard');
    if (tabName === 'targeting') loadTargetings();
};

/* ---- Isi stat card dari state (localStorage) & DB ---- */
function renderDashboardStats() {
    // Campaigns, Adsets, Leads dari localStorage state
    const keys = ['campaigns', 'adsets', 'leads'];
    keys.forEach(k => {
        const el = document.querySelector(`.dash-count[data-key="${k}"]`);
        if (el) el.textContent = (state[k] || []).length;
    });
    // Targeting dari DB
    fetch("{{ route('leads-tracker.targeting.getData') }}")
        .then(r => r.json())
        .then(res => {
            const el = document.querySelector('.dash-count[data-key="targeting"]');
            if (el && res.success) el.textContent = res.data.length;
        })
        .catch(() => {});
}

document.addEventListener('DOMContentLoaded', function() {
    renderDashboardStats();
    lucide.createIcons();
});
</script>