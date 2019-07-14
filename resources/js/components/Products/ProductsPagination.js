import React from "react";
import Pagination from "react-js-pagination";

class ProductsPagination extends React.Component {
  render() {
    const { products, activePage, isLoaded, handlePageChange } = this.props;

    if (isLoaded && typeof products.data !== "undefined") {
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
export default ProductsPagination;
