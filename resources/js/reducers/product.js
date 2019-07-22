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
        case "FETCH_PRODUCTS_PENDING":
            return { ...state, fetched: false, isLoaded: false };
            break;
        case "FETCH_PRODUCTS_REJECTED":
            return { ...state, fetched: false, isLoaded: true, error: action.payload };
            break;
        case "FETCH_PRODUCTS_FULFILLED":
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                products: action.payload
            };
            break;
        case "FETCH_PRODUCT_PENDING":
            return { ...state, fetched: false, isLoaded: false };
            break;
        case "FETCH_PRODUCT_REJECTED":
            return { ...state, fetched: false, isLoaded: true, error: action.payload };
            break;
        case "FETCH_PRODUCT_FULFILLED":
            return {
                ...state,
                fetched: true,
                isLoaded: true,
                product: action.payload
            };
            break;
    }

    return state;
};
export default productReducer;
