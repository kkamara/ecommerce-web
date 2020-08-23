import { getCacheHashToken } from "../../utilities/methods";
import { APP_URL, TOKEN_NAME } from "../../constants";
import { userActions } from "../reducers/types";

import { currentUserActions, cartActions } from "./index";

export default { loginUser, logoutUser };

function loginUser(email, password) {
    return async dispatch => {
        dispatch(request(userActions.POST_LOGIN_USER_PENDING));
        const headers = new Headers({ "Content-Type": "application/json" });
        headers.append('X-CLIENT-HASH-KEY', getCacheHashToken());
        let url = APP_URL + "/user/login";
        url = encodeURI(url);

        console.log("querying server for " + url);
        await fetch(url, {
            body: JSON.stringify({ email, password }),
            method: "POST",
            headers,
        })
            .then(res => res.json())
            .then(json => {
                localStorage.setItem(TOKEN_NAME, json.token);

                dispatch(
                    success(userActions.POST_LOGIN_USER_SUCCESS, json.user)
                );
            })
            .catch(err => {
                dispatch(error(userActions.POST_LOGIN_USER_ERROR, err));
            });

        function request(type) {
            return {
                type
            };
        }

        function error(type, payload) {
            return {
                type,
                payload
            };
        }

        function success(type, payload) {
            dispatch(currentUserActions.getCurrentUser());
            dispatch(cartActions.getCart());
            return {
                type,
                payload
            };
        }
    };
}

function logoutUser() {
    return async dispatch => {
        localStorage.removeItem(TOKEN_NAME);

        dispatch(pending(userActions.GET_LOGOUT_USER_PENDING));
        dispatch(success(userActions.GET_LOGOUT_USER_SUCCESS));
        window.location.href = "/";

        function pending(type) {
            return {
                payload: null,
                type,
            };
        }

        function success(type) {
            dispatch(currentUserActions.getCurrentUser());
            dispatch(cartActions.getCart());
            return {
                payload: null,
                type,
            };
        }
    };
}
