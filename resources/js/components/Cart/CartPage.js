import React, { Fragment } from "react";
import { connect } from "react-redux";

import Loader from "../Loader";

const CartPage = ({ cart: cartObj }) => {
    const { cart, isLoaded, fetched } = cartObj;
    console.log("cart", "isLoaded", "fetched", cart, isLoaded, fetched);

    const updateCart = e => {};

    const _renderCartForm = () => {
        return (
            <form action="" onSubmit={e => updateCart(e)}>
                {cart.items && cart.items.length ?
                    (
                        <Fragment>
                            <table className="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {cart.items.map(({
                                        product,
                                        amount,
                                    }, index) => (
                                        <tr key={index}>
                                            <td>
                                                <a
                                                    href={`/products/${product.id}`}
                                                >
                                                    {product.name}
                                                </a>
                                            </td>

                                            <td>{product.formatted_cost}</td>

                                            <td>
                                                <input
                                                    className="form-control"
                                                    type="number"
                                                    name={`amount-${product.id}`}
                                                    defaultValue={`${amount}`}
                                                />
                                            </td>
                                        </tr>
                                    ))}
                                    <tr>
                                        <th>Total:</th>
                                        <td>{cart.price}</td>
                                        <td>{cart.count}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br />
                            <div className="pull-right">
                                <input
                                    type="submit"
                                    href=""
                                    className="btn btn-primary"
                                    defaultValue="Update details"
                                />
                            </div>
                        </Fragment>
                     ) : (
                        <p>Your cart is empty.</p>
                     )
                }
            </form>
        );
    };

    if (!isLoaded) {
        return <Loader />;
    } else if (!fetched) {
        return <div>Error</div>;
    }

    return (
        <div className="container">
            <div className="row">
                <div className="col-md-8">
                    <h3 className="lead">
                        <strong>Your Cart</strong>
                    </h3>
                    {_renderCartForm()}
                </div>
                <div className="col-md-4">
                    <ul className="list-group">
                        <li className="list-group-item">
                            <a href='#' className='btn btn-success' style={{ display: 'block' }}>
                                Proceed to checkout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    );
};

const mapStateToProps = state => ({
    current_user: state.current_user,
    cart: state.cart
});
const mapDispatchToProps = dispatch => ({});

export default connect(mapStateToProps, mapDispatchToProps)(CartPage);
