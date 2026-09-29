@php
    // Literal class strings (kept here so the Tailwind scanner picks them up)
    $notificationBoxClasses = [
        'warning'   => 'bg-warning/10 dark:bg-warning/15',
        'info'      => 'bg-info/10 dark:bg-info/15',
        'success'   => 'bg-success/10 dark:bg-success/15',
        'error'     => 'bg-error/10 dark:bg-error/15',
        'secondary' => 'bg-secondary/10 dark:bg-secondary-light/15',
        'primary'   => 'bg-primary/10 dark:bg-accent-light/15',
    ];

    $notificationTextClasses = [
        'warning'   => 'text-warning',
        'info'      => 'text-info',
        'success'   => 'text-success',
        'error'     => 'text-error',
        'secondary' => 'text-secondary dark:text-secondary-light',
        'primary'   => 'text-primary dark:text-accent-light',
    ];
@endphp

@forelse($items ?? [] as $item)
    <div class="flex items-center space-x-3">
        <div
            class="flex size-10 shrink-0 items-center justify-center rounded-lg {{ $notificationBoxClasses[$item['color']] ?? $notificationBoxClasses['primary'] }}">
            <i class="{{ $item['icon'] }} {{ $notificationTextClasses[$item['color']] ?? $notificationTextClasses['primary'] }}"></i>
        </div>
        <div class="min-w-0">
            <p class="font-medium text-slate-600 dark:text-navy-100 truncate">
                {{ $item['title'] }}
            </p>
            <div class="mt-1 text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                {{ $item['subtitle'] }}
            </div>
        </div>
    </div>
@empty
    <div class="mt-4 pb-4 text-center">
        <img class="mx-auto w-36" src="images/illustrations/empty-girl-box.svg" alt="image">
        <div class="mt-5">
            <p class="text-base font-semibold text-slate-700 dark:text-navy-100">
                {{ $emptyTitle ?? 'Nothing here yet' }}
            </p>
            <p class="text-slate-400 dark:text-navy-300">
                {{ $emptyText ?? 'There are no notifications yet' }}
            </p>
        </div>
    </div>
@endforelse
