import { userActions } from "./types";

const initialState = {};
const userReducer = (state = initialState, action) => {
    switch (action.type) {
        case userActions.POST_LOGIN_USER_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case userActions.POST_LOGIN_USER_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case userActions.POST_LOGIN_USER_SUCCESS:
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                user: action.payload
            };
        case userActions.GET_LOGOUT_USER_PENDING:
            return { ...state };
        case userActions.GET_LOGOUT_USER_ERROR:
            return {
                ...state,
                logout: false
            };
        case userActions.GET_LOGOUT_USER_SUCCESS:
            return {
                ...state,
                logout: true
            };
    }

    return state;
};
export default userReducer;
