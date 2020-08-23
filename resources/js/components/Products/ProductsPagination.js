import React from "react";
import { connect } from "react-redux";
import Pagination from "react-js-pagination";

const ProductsPagination = ({ handlePageChange, products: stateProducts }) => {
    const { data: productsData } = stateProducts;
    
    if (productsData.data && productsData.data.length) {
        return (
            <div>
                <Pagination
                    innerClass="pagination pagination-lg"
                    itemClass="page-item"
                    linkClass="page-link"
                    activePage={productsData.activePage}
                    itemsCountPerPage={productsData.per_page}
                    totalItemsCount={productsData.total}
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
