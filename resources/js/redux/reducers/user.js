import { userActions } from "./types";

const initialState = {
    isLoaded: false,
    fetched: false,
};
const userReducer = (state = initialState, action) => {
    switch (action.type) {
        case userActions.POST_LOGIN_USER_PENDING:
        case userActions.GET_LOGOUT_USER_PENDING:
            return { ...state, fetched: false, isLoaded: false };
        case userActions.POST_LOGIN_USER_ERROR:
        case userActions.GET_LOGOUT_USER_ERROR:
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
        case userActions.POST_LOGIN_USER_SUCCESS:
        case userActions.GET_LOGOUT_USER_SUCCESS:
            if (state.error) delete state.error;
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                user: action.payload
            };
    }

    return state;
};
export default userReducer;
