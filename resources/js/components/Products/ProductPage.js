import React, { Component } from "react";
import { connect } from "react-redux";
import { Redirect } from "react-router-dom";

import { productActions } from "../../redux/actions/index";
import ProductReviewList from "../ProductReviews/ProductReviewList";

import Loader from "../Loader";

class ProductPage extends Component {
    componentDidMount() {
        this.loadProduct();
    }

    loadProduct() {
        const { id } = this.props.match.params;

        this.props.getProduct(id);
    }

    render() {
        const { isLoaded, fetched } = this.props.product;

        if (!isLoaded) {
            return <Loader />;
        } else {
            const { current_user, userAuthenticated } = this.props;
            const { product, reviews } = this.props.product.product;

            return (
                <div className="container" id="app">
                    <div className="row">
                        <div className="col-md-9">
                            <table className="table table-striped">
                                <tbody>
                                    <tr className="text-center">
                                        <th scope="row" colSpan="2">
                                            <img
                                                style={{ maxHeight: 100 }}
                                                src={product.image_path}
                                                className="img-responsive"
                                            />
                                            <h4>{product.name}</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td scope="row">Description</td>
                                        <td>
                                            {product.short_description}
                                            <br />
                                            <br />
                                            {product.long_description}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Product Details</td>
                                        <td>{product.product_details}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div className="col-md-3">
                            <ul className="list-group">
                                <li className="list-group-item">
                                    <h3>{product.formatted_cost}</h3>
                                    <small>
                                        {product.shippable ? (
                                            "This item is shippable"
                                        ) : (
                                            <div>
                                                This item is{" "}
                                                <strong>not</strong> shippable
                                            </div>
                                        )}
                                    </small>
                                    <br />
                                    {product.free_delivery ? (
                                        <small>Free Delivery</small>
                                    ) : (
                                        ""
                                    )}
                                </li>
                                <li className="list-group-item">
                                    {userAuthenticated && doesUserOwnProduct ? (
                                        <div>
                                            {/* href={`/vendor/${current_user.user.company.slug}/products/${product.id}/edit`} */}
                                            <a
                                                href="#"
                                                className="btn btn-warning btn-sm pull-left"
                                            >
                                                Edit item
                                            </a>
                                            {/* href={`/vendor/${current_user.user.company.slug}/products/${product.id}/delete`}  */}
                                            <a
                                                href="#"
                                                className="btn btn-danger btn-sm pull-right"
                                            >
                                                Delete item
                                            </a>
                                        </div>
                                    ) : (
                                        // href={`/products/add`}
                                        <a
                                            href="#"
                                            className="btn btn-primary btn-sm"
                                        >
                                            Add to cart
                                        </a>
                                    )}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-md-12">
                            <ProductReviewList
                                reviews={reviews}
                                score={product.review}
                                current_user={current_user}
                                userAuthenticated={userAuthenticated}
                                product={product}
                            />
                        </div>
                    </div>
                </div>
            );
        }
    }
}

const mapStateToProps = state => ({
    product: state.product.product
});
const mapDispatchToProps = dispatch => ({
    getProduct: id => dispatch(productActions.getProduct(id))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ProductPage);
