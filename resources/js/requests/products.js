import { APP_URL } from "../constants";
import { convertArrayToGETParams } from "../utilities/methods";

export const getProducts = async (pageNumber = null, params = {}) => {
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
            return {
                isLoaded: true,
                fetched: true,
                products: json.products,
                activePage: pageNumber,
                searchParams: params
            };
        })
        .catch(err => {
            return {
                isLoaded: true,
                fetched: false,
                errors: err,
                activePage: pageNumber,
                searchParams: params
            };
        });
    return data;
};

export const getProduct = async id => {
    let url = APP_URL + `/products/${id}`;

    url = encodeURI(url);
    console.log("querying server for " + url);
    const data = await fetch(url)
        .then(res => res.json())
        .then(json => {
            return {
                isLoaded: true,
                fetched: true,
                product: json.product
            };
        })
        .catch(err => {
            return {
                isLoaded: true,
                fetched: false,
                errors: err
            };
        });
    return data;
};
