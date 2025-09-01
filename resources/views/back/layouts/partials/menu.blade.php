<!-- menu -->
<div class="menu">
    <div class="menu-header">
        <a href="index.html" class="menu-header-logo">
            <img src="{{ asset(FrontHelper::getEnvFolder() .'storage/front/assets/images/logo.png') }}" alt="logo">
        </a>
        <a href="index.html" class="btn btn-sm menu-close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>
    <div class="menu-body">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="avatar me-3">
                    <img src="{{ Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset(auth()->user()->avatar) }}"
                         class="rounded-circle" alt="image">
                </div>
                <div>
                    <div class="fw-bold">{{ auth()->user()->name?? auth()->user()->email }}</div>
                    <small class="text-muted">{{ auth()->user()->roles->first()->role }}</small>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('profile.edit') }}" class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-person dropdown-item-icon"></i> Profil
                </a>
                <a href="#" class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-envelope dropdown-item-icon"></i> Inbox
                </a>
                <a href="#" class="dropdown-item d-flex align-items-center" data-sidebar-target="#settings">
                    <i class="bi bi-gear dropdown-item-icon"></i> Settings
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
                <a  class="active"
                    href="index.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Categories</span>
                </a>
                <ul>
                    <li>
                        <a  href="product-list.html">List
                            View</a>
                    </li>
                    <li>
                        <a  href="{{ route('categories.create') }}">Ajouter</a>
                    </li>
                    <li>
                        <a  href="order-detail.html">Modifier</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Sous Categories</span>
                </a>
                <ul>
                    <li>
                        <a  href="product-list.html">List
                            View</a>
                    </li>
                    <li>
                        <a  href="{{ route('sous-categories.create') }}">Ajouter</a>
                    </li>
                    <li>
                        <a  href="order-detail.html">Modifier</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-truck"></i>
                    </span>
                    <span>Products</span>
                </a>
                <ul>
                    <li>
                        <a  href="product-list.html">List
                            View</a>
                    </li>
                    <li>
                        <a  href="{{ route('produits.create') }}">Ajouter</a>
                    </li>
                    <li>
                        <a  href="product-detail.html">Modifier</a>
                    </li>
                    <li>
                        <a  href="shopping-cart.html">Desactiver</a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-wallet2"></i>
                    </span>
                    <span>Recharges</span>
                </a>
                <ul>
                    <li>
                        <a  href="{{ route('recharges.create') }}">Recharge</a>
                    </li>
                    <li>
                        <a  href="buyer-orders.html">Historiques</a>
                    </li>

                </ul>
            </li>
            <li>
                <a  href="customers.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-person-badge"></i>
                    </span>
                    <span>commandes</span>
                </a>
                <ul>
                    <li>
                        <a  href="buyer-dashboard.html">listes</a>
                    </li>


                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Statistiques</span>
                </a>
                <ul>
                    <li>
                        <a href="invoices.html"
                           >Utilisateurs</a>
                    </li>
                    <li>
                        <a href="invoice-detail.html"
                           >Detail</a>
                    </li>
                </ul>
            </li>
            <li class="menu-divider">Apps</li>
            <li>
                <a  href="chats.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-chat-square"></i>
                    </span>
                    <span>Chats</span>
                    <span class="badge bg-success rounded-circle ms-auto">2</span>
                </a>
            </li>

            <li>
                <a href="#" class="disabled">
                    <span class="nav-link-icon">
                        <i class="bi bi-hand-index-thumb"></i>
                    </span>
                    <span>Disabled</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- ./  menu -->
