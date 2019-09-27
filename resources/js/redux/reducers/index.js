import { combineReducers } from "redux";
import productReducer from "./product";
import productsReducer from "./products";
import cartReducer from "./cart";
import userReducer from "./user";

const reducers = combineReducers({
    products: productsReducer,
    product: productReducer,
    cart: cartReducer,
    user: userReducer
});
export default reducers;
