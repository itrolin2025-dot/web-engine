@foreach ($menus as $menu)
    <li class="{{ !empty($menu['children']) ? 'has-treeview' : '' }}">

        <a href="#" class="nav-link">
            <span>{{ $menu['name'] }}</span>
            @if (!empty($menu['children']))
                <i class="menu-arrow"></i>
            @endif
        </a>

        @if (!empty($menu['children']))
            <ul class="nav nav-treeview">
                @include('components.menu-item', ['menus' => $menu['children']])
            </ul>
        @endif

    </li>
@endforeach
