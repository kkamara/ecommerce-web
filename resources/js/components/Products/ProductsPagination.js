import React from "react";
import { connect } from "react-redux";
import Pagination from "react-js-pagination";

class ProductsPagination extends React.Component {
    render() {
        const { handlePageChange, activePage, products } = this.props;

        if (products.products.products.data.length) {
            return (
                <div>
                    <Pagination
                        innerClass="pagination pagination-lg"
                        itemClass="page-item"
                        linkClass="page-link"
                        activePage={activePage}
                        itemsCountPerPage={products.products.products.per_page}
                        totalItemsCount={products.products.products.total}
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
    products: state.products
});
export default connect(mapStateToProps)(ProductsPagination);
