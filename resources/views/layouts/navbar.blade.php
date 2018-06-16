<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Find Your Product" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </ul>
            <ul class="navbar-nav mr-right">
                @if(Auth::check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
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
                            <a class="nav-link" href="{{ route('loginHome') }}">
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