<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ROLIN Meta Tracker | Elite v4.5</title>

    <!-- UI Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=JetBrains+Mono:wght@500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="{{ asset('css/custom/leads.css') }}">

    <script>
        // Global variables for all tabs
        var CSRF_TOKEN = "{{ csrf_token() }}";
        if (!window.state) window.state = { targeting: [], campaigns: [], adsets: [], creatives: [], leads: [] };
        const escapeAttr = (s) => s ? String(s).replace(/'/g, "\\'").replace(/"/g, "&quot;") : '';
    </script>
</head>

<body data-theme="light">

    <div class="nav-wrapper">
        <nav class="top-nav">
            <div class="nav-brand">Rolin Elite</div>
            <div class="nav-menu">
                <a href="#" class="nav-link-item active" data-tab="dashboard"><i data-lucide="home"></i>
                        </a>

                @if(isset($canDetail) && $canDetail)
                    <a href="#" class="nav-link-item" data-tab="overview"><i data-lucide="layout-dashboard"></i>
                        <span>Overview</span></a>
                    <a href="#" class="nav-link-item" data-tab="targeting"><i data-lucide="target"></i>
                        <span>Targeting</span></a>
                    <a href="#" class="nav-link-item" data-tab="campaigns"><i data-lucide="megaphone"></i>
                        <span>Campaigns</span></a>
                    <a href="#" class="nav-link-item" data-tab="adsets"><i data-lucide="layers"></i> <span>Ad
                            Sets</span></a>
                    <a href="#" class="nav-link-item" data-tab="creatives"><i data-lucide="image"></i> <span>Ads</span></a>
                @endif

                @if(isset($canAdd) && $canAdd)
                    <a href="#" class="nav-link-item" data-tab="leads"><i data-lucide="user-plus"></i>
                        <span>Leads</span></a>
                @endif

                @if(isset($canDetail) && $canDetail)
                    <a href="#" class="nav-link-item" data-tab="info"><i data-lucide="info"></i> <span>Info</span></a>
                @endif
            </div>

            <div class="d-flex align-items-center">
                <button class="theme-toggle" id="theme-btn"><i data-lucide="moon"></i></button>
                <div class="dropdown me-2">
                    <button class="theme-toggle btn-action" data-bs-toggle="dropdown"><i data-lucide="settings"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2 rounded-4 mt-2">
                        <!-- <li><button class="dropdown-item rounded-3 py-2" id="op-export"><i data-lucide="download"
                                    class="me-2" style="width:16px"></i>Export CSV</button></li>
                        <li><button class="dropdown-item rounded-3 py-2" id="op-backup"><i data-lucide="database"
                                    class="me-2" style="width:16px"></i>Backup JSON</button></li>
                        <li><label class="dropdown-item rounded-3 py-2 mb-0 cursor-pointer"><i data-lucide="upload"
                                    class="me-2" style="width:16px"></i>Restore<input type="file" id="op-restore"
                                    hidden></label></li>
                        <li> -->
                        <h5>&nbsp; Hi, {{ Auth::user()->name }}</h5>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a href="{{ route('dashboard') }}" class="dropdown-item rounded-3 py-2 text-danger">
                                <i data-lucide="log-out" class="me-2" style="width:16px"></i>Exit
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container">
        
        @include('leads-tracker.app.dashboard')

        @include('leads-tracker.app.overview')
        
        @include('leads-tracker.app.targeting')

        @include('leads-tracker.app.campaigns')

        @include('leads-tracker.app.adsets')

        @include('leads-tracker.app.ads')

        @include('leads-tracker.app.leads')

        @include('leads-tracker.app.info')

    </div>


    <div id="toast-container"></div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function notify(msg) {
            const c = document.getElementById('toast-container');
            if(!c) return;
            const t = document.createElement('div');
            t.className = "toast-box animate-fade mt-2"; t.innerText = msg;
            c.appendChild(t);
            setTimeout(() => t.remove(), 2500);
        }

        function copy(txt) {
            if (!txt) return;
            navigator.clipboard.writeText(txt).then(() => {
                notify("Copied: " + (txt.length > 20 ? txt.substring(0, 20) + '...' : txt));
            }).catch(() => {
                const el = document.createElement('textarea');
                el.value = txt; document.body.appendChild(el);
                el.select(); document.execCommand('copy');
                document.body.removeChild(el); notify("Copied: " + txt);
            });
        }

        /* Tab Switching Logic */
        document.querySelectorAll(".nav-link-item").forEach(p => {
            p.onclick = (e) => {
                e.preventDefault();
                const tabName = p.dataset.tab;
                
                // UI updates - remove active from all links
                document.querySelectorAll(".nav-link-item").forEach(x => x.classList.remove("active"));
                p.classList.add("active");
                
                // Hide only top-level sections that are tabs
                document.querySelectorAll(".container > section").forEach(s => {
                    if (s.id && s.id.startsWith('tab-')) {
                        s.classList.add("d-none");
                    }
                });
                
                const targetTab = document.getElementById(`tab-${tabName}`);
                if (targetTab) {
                    targetTab.classList.remove("d-none");
                }

                // Trigger specific tab loads if needed (though most have their own click listeners)
                if (tabName === 'overview' && typeof loadOverview === 'function') {
                    loadOverview();
                }
                
                lucide.createIcons();
            };
        });

        // Global function to sync selectors between tabs
        function updateSelectors() {
            const sCamp = document.getElementById("s-camp");
            const sTarget = document.getElementById("s-target");
            const crAdset = document.getElementById("cr-adset");

            // Update AdSet -> Campaign selector
            if (sCamp && state.campaigns) {
                const current = sCamp.value;
                sCamp.innerHTML = `<option value="">Select Campaign</option>` + state.campaigns.map(c => 
                    `<option value="${c.id}">${c._code || c.id} - ${c.name}</option>`
                ).join("");
                sCamp.value = current;
            }

            // Update AdSet -> Targeting selector
            if (sTarget && state.targeting) {
                const current = sTarget.value;
                sTarget.innerHTML = `<option value="">Select Targeting</option>` + state.targeting.map(t => 
                    `<option value="${t.id}">${t._code || t.id} - ${t.label}</option>`
                ).join("");
                sTarget.value = current;
            }
            
            // Note: Ads -> Adset selector is usually handled by populateCreativeAdsetSelector() in ads.blade.php
            // but we can add it here if needed for sync.
        }

        window.onload = () => {
            // Theme toggle
            const themeBtn = document.getElementById('theme-btn');
            if (themeBtn) {
                themeBtn.onclick = () => {
                    const b = document.body;
                    const t = b.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                    b.setAttribute('data-theme', t);
                    themeBtn.innerHTML = `<i data-lucide="${t === 'light' ? 'moon' : 'sun'}"></i>`;
                    lucide.createIcons();
                };
            }
            lucide.createIcons();
            
            // Show the default active tab if not already shown
            const activeTabLink = document.querySelector('.nav-link-item.active');
            if (activeTabLink) {
                const tabName = activeTabLink.dataset.tab;
                document.querySelectorAll(".container > section").forEach(s => {
                    if (s.id && s.id.startsWith('tab-')) {
                        s.classList.add("d-none");
                    }
                });
                const targetTab = document.getElementById(`tab-${tabName}`);
                if (targetTab) targetTab.classList.remove("d-none");
            }
        };
    </script>
</body>
</html>