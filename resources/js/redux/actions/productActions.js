
import { LoadProducts, } from '../../services/productService'
import * as types from '../types'

export const getProducts = () => {
    return (dispatch) => {
        
        dispatch({
            type: types.LOAD_PROFILE_PENDING,
            payload: true,
        })

        LoadProducts().then((res) => {
             
            dispatch({
                type: types.LOAD_PROFILE_SUCCESS,
                payload: res.json(),
            })
            
        }, error => {
            dispatch({ 
                type : types.LOAD_PRODUCTS_ERROR, 
                payload: error,
            })
        })
    }
}
