import React from "react";

import { getProducts } from "../requests/products.js";
import ProductsList from "./Products/ProductsList.js";
import ProductsPagination from "./Products/ProductsPagination.js";
import ProductsSearch from "./Products/ProductsSearch.js";

const initialState = {
  products: {},
  productSearch: {},
  isLoaded: false,
  activePage: 1
};

class App extends React.Component {
  state = { ...initialState };

  async componentDidMount() {
    const data = await getProducts();

    this.setProductsState(data);
  }

  async loadProducts() {
    const data = await getProducts(
      this.state.activePage,
      this.state.productSearch
    );

    this.setProductsState(data);
  }

  setProductsState({ isLoaded, products }) {
    this.setState({
      isLoaded: isLoaded,
      products: products
    });
  }

  setActivePageState(pageNumber, callback) {
    this.setState({ activePage: pageNumber }, () => callback());
  }

  setProductSearchState(criteria, callback) {
    this.setState({ productSearch: criteria }, () => {
      console.log("in app component", this.state);
      return callback();
    });
  }

  handlePageChange(pageNumber) {
    this.setActivePageState(pageNumber, this.loadProducts.bind(this));
  }

  handleProductSearch(criteria) {
    this.setProductSearchState(criteria, this.loadProducts.bind(this));
  }

  render() {
    return (
      <div className="container" id="app">
        <div className="card">
          <div className="card-header">
            <ProductsSearch
              handleProductSearch={this.handleProductSearch.bind(this)}
            />
          </div>
          <div className="card-body">
            <div className="card-text">
              <div className="list-group">
                <ProductsList {...this.state} />
              </div>
            </div>
          </div>
          <div className="card-footer">
            <div className="text-center">
              <ProductsPagination
                {...this.state}
                handlePageChange={this.handlePageChange.bind(this)}
              />
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default App;
