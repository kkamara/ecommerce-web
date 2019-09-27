import { cartActions } from "./types";

const initialState = {
    cart: {
        cart: []
    }
};
const cartReducer = (state = initialState, action) => {
    switch (action.type) {
        case cartActions.ADD_TO_CART_PENDING:
            return { ...state, successful: false, isLoaded: false };
        case cartActions.ADD_TO_CART_ERROR:
            return {
                ...state,
                successful: false,
                isLoaded: true,
                error: action.payload
            };
        case cartActions.ADD_TO_CART_SUCCESS:
            return {
                ...state,
                successful: true,
                isLoaded: true
            };
    }

    return state;
};
export default cartReducer;
