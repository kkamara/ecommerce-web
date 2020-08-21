import React from "react";
import { connect } from "react-redux";
import Pagination from "react-js-pagination";

const ProductsPagination = ({ handlePageChange, products }) => {
    if (products.products.products.data.length) {
        return (
            <div>
                <Pagination
                    innerClass="pagination pagination-lg"
                    itemClass="page-item"
                    linkClass="page-link"
                    activePage={products.products.activePage}
                    itemsCountPerPage={products.products.products.per_page}
                    totalItemsCount={products.products.products.total}
                    pageRangeDisplayed={7}
                    onChange={handlePageChange}
                    getPageUrl={pageNumber => `${pageNumber}`}
                />
            </div>
        );
    } else {
        return <div />;
    }
}

const mapStateToProps = state => ({
    products: state.products
});
export default connect(mapStateToProps)(ProductsPagination);
