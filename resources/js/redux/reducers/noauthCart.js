import { noauthCartActions } from "./types";

const initialState = {};
const noauthCartReducer = (state = initialState, action) => {
    switch (action.type) {
        case noauthCartActions.NOAUTH_ADD_TO_CART_PENDING:
            return { ...state, successful: false, isLoaded: false };
        case noauthCartActions.NOAUTH_ADD_TO_CART_ERROR:
            return {
                ...state,
                successful: false,
                isLoaded: true,
                error: action.payload
            };
        case noauthCartActions.NOAUTH_ADD_TO_CART_SUCCESS:
            return {
                ...state,
                successful: true,
                isLoaded: true,
                product: action.payload
            };
    }

    return state;
};
export default noauthCartReducer;
