import { currentUserActions } from "./types";

const initialState = {
    user: {
        user: undefined,
        isLoaded: false,
        fetched: false,
        logout: false,
        errors: {}
    }
};
const currentUserReducer = (state = initialState, action) => {
    switch (action.type) {
        case currentUserActions.GET_CURRENT_USER_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case currentUserActions.GET_CURRENT_USER_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case currentUserActions.GET_CURRENT_USER_SUCCESS:
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                user: action.payload
            };
    }

    return state;
};
export default currentUserReducer;
