const initialState = {
    fetching: false,
    fetched: false,
    products: {},
    error: null
};
const productReducer = (state = initialState, action) => {
    switch (action.type) {
        case "FETCH_PRODUCTS_PENDING":
            return { ...state, fetching: true };
            break;
        case "FETCH_PRODUCTS_REJECTED":
            return { ...state, fetching: false, error: action.payload };
            break;
        case "FETCH_PRODUCTS_FULFILLED":
            return {
                ...state,
                fetching: false,
                fetched: true,
                products: action.payload
            };
            break;
    }

    return state;
};
export default productReducer;
