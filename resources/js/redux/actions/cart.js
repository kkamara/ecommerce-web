import { getCacheHashToken, getAuthToken } from "../../utilities/methods";
import { cartActions } from "../reducers/types";
import { APP_URL } from "../../constants";

export default {
    updateCart,
    addToCart,
    getCart
};

function updateCart(data) {
    return async dispatch => {
        dispatch(request(cartActions.UPDATE_CART_PENDING));

        const headers = new Headers({ "Content-Type": "application/json" });
        const token = getAuthToken();

        if (token) headers.append('Authorization', `Bearer ${token}`);
        else headers.append('X-CLIENT-HASH-KEY', getCacheHashToken());

        let url = APP_URL + `/cart/update`;
        url = encodeURI(url);

        console.log("querying server for " + url);
        await fetch(url, {
            method: "PUT",
            body: JSON.stringify(data),
            headers,
        })
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(cartActions.UPDATE_CART_SUCCESS, json.message)
                );

                // not good, but quick fix to get around 
                // not storing inputs in class state
                window.location.reload(false); 
            })
            .catch(err => {
                dispatch(error(cartActions.UPDATE_CART_ERROR, err));
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

function addToCart(ID) {
    return async dispatch => {
        dispatch(request(cartActions.ADD_TO_CART_PENDING));
        
        const headers = new Headers({ "Content-Type": "application/json" });
        const token = getAuthToken();

        if (token) headers.append('Authorization', `Bearer ${token}`);
        else headers.append('X-CLIENT-HASH-KEY', getCacheHashToken());

        let url = APP_URL + `/products/${ID}/store`;
        url = encodeURI(url);

        console.log("querying server for " + url);
        await fetch(url, {
            method: "POST",
            headers,
        })
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(cartActions.ADD_TO_CART_SUCCESS, json.message)
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
            dispatch(getCart());
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

        const headers = new Headers({ "Content-Type": "application/json" });
        const token = getAuthToken();

        if (token) headers.append('Authorization', `Bearer ${token}`);
        else headers.append('X-CLIENT-HASH-KEY', getCacheHashToken());

        url = encodeURI(url);

        console.log("querying server for " + url);
        await fetch(url, {
            method: "POST",
            headers,
        })
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(cartActions.GET_CART_SUCCESS, {
                        count: json.count,
                        items: json.cart,
                        cost: json.cost
                    })
                );
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
