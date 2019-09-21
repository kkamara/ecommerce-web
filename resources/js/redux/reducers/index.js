import { combineReducers } from "redux";
import productReducer from "./product";
import productsReducer from "./products";
import userReducer from "./user";

const reducers = combineReducers({
    products: productsReducer,
    product: productReducer,
    user: userReducer
});
export default reducers;
