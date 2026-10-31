@php
    $navLeft = app('adminlte')->menu('navbar-left');
@endphp
<nav class="app-header {{ config('adminlte.classes_topnav', 'navbar-expand bg-body') }} navbar">
    <div class="{{ config('adminlte.classes_topnav_container', 'container-fluid') }}">
        {{-- Left side --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="{{ __('Toggle sidebar') }}">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </a>
            </li>

          
      

        </ul>

        {{-- Right side --}}
        <ul class="navbar-nav ms-auto">
            

            {{-- Color mode toggle --}}
            @if (config('adminlte.color_mode_toggle', true))
             @include('adminlte::partials.color-mode')
            @endif

            {{-- User menu --}}
            @if (config('adminlte.usermenu_enabled', true))
                @include('adminlte::partials.usermenu')
            @endif
        </ul>
    </div>
</nav>

@include('adminlte::partials.command-palette')
