import { combineReducers } from "redux";
import productReducer from "./product";
import productsReducer from "./products";
import noauthCartReducer from "./noauthCart";
import userReducer from "./user";

const reducers = combineReducers({
    noauthCart: noauthCartReducer,
    products: productsReducer,
    product: productReducer,
    user: userReducer
});
export default reducers;
