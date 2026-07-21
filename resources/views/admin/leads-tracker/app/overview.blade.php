<section id="tab-overview" class="d-none">
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="stat-label">Total Leads</div>
                <div class="stat-val" id="ov-leads">0</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="stat-label">Qualified</div>
                <div class="stat-val" id="ov-qual">0</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="stat-label">Won Rate</div>
                <div class="stat-val" id="ov-won">0%</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="stat-label">Total Spend</div>
                <div class="stat-val text-truncate" id="ov-spend">0</div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <h6 class="stat-label mb-4">Lead Performance by Segment (Code)</h6>
                <div style="height:320px"><canvas id="ov-chart-area"></canvas></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <h6 class="stat-label mb-4">Investment by Brand</h6>
                <div style="height:320px"><canvas id="ov-chart-brand"></canvas></div>
            </div>
        </div>
    </div>
</section>

<script>
let chartArea = null;
let chartBrand = null;

function loadOverview() {
    fetch("{{ route('leads-tracker.overview.getData') }}")
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            
            // Update KPIs
            document.getElementById('ov-leads').textContent = res.kpi.total_leads;
            document.getElementById('ov-qual').textContent  = res.kpi.qualified;
            document.getElementById('ov-won').textContent   = res.kpi.won_rate;
            document.getElementById('ov-spend').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(res.kpi.total_spend);

            // Update Charts
            renderOverviewCharts(res.chart_segment, res.chart_brand);
        })
        .catch(err => console.error('Overview error:', err));
}

function renderOverviewCharts(segmentData, brandData) {
    // Lead Performance by Segment (Bar Chart)
    const ctxArea = document.getElementById('ov-chart-area').getContext('2d');
    if (chartArea) chartArea.destroy();
    chartArea = new Chart(ctxArea, {
        type: 'bar',
        data: {
            labels: segmentData.labels,
            datasets: [{
                label: 'Leads',
                data: segmentData.data,
                backgroundColor: '#C29243',
                borderRadius: 8
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
        }
    });

    // Investment by Brand (Doughnut Chart)
    const ctxBrand = document.getElementById('ov-chart-brand').getContext('2d');
    if (chartBrand) chartBrand.destroy();
    chartBrand = new Chart(ctxBrand, {
        type: 'doughnut',
        data: {
            labels: brandData.labels,
            datasets: [{
                data: brandData.data,
                backgroundColor: ['#24112C', '#C29243', '#fbbf24', '#6b7280', '#e2e8f0']
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

// Trigger load when tab is clicked
document.addEventListener('DOMContentLoaded', () => {
    const navItem = document.querySelector('.nav-link-item[data-tab="overview"]');
    if (navItem) {
        navItem.addEventListener('click', () => {
            setTimeout(loadOverview, 100);
        });
    }
    // Initial load if overview is active
    if (document.querySelector('.nav-link-item.active')?.dataset.tab === 'overview') {
        loadOverview();
    }
});
</script>