<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
    {{-- Boucle pour générer le menu dynamiquement --}}
    @if(isset($sidebarMenu) && count($sidebarMenu) > 0)
        @foreach($sidebarMenu as $menuItem)
            @if(!empty($menuItem['submenu']))
                <li class="nav-item {{collect($menuItem['route_group'])->contains(fn($pattern) => request()->routeIs($pattern)) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ collect($menuItem['route_group'])->contains(fn($pattern) => request()->routeIs($pattern)) ? 'active' : '' }}">
                        <i class="nav-icon {{ $menuItem['icon'] ?? 'bi bi-circle' }}"></i>
                        <p>
                            {{ $menuItem['title'] }}
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($menuItem['submenu'] as $subMenuItem)
                            @if(Route::has($subMenuItem['route']))
                                <li class="nav-item">
                                    <a wire:navigate href="{{ route($subMenuItem['route']) }}" class="nav-link {{ request()->routeIs($subMenuItem['route']) || (isset($subMenuItem['original_active_check']) && request()->routeIs($subMenuItem['original_active_check'])) ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i> 
                                        <p>{{ $subMenuItem['title'] }}</p>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @else
                {{-- For menu items without a submenu (direct link if defined) --}}
                @if(isset($menuItem['route']) && Route::has($menuItem['route']))  
                    <li class="nav-item">
                        <a wire:navigate href="{{ route($menuItem['route']) }}" class="nav-link {{ request()->routeIs($menuItem['route']) ? 'active' : '' }}">
                            <i class="nav-icon {{ $menuItem['icon'] ?? 'bi bi-circle' }}"></i>
                            <p>{{ $menuItem['title'] }}</p>
                        </a>
                    </li>
                @elseif(isset($menuItem['title'])) 
                    <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon {{ $menuItem['icon'] ?? 'bi bi-circle' }}"></i><p>{{ $menuItem['title'] }} (non-clickable)</p></a></li>
                @endif
            @endif
        @endforeach
    @else
        <li class="nav-item"><a href="#" class="nav-link"><p>Aucun élément de menu disponible.</p></a></li>
    @endif
    {{-- Fin de la boucle de menu dynamique --}}

    {{-- Conserver le lien "Gérer les Permissions" s'il est séparé et contrôlé par un rôle spécifique --}}
    @if (Auth::check() && Auth::user()->hasRole('admin'))
        @if (Route::has('manage.permissions'))
            <li class="nav-item {{ request()->routeIs('manage.permissions') ? 'menu-open' : '' }}">
                <a wire:navigate href="{{ route('manage.permissions') }}" class="nav-link {{ request()->routeIs('manage.permissions') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-shield-lock"></i>
                    <p>Gérer les Permissions</p>
                </a>
            </li>
        @endif
    @endif
</ul>