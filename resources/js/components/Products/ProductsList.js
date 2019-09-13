import React from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

const initialState = {
    activePage: 1
};
class ProductsList extends React.Component {
    state = { ...initialState };

    render() {
        const { products } = this.props;

        if (products.products.products.data.length) {
            return (
                <div>
                    {products.products.products.data.map(
                        ({
                            id,
                            name,
                            formatted_cost,
                            short_description,
                            review,
                            image_path
                        }) => (
                            <a
                                href={`/products/${id}`}
                                className="list-group-item list-group-item-action flex-column align-items-start"
                                key={id}
                            >
                                <div className="d-flex w-100 justify-content-between">
                                    <img
                                        src={image_path}
                                        style={{ maxHeight: 100 }}
                                        className="img-responsive"
                                    />
                                    <h5 className="mb-1">{name}</h5>
                                    <h3>
                                        <strong>{formatted_cost}</strong>
                                    </h3>
                                </div>
                                <p className="mb-1">{short_description}.</p>
                                <small>{name}.</small>
                                <div className="float-right">
                                    Average Rating: {review}
                                </div>
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

const mapStateToProps = state => ({
    products: state.product
});
export default connect(
    mapStateToProps,
    null
)(withRouter(ProductsList));
