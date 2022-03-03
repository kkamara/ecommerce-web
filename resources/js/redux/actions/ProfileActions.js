
import {LoadProfile} from '../../services/ProfileServices'
import * as ActionTypes from '../ActionTypes'

export const LoadProfileAction = () => {
    return (dispatch) => {
        
        dispatch({type: ActionTypes.LOADING})

        LoadProfile().then((res) => {
             
            if (res.hasOwnProperty('success') && res.success === true) {
                dispatch({type: ActionTypes.LOAD_PROFILE_SUCCESS,res})
            } else if (res.hasOwnProperty('success') && res.success === false) {
                dispatch({type: ActionTypes.LOAD_PROFILE_ERROR,res})
            }
            
        }, error => {
            dispatch({type : ActionTypes.CODE_ERROR, error})
        })
    }
}
