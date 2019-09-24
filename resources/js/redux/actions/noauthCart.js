import { APP_URL } from "../../constants";
import { noauthCartActions } from "../reducers/types";

export default {
    addToCart
};

function addToCart(id) {
    return async dispatch => {
        dispatch(request(noauthCartActions.NOAUTH_ADD_TO_CART_PENDING));
        let url = APP_URL + `/products/${id}`;

        url = encodeURI(url);
        console.log("querying server for " + url);
        await fetch(url)
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(
                        noauthCartActions.NOAUTH_ADD_TO_CART_SUCCESS,
                        json.product
                    )
                );
            })
            .catch(err => {
                dispatch(
                    error(noauthCartActions.NOAUTH_ADD_TO_CART_ERROR, err)
                );
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
