import React from "react";

import { getProducts } from "../requests/products.js";
import ProductsList from "./Products/ProductsList.js";
import ProductsPagination from "./Products/ProductsPagination.js";
import ProductsSearch from "./Products/ProductsSearch.js";

import { connect } from "react-redux";

const initialState = {
    products: {
        searchParams: {},
        activePage: 1
    }
};

class App extends React.Component {
    state = { ...initialState };

    async componentDidMount() {
        this.loadProducts();
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
            return <div>Loading</div>;
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
