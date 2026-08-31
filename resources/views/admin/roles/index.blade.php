<x-app-layout>
    <!-- PAGE HEADER -->
    <div class="flex items-center space-x-4 py-5 lg:py-6">
    </div>
    
    @include('components.other') <!-- delete soon -->
    
    @include('admin.' . $modul_path . '.partials.table')
    
</x-app-layout>

