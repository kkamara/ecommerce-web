import { cartActions } from "./types";

const initialState = {
    isLoaded: false,
    fetched: false,
};
const cartReducer = (state = initialState, action) => {
    switch (action.type) {
        case cartActions.ADD_TO_CART_PENDING:
        case cartActions.UPDATE_CART_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case cartActions.ADD_TO_CART_ERROR:
        case cartActions.UPDATE_CART_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case cartActions.ADD_TO_CART_SUCCESS:
        case cartActions.UPDATE_CART_SUCCESS:
            return {
                ...state,
                fetched: true,
                isLoaded: true
            };
        case cartActions.GET_CART_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case cartActions.GET_CART_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case cartActions.GET_CART_SUCCESS:
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                data: action.payload
            };
    }

    return state;
};
export default cartReducer;
