<li class="nav-item mt-3">
    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">LaraBuild</h6>
</li>
<li class="nav-item">
    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'lara-migration') ? 'active' : '' }}"
        href="{{ route('lara-migration.index') }}">
        <div
            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-database text-primary text-sm opacity-10 pb-1" aria-hidden="true"></i>
        </div>
        <span class="nav-link-text ms-1">Manage Migration</span>
    </a>
    <a class="nav-link {{ Route::currentRouteName() == 'generate-crud' ? 'active' : '' }}"
        href="{{ route('generate-crud') }}">
        <div
            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-bolt text-primary text-sm opacity-10 pb-1" aria-hidden="true"></i>
        </div>
        <span class="nav-link-text ms-1">Generate CRUD</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Route::currentRouteName() == 'form-example' ? 'active' : '' }}"
        href="{{ route('form-example') }}">
        <div
            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-book text-primary text-sm opacity-10 pb-1" aria-hidden="true"></i>
        </div>
        <span class="nav-link-text ms-1">Form Examples</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
        <div
            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-television text-primary text-sm opacity-10 pb-1" aria-hidden="true"></i>
        </div>
        <span class="nav-link-text ms-1">Dashboard Example</span>
    </a>
</li>
