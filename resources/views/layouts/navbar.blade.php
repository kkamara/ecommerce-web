<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                </li>
                <div class="nav-item">
                    <a class="nav-link" href="{{ url('products/?sort_by=pop') }}">Most Popular</a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="{{ url('products/?sort_by=top') }}">Top Rated</a>
                </div>
            </ul>
            <ul class="navbar-nav mr-auto">
                <form class="form-inline my-2 my-lg-0" action='{{ route('productHome') }}' method='GET'>
                    <input name='query' class="form-control mr-sm-2" type="search" placeholder="Find Your Product" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </ul>
            <ul class="navbar-nav mr-right">
                @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Stuff
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('billingHome') }}">Billing Cards</a>
                            <a class="dropdown-item" href="{{ route('addressHome') }}">Addresses</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('orderHome') }}">Order History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('userEdit', auth()->user()->slug) }}">User Settings</a>
                            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                            <a class="nav-link" href="{{ route('registerHome') }}">
                            <span>
                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                            </span>
                            <span>Register</span>
                        </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                            <span>
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                            </span>
                            <span>Login</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cartShow') }}">
                        <span>
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        </span>
                        <span>Cart ({{ $cartCount }})</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>