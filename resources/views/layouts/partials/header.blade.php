<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @if(Auth::guard('customer')->check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.products.index') }}">{{ __('Product List') }}</a>
                </li>
                
                @elseif(Auth::guard('warehouse')->check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('warehouse.warehouses.index') }}">{{ __('Warehouse') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('warehouse.products.index') }}">{{ __('Products') }}</a>
                </li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('customer.login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.login') }}">{{ __('Customer Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('customer.register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.register') }}">{{ __('Customer Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        @if(Auth::guard('customer')->check())
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('customer.logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                        @elseif(Auth::guard('warehouse')->check())
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('warehouse.logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                
                                <form id="logout-form" action="{{ route('warehouse.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @else
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                        @endif
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>