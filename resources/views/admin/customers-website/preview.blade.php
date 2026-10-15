<x-app-layout>
    <div class="flex items-center justify-center min-h-screen bg-slate-50 dark:bg-navy-900 p-6">
        <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-navy-700 dark:bg-navy-800">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-800 dark:text-navy-100">
                    Preview — {{ $website->title ?? 'Website' }}
                </h2>
                <a href="{{ route('admin.customers-website') }}" class="text-xs text-primary hover:underline dark:text-accent-light">
                    Back to list
                </a>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
                <div class="flex justify-between border-b border-slate-200 pb-2 dark:border-navy-700">
                    <span class="text-slate-500 dark:text-navy-300">Domain</span>
                    <span class="font-mono text-slate-800 dark:text-navy-100">{{ $website->domain ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2 dark:border-navy-700">
                    <span class="text-slate-500 dark:text-navy-300">Status</span>
                    <span class="badge rounded-full px-2 py-0.5 text-xs font-semibold {{ $website->is_active ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                        {{ $website->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2 dark:border-navy-700">
                    <span class="text-slate-500 dark:text-navy-300">Selected</span>
                    <span class="text-slate-800 dark:text-navy-100">{{ $website->is_selected ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                @if($website->domain)
                    <a href="{{ url('/' . $website->domain) }}" target="_blank" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i>
                        Open Website
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
