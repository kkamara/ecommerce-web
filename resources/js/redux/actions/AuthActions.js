import * as ActionTypes from '../ActionTypes'
import {
    RegisterUserService, 
    LoginUserService, 
    LogOutUserService,
} from '../../services/AuthServices'

export const RegisterAction = (credentials) => {
    return (dispatch) => {
        dispatch({type: ActionTypes.RESTART_AUTH_RESPONSE})
        dispatch({type: ActionTypes.LOADING})

        RegisterUserService(credentials).then((res) => {
             
            if(res.hasOwnProperty('success') && res.success === true) {
                dispatch({type: ActionTypes.SIGNUP_SUCCESS, res})
            } else if (res.hasOwnProperty('success') && res.success === false) {
                dispatch({type: ActionTypes.SIGNUP_ERROR,res})
            }
        }, error => {
            dispatch({type : ActionTypes.CODE_ERROR, error})
        })
    }
}

export const LoginAction = (credentials, history) => {
    return (dispatch) => {
        dispatch({type: ActionTypes.RESTART_AUTH_RESPONSE})
        dispatch({type: ActionTypes.LOADING})
        //console.log(history)
        LoginUserService(credentials).then((res) => {
            if (res.hasOwnProperty('success') && res.success === true) {
                localStorage.setItem('user-token',res.token)
                dispatch({type: ActionTypes.LOGIN_SUCCESS})
                setTimeout(() => {
                    history.push('/user')

                },1500)

            } else if (res.hasOwnProperty('success') && res.success === false) {
                dispatch({type: ActionTypes.LOGIN_ERROR,res})
            }            
        }, error => {
            dispatch({type : ActionTypes.CODE_ERROR, error})
        })
    }
}

export const LogoutAction = () => {
    return (dispatch) => {
        dispatch({type: ActionTypes.RESTART_AUTH_RESPONSE})
    
        LogOutUserService().then((res) => {
            if (res.hasOwnProperty('success') && res.success === true) {
                dispatch({type: ActionTypes.LOGOUT_SUCCESS,res})
            } else if (res.hasOwnProperty('success') && res.success === false) {
                dispatch({type: ActionTypes.LOGOUT_SUCCESS,res})
            }
        }, error => {
            dispatch({type : ActionTypes.CODE_ERROR, error})
        })
    }
}

export const ClearAuthStateAction = () => {
    return (dispatch) => {
        dispatch({type: ActionTypes.RESTART_AUTH_RESPONSE})
    }
}
