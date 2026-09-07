
@if(session('error'))
    <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-warning bg-warning/10 p-4 text-warning dark:border-warning dark:bg-warning/5 mb-4">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
@endif

<!-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 1.5em;">
        
        <div class="alert flex rounded-lg bg-success px-4 py-4 text-white sm:px-5" style="margin-bottom: 3em;">
            {{ session('success') }}
        </div>
    </div>
@endif -->

@if(session('success'))
    <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mb-6">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

<div id="js-notification-area" style="position: fixed; top: 30px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 400px; max-width: 90%;"></div>