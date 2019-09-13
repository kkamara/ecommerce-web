import { productActions } from "./types";

const initialState = {
    products: {
        products: {},
        isLoaded: false,
        fetched: false
    },
    product: {
        product: {},
        isLoaded: false,
        fetched: false
    }
};
const productReducer = (state = initialState, action) => {
    switch (action.type) {
        case productActions.GET_PRODUCTS_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case productActions.GET_PRODUCTS_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case productActions.GET_PRODUCTS_SUCCESS:
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                products: action.payload
            };
        case productActions.GET_PRODUCT_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case productActions.GET_PRODUCT_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case productActions.GET_PRODUCT_SUCCESS:
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                product: action.payload
            };
    }

    return state;
};
export default productReducer;
