import { APP_URL, TOKEN_NAME } from "../../constants";
import { userActions } from "../reducers/types";
import { getAuthToken } from "../../utilities/methods";

export default { loginUser, logoutUser, getCurrentUser };

function loginUser(email, password) {
    return async dispatch => {
        dispatch(request(userActions.GET_CURRENT_USER_PENDING));
        let url = APP_URL + "/user/login";
        url = encodeURI(url);

        let body = new FormData();
        body.append("email", email);
        body.append("password", password);

        console.log("querying server for " + url);
        await fetch(url, {
            method: "POST",
            body
        })
            .then(res => res.json())
            .then(json => {
                localStorage.setItem(TOKEN_NAME, json.token);

                dispatch(
                    success(userActions.GET_CURRENT_USER_SUCCESS, json.user)
                );
            })
            .catch(err => {
                dispatch(error(userActions.GET_CURRENT_USER_ERROR, err));
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

        dispatch(pending(userActions.GET_CURRENT_USER_PENDING));
        dispatch(success(userActions.GET_CURRENT_USER_SUCCESS));

        function pending(type) {
            return {
                type,
                payload
            };
        }

        function success(type) {
            return {
                type,
                payload
            };
        }
    };
}

function getCurrentUser() {
    return async dispatch => {
        dispatch(request(userActions.GET_CURRENT_USER_PENDING));

        let url = APP_URL + "/user/authenticate";
        const token = getAuthToken();

        if (null === token) {
            dispatch(
                error(userActions.GET_CURRENT_USER_ERROR, "No token stored")
            );
        } else {
            url = encodeURI(url);
            console.log("querying server for " + url);
            await fetch(url, {
                method: "GET",
                headers: new Headers({
                    Authorization: `Bearer ${token}`
                })
            })
                .then(res => res.json())
                .then(json => {
                    if ("undefined" == json.user) {
                        dispatch(
                            error(
                                userActions.GET_CURRENT_USER_ERROR,
                                json.error
                            )
                        );
                    } else {
                        dispatch(
                            success(
                                userActions.GET_CURRENT_USER_SUCCESS,
                                json.user
                            )
                        );
                    }
                })
                .catch(err => {
                    dispatch(error(userActions.GET_CURRENT_USER_ERROR, err));
                });
        }

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
            return {
                type,
                payload
            };
        }
    };
}
