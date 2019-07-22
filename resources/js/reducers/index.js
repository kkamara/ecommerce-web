import { combineReducers } from "redux";
import productReducer from "./product";
import userReducer from "./user";

const reducers = combineReducers({
    product: productReducer,
    user: userReducer
});
export default reducers;
