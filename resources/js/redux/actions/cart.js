import { 
    getCacheHashToken, 
    removeAuthToken,
    getAuthToken,
} from "../../utilities/methods";
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

        const headers = { "Content-Type": "application/json" };
        const token = getAuthToken();

        if (token) headers['Authorization'] = `Bearer ${token}`;
        else headers['X-CLIENT-HASH-KEY'] = getCacheHashToken();

        let url = APP_URL + `/cart/update`;
        url = encodeURI(url);

        await fetch(url, {
            method: "PUT",
            body: JSON.stringify(data),
            headers,
        })
            .then(res => res.json())
            .then(() => {
                dispatch(success(cartActions.UPDATE_CART_SUCCESS));

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

        function success(type) {
            return {type};
        }
    };
}

function addToCart(ID) {
    return async dispatch => {
        dispatch(request(cartActions.ADD_TO_CART_PENDING));
        
        const headers = { "Content-Type": "application/json" };
        const token = getAuthToken();

        if (token) headers['Authorization'] = `Bearer ${token}`;
        else headers['X-CLIENT-HASH-KEY'] = getCacheHashToken();

        let url = APP_URL + `/products/${ID}/store`;
        url = encodeURI(url);

        await fetch(url, {
            method: "POST",
            headers,
        })
            .then(res => res.json())
            .then(() => dispatch(success(cartActions.ADD_TO_CART_SUCCESS)))
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

        function success(type) {
            dispatch(getCart());
            return { type };
        }
    };
}

function getCart() {
    return async dispatch => {
        dispatch(request(cartActions.GET_CART_PENDING));
        let url = APP_URL + `/cart`;

        const headers = { "Content-Type": "application/json" };
        const token = getAuthToken();

        if (token) headers['Authorization'] = `Bearer ${token}`;
        else headers['X-CLIENT-HASH-KEY'] = getCacheHashToken();

        url = encodeURI(url);

        await fetch(url, {
            method: "POST",
            headers,
        })
            .then(res => res.json())
            .then(json => {
                const { count, cart, cost } = json.data;
                dispatch(
                    success(cartActions.GET_CART_SUCCESS, {
                        data: cart,
                        count,
                        cost,
                    })
                );
            })
            .catch(err => {
                dispatch(error(cartActions.GET_CART_ERROR, err));
                if (getAuthToken()) {
                    removeAuthToken();
                }
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
