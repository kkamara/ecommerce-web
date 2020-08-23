import { getCacheHashToken, getAuthToken } from "../../utilities/methods";
import { productActions } from "../reducers/types";
import { APP_URL } from "../../constants";

export default {
    getProduct
};

function getProduct(id) {
    return async dispatch => {
        dispatch(request(productActions.GET_PRODUCT_PENDING));
        const url = encodeURI(APP_URL + `/products/${id}`);

        const headers = { "Content-Type": "application/json" };
        const token = getAuthToken();

        if (token) headers['Authorization'] = `Bearer ${token}`;
        else headers['X-CLIENT-HASH-KEY'] = getCacheHashToken();

        await fetch(url, { headers, })
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(productActions.GET_PRODUCT_SUCCESS, json.data)
                );
            })
            .catch(err => {
                dispatch(error(productActions.GET_PRODUCT_ERROR, err));
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
