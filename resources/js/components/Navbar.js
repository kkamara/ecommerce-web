import React, { PureComponent } from "react";
import { withRouter, Link, Redirect } from "react-router-dom";

import { APP_NAME } from "../constants";

class Navbar extends PureComponent {
    render() {
        return (
            <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                <div className="container">
                    <Link className="navbar-brand" to={{ pathname: `/` }}>
                        {APP_NAME}
                    </Link>
                    <button
                        className="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span className="navbar-toggler-icon" />
                    </button>

                    <div
                        className="collapse navbar-collapse"
                        id="navbarSupportedContent"
                    >
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link
                                    className="nav-link"
                                    to={{ pathname: "/" }}
                                >
                                    Home
                                </Link>
                            </li>
                            <div className="nav-item">
                                <Link
                                    className="nav-link"
                                    to={{
                                        pathname: "/",
                                        state: { sort_by: "pop" }
                                    }}
                                >
                                    Most Popular
                                </Link>
                            </div>
                            <div className="nav-item">
                                <Link
                                    className="nav-link"
                                    to={{
                                        pathname: "/",
                                        state: { sort_by: "top" }
                                    }}
                                >
                                    Top Rated
                                </Link>
                            </div>
                        </ul>
                        <ul className="navbar-nav mr-auto">
                            <form
                                className="form-inline my-2 my-lg-0"
                                action="/"
                                method="GET"
                            >
                                <input
                                    name="query"
                                    className="form-control mr-sm-2"
                                    type="search"
                                    placeholder="Find Your Product"
                                    aria-label="Search"
                                />
                                <button
                                    className="btn btn-outline-success my-2 my-sm-0"
                                    type="submit"
                                >
                                    Search
                                </button>
                            </form>
                        </ul>
                        <ul className="navbar-nav mr-right">
                            {/* @if(Auth::check())
                    <li className="nav-item dropdown">
                        <a className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Stuff
                        </a>
                        <div className="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a className="dropdown-item" href="{{ route('billingHome') }}">Billing Cards</a>
                            <a className="dropdown-item" href="{{ route('addressHome') }}">Addresses</a>
                            <div className="dropdown-divider"></div>
                            <a className="dropdown-item" href="{{ route('orderHome') }}">Order History</a>
                            <div className="dropdown-divider"></div>
                            @role('vendor')
                                <a className="dropdown-item" href="{{ route('companyProductCreate', auth()->user()->company->slug) }}">Add a Product</a>
                                <a className="dropdown-item" href="{{ route('companyProductHome', auth()->user()->company->slug) }}">My Products</a>
                            @else
                                @role('moderator')
                                        <a className="dropdown-item" href="{{ route('modHubHome') }}">Moderator's Hub</a>
                                @else
                                    <a className="dropdown-item" href="{{ route('vendorCreate') }}">Become a vendor</a>
                                @endrole
                            @endrole
                            <div className="dropdown-divider"></div>
                            <a className="dropdown-item" href="{{ route('userEdit', auth()->user()->slug) }}">User Settings</a>
                            <a className="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </div>
                    </li>
                @else
                    <li className="nav-item">
                            <a className="nav-link" href="{{ route('registerHome') }}">
                            <span>
                                <i className="fa fa-user-plus" aria-hidden="true"></i>
                            </span>
                            <span>Register</span>
                        </a>
                    </li>
                    <li className="nav-item">
                            <a className="nav-link" href="{{ route('login') }}">
                            <span>
                                <i className="fa fa-sign-in" aria-hidden="true"></i>
                            </span>
                            <span>Login</span>
                        </a>
                    </li>
                @endif */}
                            {/* <li className="nav-item">
                    <a className="nav-link" href="{{ route('cartShow') }}">
                        <span>
                            <i className="fa fa-cart-plus" aria-hidden="true"></i>
                        </span>
                        <span>Cart ({{ $cartCount }})</span>
                    </a>
                </li> */}
                        </ul>
                    </div>
                </div>
            </nav>
        );
    }
}

export default withRouter(Navbar);
