import React from "react";
import Pagination from "react-js-pagination";
import { connect } from "react-redux";

class ProductsPagination extends React.Component {
    render() {
        const { handlePageChange } = this.props;
        const { products, isLoaded, fetched, activePage } = this.props.products;

        if (isLoaded && fetched) {
            return (
                <div>
                    <Pagination
                        innerClass="pagination pagination-lg"
                        itemClass="page-item"
                        linkClass="page-link"
                        activePage={activePage}
                        itemsCountPerPage={products.per_page}
                        totalItemsCount={products.total}
                        pageRangeDisplayed={7}
                        onChange={handlePageChange}
                    />
                </div>
            );
        } else {
            return <div />;
        }
    }
}

const mapStateToProps = state => ({
    products: state.product.products
});
export default connect(mapStateToProps)(ProductsPagination);
