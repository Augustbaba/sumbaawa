<!-- menu -->
<div class="menu">
    <div class="menu-header">
        <a href="{{ route('index') }}" class="menu-header-logo">
            <img src="{{ asset(FrontHelper::getEnvFolder() .'storage/front/assets/images/logo.png') }}" alt="logo">
        </a>
        <a href="{{ route('index') }}" class="btn btn-sm menu-close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>
    <div class="menu-body">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="avatar me-3">
                    @if (auth()->user()->avatar == FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png')
                        <img src="{{ Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset(auth()->user()->avatar) }}"
                            class="rounded-circle" alt="image">
                    @else
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                            class="rounded-circle" alt="image">
                    @endif
                </div>
                <div>
                    <div class="fw-bold">{{ auth()->user()->name?? auth()->user()->email }}</div>
                    <small class="text-muted">{{ auth()->user()->roles->first()->role }}</small>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('profile.show') }}" class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-person dropdown-item-icon"></i> Profil
                </a>

                <a href="{{ route('logout') }}" class="dropdown-item d-flex align-items-center text-danger"
                   target="_blank">
                    <i class="bi bi-box-arrow-right dropdown-item-icon"></i> Se Déconnecter
                </a>
            </div>
        </div>
        <ul>
            <li class="menu-divider">E-Commerce Sumba Awa</li>
            <li>
                <a  class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'wallet.index' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-wallet"></i>
                    </span>
                    <span>Recharges</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('wallet.index') }}"
                           >Mes recharges</a>
                    </li>
                </ul>
            </li>
            @hasanyrole('admin|dev')
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'categories.index' || Route::currentRouteName() == 'categories.create' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Categories</span>
                </a>
                <ul>
                    <li>
                        <a  href="{{ route('categories.index') }}">List
                            View</a>
                    </li>
                    <li>
                        <a  href="{{ route('categories.create') }}">Ajouter</a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'sous-categories.index' || Route::currentRouteName() == 'sous-categories.create' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Sous Categories</span>
                </a>
                <ul>
                    <li>
                        <a  href="{{ route('sous-categories.index') }}">List
                            View</a>
                    </li>
                    <li>
                        <a  href="{{ route('sous-categories.create') }}">Ajouter</a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'produits.index' || Route::currentRouteName() == 'produits.create' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-truck"></i>
                    </span>
                    <span>Products</span>
                </a>
                <ul>
                    <li>
                        <a  href="{{ route('produits.index') }}">List
                            View</a>
                    </li>
                    <li>
                        <a  href="{{ route('produits.create') }}">Ajouter</a>
                    </li>



                </ul>
            </li>
            <li>
                <a  href="#" class="{{ Route::currentRouteName() == 'admin.commandes.index' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-person-badge"></i>
                    </span>
                    <span>commandes</span>
                </a>
                <ul>
                    <li>
                        <a  href="{{ route('admin.commandes.index') }}">listes</a>
                    </li>


                </ul>
            </li>
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'users.index' ? 'active' : '' }} {{ Route::currentRouteName() == 'admin.nihao-travel.index' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Statistiques</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('users.index') }}"
                           >Utilisateurs</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.nihao-travel.index') }}"
                           >Nihao Travel</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.paypal-balance.index') }}"
                           >Paypal Withdrawal</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'admin.currencies.index' ? 'active' : '' }} {{ Route::currentRouteName() == 'admin.currencies.create' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </span>
                    <span>Devises</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('admin.currencies.index') }}"
                           >Listes</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.currencies.create') }}"
                           >Ajouter</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="{{ Route::currentRouteName() == 'pays.index' ? 'active' : '' }} {{ Route::currentRouteName() == 'pays.create' ? 'active' : '' }}">
                    <span class="nav-link-icon">
                        <i class="bi bi-flag me-2"></i>
                    </span>
                    <span>Pays</span>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('pays.index') }}"
                           >Liste</a>
                    </li>
                    <li>
                        <a href="{{ route('pays.create') }}"
                           >Ajouter</a>
                    </li>
                </ul>
            </li>

            @endhasanyrole

        </ul>
    </div>
</div>
<!-- ./  menu -->
