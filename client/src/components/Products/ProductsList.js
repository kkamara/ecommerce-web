import React from "react";

const initialState = {
  activePage: 1
};
class ProductsList extends React.Component {
  state = { ...initialState };

  render() {
    const { products, isLoaded } = this.props;
    if (isLoaded && typeof products.data !== "undefined") {
      return (
        <div>
          {products.data.map(
            ({ id, name, formatted_cost, short_description, review }) => (
              <a
                href="#"
                className="list-group-item list-group-item-action flex-column align-items-start"
                key={id}
              >
                <div className="d-flex w-100 justify-content-between">
                  <img
                    style={{ maxHeight: "100px" }}
                    className="img-responsive"
                  />
                  <h5 className="mb-1">{name}</h5>
                  <h3>
                    <strong>{formatted_cost}</strong>
                  </h3>
                </div>
                <p className="mb-1">{short_description}.</p>
                <small>{name}.</small>
                <div className="float-right">Average Rating: {review}</div>
              </a>
            )
          )}
        </div>
      );
    } else {
      return <p>There are no products currently available.</p>;
    }
  }
}

export default ProductsList;
