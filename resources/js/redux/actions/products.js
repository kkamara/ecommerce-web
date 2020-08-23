import { APP_URL } from "../../constants";
import { productsActions } from "../reducers/types";
import { 
    convertArrayToGETParams, 
    getCacheHashToken,
    getAuthToken,
} from "../../utilities/methods";

export default {
    getProducts
};

function getProducts(pageNumber = null, params = {}) {
    return async dispatch => {
        dispatch(request(productsActions.GET_PRODUCTS_PENDING));
        let url = APP_URL + "/products";

        const headers = { "Content-Type": "application/json" };
        const token = getAuthToken();

        if (token) headers['Authorization'] = `Bearer ${token}`;
        else headers['X-CLIENT-HASH-KEY'] = getCacheHashToken();

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

        await fetch(url, { headers, })
            .then(res => res.json())
            .then(json => {
                const { 
                    current_page,
                    per_page,
                    total,
                    data,
                } = json.data;
                dispatch(
                    success(productsActions.GET_PRODUCTS_SUCCESS, {
                        activePage: current_page,
                        searchParams: params,
                        per_page,
                        total,
                        data,
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
