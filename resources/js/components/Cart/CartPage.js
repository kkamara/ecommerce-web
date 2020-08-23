import React, { Fragment, Component } from "react";
import { connect } from "react-redux";

import { cartActions } from "../../redux/actions/index";

import Loader from "../Loader";

class CartPage extends Component {
    onCartUpdateClick() {
        const { data: cartData } = this.props.cart;
        if (!cartData || !cartData.data.length) return;
        let formattedItems = {};
        cartData.data.forEach(({ product }) => {
            const itemID = `amount-${product.id}`;
            const newQty = parseInt($(`input[name="${itemID}"]`).val());
            if (!newQty) return;
            formattedItems[`amount-${product.id}`] = newQty;
        });
        this.props.updateCart(formattedItems);
    }

    onCheckoutProceedClick() {
        const { data: cartData } = this.props.cart;
        if (!cartData || !cartData.data.length) return;
        // todo...
    }

    _renderCartForm() {
        const { data: cartData } = this.props.cart;
        
        return (
            <div>
                {cartData.data && cartData.data.length ?
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
                                    {cartData.data.map(({
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
                                        <td>{cartData.cost}</td>
                                        <td>{cartData.count}</td>
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
        const { data: cartData, isLoaded, fetched } = this.props.cart;
        let result = null;

        if (!isLoaded || !cartData) {
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
                                        disabled={cartData.data && !cartData.data.length}
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
