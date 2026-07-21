
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 1.5em;">
        
        <div class="alert flex rounded-lg bg-error px-4 py-4 text-white sm:px-5" style="margin-bottom: 3em;">
            {{ session('error') }}
        </div>

    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 1.5em;">
        
        <div class="alert flex rounded-lg bg-success px-4 py-4 text-white sm:px-5" style="margin-bottom: 3em;">
            {{ session('success') }}
        </div>
    </div>
@endif

<div id="js-notification-area" style="position: fixed; top: 30px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 400px; max-width: 90%;"></div>