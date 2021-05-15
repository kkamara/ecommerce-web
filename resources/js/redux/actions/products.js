import { APP_URL } from "../../constants";
import { productsActions } from "../reducers/types";
import { convertArrayToGETParams } from "../../utilities/methods";

export default {
    getProducts
};

function getProducts(pageNumber = null, params = {}) {
    return async dispatch => {
        dispatch(request(productsActions.GET_PRODUCTS_PENDING));
        let url = APP_URL + "/products";

        let GETVars = convertArrayToGETParams(params);

        if (pageNumber) {
            url += `?page=${pageNumber}`;

            if (GETVars.length > 0) {
                url += `&${GETVars}`;
            }
        } else {
            if (GETVars.length > 0) {
                url += `?${GETVars}`;
            }
        }
        url = encodeURI(url);
        console.log("querying server for " + url);
        const data = await fetch(url)
            .then(res => res.json())
            .then(json => {
                dispatch(
                    success(productsActions.GET_PRODUCTS_SUCCESS, {
                        products: json.products,
                        activePage: pageNumber,
                        searchParams: params
                    })
                );
            })
            .catch(err => {
                dispatch(error(productsActions.GET_PRODUCTS_ERROR, err));
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
