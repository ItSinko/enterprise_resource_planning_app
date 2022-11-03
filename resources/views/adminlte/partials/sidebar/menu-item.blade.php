@inject('menuItemHelper', 'App\Helpers\MenuItemHelper')

@if (isset($item['auth']))
    @foreach ($item['auth'] as $user)
        @if (isset(Auth::user()->karyawan->divisi_id) && $user == Auth::user()->karyawan->divisi_id)
            @if ($menuItemHelper->isHeader($item))
                {{-- Header --}}
                @include('adminlte.partials.sidebar.menu-item-header')
            @elseif ($menuItemHelper->isSearchBar($item))
                {{-- Search form --}}
                @include('adminlte.partials.sidebar.menu-item-search-form')
            @elseif ($menuItemHelper->isSubmenu($item))
                {{-- Treeview menu --}}
                @include('adminlte.partials.sidebar.menu-item-treeview-menu')
            @elseif ($menuItemHelper->isLink($item))
                {{-- Link --}}
                @include('adminlte.partials.sidebar.menu-item-link')
            @endif
        @endif
    @endforeach
@else
    @if ($menuItemHelper->isHeader($item))
        {{-- Header --}}
        @include('adminlte.partials.sidebar.menu-item-header')
    @elseif ($menuItemHelper->isSearchBar($item))
        {{-- Search form --}}
        @include('adminlte.partials.sidebar.menu-item-search-form')
    @elseif ($menuItemHelper->isSubmenu($item))
        {{-- Treeview menu --}}
        @include('adminlte.partials.sidebar.menu-item-treeview-menu')
    @elseif ($menuItemHelper->isLink($item))
        {{-- Link --}}
        @include('adminlte.partials.sidebar.menu-item-link')
    @endif

@endif
