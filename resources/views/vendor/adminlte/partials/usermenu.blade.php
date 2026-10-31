@php
    $user = auth()->user();
    $name = $user->name ?? ($user->email ?? 'Guest');
    $avatar = (config('adminlte.usermenu_image') && ! empty($user?->profile_photo_url))
        ? $user->profile_photo_url
        : asset('vendor/adminlte/img/user2-160x160.jpg');
    $memberSince = $user?->created_at ? $user->created_at->format('M. Y') : null;
@endphp
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
       
        <span class="d-none d-md-inline"><i class="fa-solid fa-user"></i></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end" syle="width: auto;">
        {{-- User image --}}
        
        {{-- Footer --}}
        <li class="user-footer">
           
            <a href="#" class="btn btn-outline-danger float-end"
               onclick="event.preventDefault(); document.getElementById('adminlte-logout-form').submit();">
                {{ __('adminlte.sign_out') }}
            </a>
            <form id="adminlte-logout-form" action="{{ url('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</li>
