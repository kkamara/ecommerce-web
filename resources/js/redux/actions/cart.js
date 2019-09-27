import { APP_URL } from "../../constants";
import { cartActions } from "../reducers/types";

export default {
    addToCart,
    getCart
};

function addToCart(id) {
    return async dispatch => {
        dispatch(request(cartActions.ADD_TO_CART_PENDING));
        // implement
        let url = APP_URL + `/products/${id}`;

        url = encodeURI(url);
        console.log("querying server for " + url);
        await fetch(url)
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(cartActions.ADD_TO_CART_SUCCESS, json.product)
                );
            })
            .catch(err => {
                dispatch(error(cartActions.ADD_TO_CART_ERROR, err));
            });

        function request(type) {
            return {
                type
            };
        }

        function error(type, payload) {
            return {
                type,
                payload
            };
        }

        function success(type, payload) {
            return {
                type,
                payload
            };
        }
    };
}

function getCart() {
    return async dispatch => {
        dispatch(request(cartActions.GET_CART_PENDING));
        let url = APP_URL + `/cart`;

        url = encodeURI(url);
        console.log("querying server for " + url);
        await fetch(url)
            .then(res => res.json())
            .then(json => {
                dispatch(success(cartActions.GET_CART_SUCCESS, json.product));
            })
            .catch(err => {
                dispatch(error(cartActions.GET_CART_ERROR, err));
            });

        function request(type) {
            return {
                type
            };
        }

        function error(type, payload) {
            return {
                type,
                payload
            };
        }

        function success(type, payload) {
            return {
                type,
                payload
            };
        }
    };
}
