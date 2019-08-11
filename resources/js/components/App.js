import React from "react";
import { connect } from "react-redux";

import { getProducts } from "../requests/products.js";
import ProductsList from "./Products/ProductsList.js";
import ProductsPagination from "./Products/ProductsPagination.js";
import ProductsSearch from "./Products/ProductsSearch.js";

import Loader from "./Loader";

const initialState = {
    products: {
        searchParams: {
            sort_by: "",
            min_price: "",
            max_price: "",
            query: ""
        },
        activePage: 1
    }
};

class App extends React.Component {
    state = { ...initialState };

    componentDidMount() {
        let sort_by,
            min_price,
            max_price,
            query = null;

        if (
            typeof this.props.location.state !== "undefined" &&
            typeof this.props.location.state.sort_by != "undefined"
        ) {
            sort_by = this.props.location.state.sort_by;
        }
        if (
            typeof this.props.location.state !== "undefined" &&
            typeof this.props.location.state.min_price != "undefined"
        ) {
            min_price = this.props.location.state.min_price;
        }
        if (
            typeof this.props.location.state !== "undefined" &&
            typeof this.props.location.state.max_price != "undefined"
        ) {
            max_price = this.props.location.state.max_price;
        }
        if (
            typeof this.props.location.state !== "undefined" &&
            typeof this.props.location.state.query != "undefined"
        ) {
            query = this.props.location.state.query;
        }

        if (sort_by || min_price || max_price || query) {
            this.handleIncomingVals({ sort_by, min_price, max_price, query });
        } else {
            this.loadProducts();
        }
    }

    loadProducts() {
        this.props.dispatch({
            type: "FETCH_PRODUCTS",
            payload: getProducts(
                this.state.products.activePage,
                this.state.products.searchParams
            )
        });
    }

    handleIncomingVals({ sort_by, min_price, max_price, query }) {
        this.setState(
            prevState => {
                const { products } = prevState;
                let newProducts = Object.assign({}, products);

                if (sort_by) newProducts.searchParams.sort_by = sort_by;
                if (min_price) newProducts.searchParams.min_price = min_price;
                if (max_price) newProducts.searchParams.max_price = max_price;
                if (query) newProducts.searchParams.query = query;

                return { products: newProducts };
            },
            () => this.loadProducts()
        );
    }

    setActivePageState(pageNumber, callback) {
        this.setState(
            prevState => {
                let products = Object.assign({}, prevState.products);
                products.activePage = pageNumber;
                return { products };
            },
            () => callback()
        );
    }

    setProductSearchState(criteria, callback) {
        this.setState(
            prevState => {
                let products = Object.assign({}, prevState.products);
                products.searchParams = criteria;
                return { products };
            },
            () => callback()
        );
    }

    handlePageChange(pageNumber) {
        this.setActivePageState(pageNumber, this.loadProducts.bind(this));
    }

    handleProductSearch(criteria) {
        this.setProductSearchState(criteria, this.loadProducts.bind(this));
    }

    render() {
        console.log("current user", this.props.current_user);

        const { isLoaded, fetched } = this.props.products;
        if (!isLoaded) {
            return <Loader />;
        } else if (isLoaded && !fetched) {
            return <div>Error</div>;
        } else if (isLoaded && fetched) {
            return (
                <div className="container" id="app">
                    <div className="card">
                        <div className="card-header">
                            <ProductsSearch
                                handleProductSearch={this.handleProductSearch.bind(
                                    this
                                )}
                                searchParams={this.state.products.searchParams}
                            />
                        </div>
                        <div className="card-body">
                            <div className="card-text">
                                <div className="list-group">
                                    <ProductsList
                                        products={this.props.products}
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="card-footer">
                            <div className="text-center">
                                <ProductsPagination
                                    products={this.props.products}
                                    handlePageChange={this.handlePageChange.bind(
                                        this
                                    )}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    }
}

const mapStateToProps = state => ({
    products: state.product.products
});

export default connect(mapStateToProps)(App);
