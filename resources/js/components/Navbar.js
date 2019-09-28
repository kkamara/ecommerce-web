import React, { PureComponent, Fragment } from "react";
import { withRouter, Link } from "react-router-dom";

import { APP_NAME } from "../constants";
import { userActions, cartActions } from "../redux/actions/index";
import { connect } from "react-redux";

import ProductQuickSearch from "./Products/ProductQuickSearch";

class Navbar extends PureComponent {
    state = {
        userRole: "",
        isAuth: false
    };

    componentDidMount() {
        this.handleUserAuth();

        this.props.getCart();
    }

    componentDidUpdate() {
        this.handleUserAuth();
    }

    handleUserAuth = () => {
        const { fetched, user } = this.props.current_user;

        if (typeof user !== "undefined" && fetched === true) {
            this.setUserRole(user);
            this.setIsAuth(
                typeof user === "object" && user !== null ? true : false
            );
        }
    };

    setUserRole = user => {
        const newUserRole = user.role;

        this.setState({ userRole: newUserRole });
    };

    setIsAuth = isAuth => this.setState({ isAuth });

    loadCurrentUser() {
        this.props.getCurrentUser();
    }

    render() {
        const { userRole, isAuth } = this.state;
        const { location } = this.props;
        const { cart } = this.props.cart;

        const { navbarSpacing } = styles;

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
                                    <li className="nav-item">
                                        <a
                                            className="nav-link"
                                            href="#"
                                            // href="{{ route('cartShow') }}"
                                        >
                                            <span>
                                                <i
                                                    className="fa fa-cart-plus"
                                                    aria-hidden="true"
                                                ></i>
                                            </span>
                                            <span>Cart ({cart.length})</span>
                                        </a>
                                    </li>
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
    current_user: state.user.user,
    cart: state.cart
});
const mapDispatchToProps = dispatch => ({
    getCurrentUser: () => dispatch(userActions.getCurrentUser()),
    getCart: () => dispatch(cartActions.getCart())
});
export default connect(
    mapStateToProps,
    mapDispatchToProps
)(withRouter(Navbar));
