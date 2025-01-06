<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}" target="_blank">
            <img src="{{ asset('img/LaraBuild(transparent)-03.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">LaraBuild</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (env('LARA_BUILD'))
                @include('sidenav')
            @endif
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Manage</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(Route::currentRouteName(), 'user') ? 'active' : '' }}"
                    href="{{ route('user.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-user text-primary text-sm opacity-10 pb-1" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Users</span>
                </a>
            </li>
            @php
                $jsonFilePath = resource_path('views/layouts/crud.json');

                $generatedItems = [];

                if (File::exists($jsonFilePath)) {
                    $generatedItems = json_decode(File::get($jsonFilePath), true);
                }
            @endphp
            @foreach ($generatedItems as $item)
                @if (in_array(auth()->user()->role, $item['role']))
                    <li class="nav-item">
                        <a class="nav-link {{ str_contains(Route::currentRouteName(), $item['route']) ? 'active' : '' }}"
                            href="{{ route($item['route'] . '.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa {{ $item['icon'] }} text-primary text-sm opacity-10 pb-1"
                                    aria-hidden="true"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>
