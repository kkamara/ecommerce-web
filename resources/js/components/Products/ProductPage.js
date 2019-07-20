import React, { Component } from "react";
import { Redirect } from "react-router-dom";

import { getProduct } from "../../requests/products";
import { connect } from "react-redux";

import ProductReviewList from "../ProductReviews/ProductReviewList";

class ProductPage extends Component {
    componentDidMount() {
        this.loadProduct();
    }

    loadProduct() {
        const { id } = this.props.match.params;

        this.props
            .dispatch({
                type: "FETCH_PRODUCT",
                payload: getProduct(id)
            })
            .then(({ value: res }) => {
                if (res.fetched == false) return <Redirect to="/" />;
            });
    }

    render() {
        const { isLoaded, fetched } = this.props.product;

        if (!isLoaded) {
            return <div>Loading</div>;
        } else {
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
                                                style={{ maxHeight: "100" }}
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
                                    {/* @if(Auth::check() && product.doesUserOwnProduct())
                        <a href='{ route('companyProductEdit', [product.company.slug, product.id]) }' className='btn btn-warning btn-sm pull-left'>
                            Edit item
                        </a>
                        <a href='{ route('companyProductDelete', [product.company.slug, product.id]) }' className='btn btn-danger btn-sm pull-right'>
                            Delete item
                        </a>
                    @else
                        <a href='{ route('productAdd', product.id) }' className='btn btn-primary btn-sm'>
                            Add to cart
                        </a>
                    @endif */}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-md-12">
                            <ProductReviewList
                                reviews={reviews}
                                score={product.review}
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

export default connect(mapStateToProps)(ProductPage);
