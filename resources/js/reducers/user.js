const initialState = {
    user: {
        user: undefined,
        isLoaded: false,
        fetched: false
    }
};
const userReducer = (state = initialState, action) => {
    switch (action.type) {
        case "FETCH_CURRENT_USER_PENDING":
            return { ...state, fetched: false, isLoaded: false };
            break;
        case "FETCH_CURRENT_USER_REJECTED":
            return {
                ...state,
                fetched: false,
                isLoaded: true,
                error: action.payload
            };
            break;
        case "FETCH_CURRENT_USER_FULFILLED":
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                user: action.payload
            };
            break;
    }

    return state;
};
export default userReducer;
