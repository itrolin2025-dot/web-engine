@php
    $level = $level ?? 1; // default level 1 (root)
@endphp

@foreach ($menus as $menu)
    @php
        $hasChildren = !empty($menu['children']);
        $collapseId = 'menu-' . $menu['id'];
    @endphp

    {{-- ========================= --}}
    {{-- LEVEL 1 (ROOT MENUS)      --}}
    {{-- ========================= --}}
    @if ($level === 1)

        {{-- ROOT MENU TANPA CHILD --}}
        @if (! $hasChildren)
            <li>
                <a
                    href="{{ (!empty($menu['kode']) && $menu['kode'] !== '#') ? url('admin/' . ltrim($menu['kode'], '/')) : '#' }}"
                    data-default-class="text-slate-600 hover:text-slate-800 dark:text-navy-200 dark:hover:text-navy-50"
                    data-active-class="font-medium text-primary dark:text-accent-light"
                    class="nav-link flex py-2 text-xs-plus tracking-wide outline-hidden transition-colors duration-300 ease-in-out"
                >
                    {{ $menu['name'] }}
                </a>
            </li>

        {{-- ROOT MENU PARENT --}}
        @else
            <li class="ac nav-parent 
                [&.is-active_svg]:rotate-90 
                [&.is-active_.ac-trigger]:font-semibold 
                [&.is-active_.ac-trigger]:text-slate-800 
                dark:[&.is-active_.ac-trigger]:text-navy-50"
            >
                <button
                    class="ac-trigger flex w-full items-center justify-between py-2 text-xs-plus tracking-wide text-slate-600 outline-hidden transition-[color] duration-300 ease-in-out hover:text-slate-800 dark:text-navy-200 dark:hover:text-navy-50"
                >
                    <span>{{ $menu['name'] }}</span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="size-4 text-slate-400 transition-transform ease-in-out"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <ul class="ac-panel">
                    @foreach ($menu['children'] as $child)
                        @include('components.menu-item', [
                            'menus' => [$child],
                            'level' => 2
                        ])
                    @endforeach
                </ul>
            </li>
        @endif



    {{-- ========================= --}}
    {{-- LEVEL 2+ (CHILD MENUS)    --}}
    {{-- ========================= --}}
    @else
        <li>
            <a
                href="{{ (!empty($menu['kode']) && $menu['kode'] !== '#') ? url('admin/' . ltrim($menu['kode'], '/')) : '#' }}"
                class="nav-link flex items-center gap-2 p-2 pl-6 text-xs-plus tracking-wide outline-hidden transition-[color,padding-left] duration-300 ease-in-out hover:pl-8"
                data-default-class="text-slate-600 hover:text-slate-800 dark:text-navy-200 dark:hover:text-navy-50"
                data-active-class="font-medium text-primary dark:text-accent-light"
            >
                {{-- Dot Indicator --}}
                <div class="nav-link flex items-center gap-2 p-2 pl-12 hover:pl-14" ></div>

                <span>{{ $menu['name'] }}</span>
            </a>
        </li>

        {{-- Jika ada nested child lagi --}}
        @if ($hasChildren)
            <ul class="ml-4">
                @foreach ($menu['children'] as $child)
                    @include('components.menu-item', [
                        'menus' => [$child],
                        'level' => $level + 1
                    ])
                @endforeach
            </ul>
        @endif
    @endif
@endforeach
