import React, { PureComponent, Fragment } from "react";
import { withRouter, Link, Redirect } from "react-router-dom";

import { getCurrentUser } from "../requests/user";
import { connect } from "react-redux";

import { APP_NAME } from "../constants";
import ProductQuickSearch from "./Products/ProductQuickSearch";

class Navbar extends PureComponent {
    state = {
        userRole: ""
    };

    componentDidMount() {
        this.loadCurrentUser();
    }

    componentDidUpdate() {
        const { user } = this.props;

        if (typeof user !== "undefined" && user.fetched === true) {
            this.setUserRole();
        }
    }

    setUserRole() {
        const newUserRole = user.role;

        this.setState({ userRole: newUserRole });
    }

    loadCurrentUser() {
        this.props.dispatch({
            type: "FETCH_CURRENT_USER",
            payload: getCurrentUser()
        });
    }

    render() {
        const { userRole } = this.state;
        const { location } = this.props;
        const { fetched, isLoaded, user } = this.props.current_user;

        const { navbarSpacing } = styles;

        const isAuth = typeof user === "undefined" ? false : true;

        if (location.pathname === "/404") {
            return <div />;
        } else {
            return (
                <Fragment>
                    <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                        <div className="container">
                            <Link
                                className="navbar-brand"
                                to={{ pathname: `/` }}
                            >
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
                                    <ProductQuickSearch {...this.props} />
                                </ul>
                                <ul className="navbar-nav mr-right">
                                    {isAuth ? (
                                        <li className="nav-item dropdown">
                                            <a
                                                className="nav-link dropdown-toggle"
                                                href="#"
                                                id="navbarDropdown"
                                                role="button"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                My Stuff
                                            </a>
                                            <div
                                                className="dropdown-menu"
                                                aria-labelledby="navbarDropdown"
                                            >
                                                <a
                                                    className="dropdown-item"
                                                    href="/billing"
                                                >
                                                    Billing Cards
                                                </a>
                                                <a
                                                    className="dropdown-item"
                                                    href="/addresses"
                                                >
                                                    Addresses
                                                </a>
                                                <div className="dropdown-divider" />
                                                <a
                                                    className="dropdown-item"
                                                    href="/orders"
                                                >
                                                    Order History
                                                </a>
                                                <div className="dropdown-divider" />
                                                {userRole === "vendor" ? (
                                                    <Fragment>
                                                        <a
                                                            className="dropdown-item"
                                                            href="/company/create"
                                                        >
                                                            Add a Product
                                                        </a>
                                                        <a
                                                            className="dropdown-item"
                                                            href="/company/products"
                                                        >
                                                            My Products
                                                        </a>
                                                    </Fragment>
                                                ) : userRole === "moderator" ? (
                                                    <Fragment>
                                                        <a
                                                            className="dropdown-item"
                                                            href="moderators/hub"
                                                        >
                                                            Moderator's Hub
                                                        </a>
                                                        :
                                                        <a
                                                            className="dropdown-item"
                                                            href="/vendors/create"
                                                        >
                                                            Become a vendor
                                                        </a>
                                                    </Fragment>
                                                ) : (
                                                    <div />
                                                )}
                                                <div className="dropdown-divider" />
                                                <a
                                                    className="dropdown-item"
                                                    href="/user/edit"
                                                >
                                                    User Settings
                                                </a>
                                                <a
                                                    className="dropdown-item"
                                                    href="/logout"
                                                >
                                                    Logout
                                                </a>
                                            </div>
                                        </li>
                                    ) : (
                                        <Fragment>
                                            <li className="nav-item">
                                                <a
                                                    className="nav-link"
                                                    href="/register"
                                                >
                                                    <span>
                                                        <i
                                                            className="fa fa-user-plus"
                                                            aria-hidden="true"
                                                        />
                                                    </span>
                                                    <span>Register</span>
                                                </a>
                                            </li>
                                            <li className="nav-item">
                                                <a
                                                    className="nav-link"
                                                    href="/login"
                                                >
                                                    <span>
                                                        <i
                                                            className="fa fa-sign-in"
                                                            aria-hidden="true"
                                                        />
                                                    </span>
                                                    <span>Login</span>
                                                </a>
                                            </li>
                                        </Fragment>
                                    )}
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
                    <div style={navbarSpacing} />
                </Fragment>
            );
        }
    }
}

const styles = {
    navbarSpacing: {
        padding: 30
    }
};

const mapStateToProps = state => ({
    current_user: state.user.user
});
export default connect(mapStateToProps)(withRouter(Navbar));
