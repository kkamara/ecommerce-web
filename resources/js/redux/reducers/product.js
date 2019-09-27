import { productActions } from "./types";

const initialState = {
    product: {
        product: {}
    }
};
const productReducer = (state = initialState, action) => {
    switch (action.type) {
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
