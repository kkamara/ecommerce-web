import { APP_URL } from "../../constants";
import { productActions } from "../reducers/types";

export default {
    getProduct
};

function getProduct(id) {
    return async dispatch => {
        dispatch(request(productActions.GET_PRODUCT_PENDING));
        const url = encodeURI(APP_URL + `/products/${id}`);

        await fetch(url)
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(productActions.GET_PRODUCT_SUCCESS, json.product)
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
