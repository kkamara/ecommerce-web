import { combineReducers } from "redux";

import currentUserReducer from "./currentUser";
import productsReducer from "./products";
import productReducer from "./product";
import cartReducer from "./cart";
import userReducer from "./user";

const reducers = combineReducers({
    current_user: currentUserReducer,
    products: productsReducer,
    product: productReducer,
    cart: cartReducer,
    user: userReducer
});
export default reducers;
