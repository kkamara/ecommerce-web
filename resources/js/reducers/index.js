import { combineReducers } from "redux";
import productReducer from "./product";

const reducers = combineReducers({
    product: productReducer
});
export default reducers;
