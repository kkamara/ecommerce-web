import React, { Fragment, Component } from "react";
import { connect } from "react-redux";

import { cartActions } from "../../redux/actions/index";

import Loader from "../Loader";

class CartPage extends Component {
    onCartUpdateClick() {
        const { cart } = this.props.cart;
        if (!cart || !cart.items.length) return;
        let formattedItems = {};
        cart.items.forEach(({ product }) => {
            const itemID = `amount-${product.id}`;
            const newQty = parseInt($(`input[name="${itemID}"]`).val());
            if (!newQty) return;
            formattedItems[`amount-${product.id}`] = newQty;
        });
        this.props.updateCart(formattedItems);
    }

    onCheckoutProceedClick(e) {
        const { cart } = this.props.cart;
        if (!cart || !cart.items.length) return;
        // todo...
    }

    _renderCartForm() {
        const { cart } = this.props.cart;
        return (
            <div>
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
                                        <tr key={index} data-id={index}>
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
                                                    defaultValue={amount || 1}
                                                />
                                            </td>
                                        </tr>
                                    ))}
                                    <tr>
                                        <th>Total:</th>
                                        <td>{cart.cost}</td>
                                        <td>{cart.count}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br />
                            <div className="pull-right">
                                <button
                                    className="btn btn-primary"
                                    defaultValue="Update details"
                                    onClick={this.onCartUpdateClick.bind(this)}
                                >
                                    Submit Query
                                </button>
                            </div>
                        </Fragment>
                     ) : (
                        <p>Your cart is empty.</p>
                     )
                }
            </div>
        );
    }

    render() {
        const { cart, isLoaded, fetched } = this.props.cart;
        let result = null;

        if (!isLoaded || !cart) {
            result = <Loader />;
        } else if (!fetched) {
            result = <div>Error</div>;
        } else {
            result = (
                <div className="container">
                    <div className="row">
                        <div className="col-md-8">
                            <h3 className="lead">
                                <strong>Your Cart</strong>
                            </h3>
                            {this._renderCartForm()}
                        </div>
                        <div className="col-md-4">
                            <ul className="list-group">
                                <li className="list-group-item">
                                    <button 
                                        className='btn btn-success mx-auto' 
                                        style={{ display: 'block' }}
                                        onClick={this.onCheckoutProceedClick.bind(this)}
                                        disabled={cart.items && !cart.items.length}
                                    >
                                        Proceed to checkout
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            );
        }

        return result;
    }
}

const mapStateToProps = state => ({
    current_user: state.current_user,
    cart: state.cart
});
const mapDispatchToProps = dispatch => ({
    updateCart: data => dispatch(cartActions.updateCart(data)),
});

export default connect(mapStateToProps, mapDispatchToProps)(CartPage);
