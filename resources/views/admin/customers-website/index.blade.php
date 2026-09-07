<x-app-layout>
    <style>
        /* === Table Header === */
        .customers-table thead th {
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.06em !important;
            color: #94a3b8 !important;
            padding: 0.875rem 1rem !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        }

        /* === Table Body === */
        .customers-table tbody td {
            font-size: 0.875rem !important;
            color: #cbd5e1 !important;
            padding: 0.875rem 1rem !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        }

        .customers-table tbody tr:last-child td {
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        }

        .customers-table tbody tr:hover td {
            background-color: rgba(255,255,255,0.02) !important;
        }
    </style>
    <div>
        <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
                <div class="hidden h-full py-1 sm:flex">
                    <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
                </div>
                <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
                    <li class="flex items-center space-x-2">
                        <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <i class="fa-solid fa-angle-right text-xs"></i>
                    </li>
                    <li>{{ $modul_type }}</li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-warning bg-warning/10 p-4 text-warning dark:border-warning dark:bg-warning/5 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    <p class="font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        {{-- DataTables Controls (outside card) --}}
        <div id="dt-controls" class="flex items-center justify-between gap-3 mb-4 px-1">
            <div id="dt-length-area"></div>
            <div class="flex items-center gap-3">
                <div id="dt-search-area"></div>
                @if($canAdd)
                <a href="{{ route('admin.customers-website.create') }}"
                    class="btn h-9 px-3 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span class="text-xs">Add</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card overflow-hidden" style="padding: 0 !important;">
            <table class="w-full text-left customers-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">#</th>
                        <th>Title</th>
                        <th>Customer</th>
                        <th>Template</th>
                        <th>Domain</th>
                        <th style="text-align:center;">Status</th>
                        <th style="width:150px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($websites as $index => $website)
                        <tr>
                            <td style="text-align:center;">{{ $index + 1 }}</td>
                            <td>
                                <p class="font-semibold dark:text-navy-100 text-sm">{{ $website->title }}</p>
                                @if($website->description)
                                    <p class="text-xs text-slate-400 dark:text-navy-300 truncate max-w-xs">{{ $website->description }}</p>
                                @endif
                            </td>
                            <td>{{ $website->customer->name ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-full bg-slate-150 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-navy-500 dark:text-navy-100">
                                    {{ $website->template->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-xs font-mono">
                                @if($website->domain)
                                    <a href="{{ Str::startsWith($website->domain, 'http') ? $website->domain : 'https://' . $website->domain }}"
                                        target="_blank" class="text-primary hover:underline dark:text-accent-light">
                                        {{ $website->domain }} <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <span class="badge rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $website->is_active ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                                    {{ $website->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div class="flex justify-end space-x-1.5">
                                    @if($canEdit)
                                        <a href="{{ route('admin.customers-website.page', $website->id) }}"
                                            class="btn h-8 w-8 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20"
                                            title="Manage Pages">
                                            <i class="fa-solid fa-list text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.customers-website.edit', $website->id) }}"
                                            class="btn h-8 w-8 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20"
                                            title="Edit Website">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                    @endif
                                    @if($canDelete)
                                        <form action="{{ route('admin.customers-website.destroy', $website->id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this customer website?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn h-8 w-8 rounded-full bg-error/10 p-0 font-medium text-error hover:bg-error/20 focus:bg-error/20"
                                                title="Delete Website">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400 dark:text-navy-300">
                                <i class="fa-solid fa-globe text-3xl mb-2 opacity-40"></i>
                                <p class="text-sm">No Customer Websites found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
