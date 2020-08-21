import React from "react";
import { connect } from "react-redux";
import querySearch from "query-string";

import { productsActions } from "../redux/actions/index";
import ProductsListPage from "./Products/ProductsListPage.js";
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
        if (0 < this.props.location.search.length) {
            query = querySearch.parse(this.props.location.search).query;
        }

        if (sort_by || query) {
            this.handleIncomingVals({ sort_by, query });
        } else {
            this.loadProducts();
        }
    }

    loadProducts() {
        this.props.getProducts(
            this.state.products.activePage,
            this.state.products.searchParams
        );
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
        const { isLoaded, fetched, products } = this.props.products;

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
                                    <ProductsListPage />
                                </div>
                            </div>
                        </div>
                        <div className="card-footer">
                            <div className="text-center">
                                <ProductsPagination
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
    products: state.products
});
const mapDispatchToProps = dispatch => ({
    getProducts: (activePage, searchParams) =>
        dispatch(productsActions.getProducts(activePage, searchParams))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(App);
