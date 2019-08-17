import { APP_URL } from "../constants";

const tokenName = "recipe-toke";

export const loginUser = async (email, password) => {
    let url = APP_URL + "/user/login";
    url = encodeURI(url);

    let body = new FormData();
    body.append("email", email);
    body.append("password", password);

    console.log("querying server for " + url);
    const data = await fetch(url, {
        method: "POST",
        body
    })
        .then(res => res.json())
        .then(json => {
            localStorage.setItem(tokenName, json.token);

            return {
                isLoaded: true,
                fetched: true,
                user: json.user
            };
        })
        .catch(err => {
            return {
                isLoaded: true,
                fetched: false,
                errors: err
            };
        });

    return data;
};

export const logoutUser = () => {
    localStorage.removeItem(tokenName);

    return {
        logout: true
    };
};

export const getCurrentUser = async () => {
    let url = APP_URL + "/user/authenticate";
    const token = localStorage.getItem(tokenName);

    if (null === token) {
        return {
            isLoaded: true,
            fetched: false,
            user: undefined
        };
    } else {
        url = encodeURI(url);
        console.log("querying server for " + url);
        const data = await fetch(url, {
            method: "GET",
            headers: new Headers({
                Authorization: `Bearer ${token}`
            })
        })
            .then(res => res.json())
            .then(json => {
                if ("undefined" == json.user) {
                    return {
                        isLoaded: true,
                        fetched: false,
                        user: undefined
                    };
                }

                return {
                    isLoaded: true,
                    fetched: true,
                    user: json.user
                };
            })
            .catch(err => {
                return {
                    isLoaded: true,
                    fetched: false,
                    errors: err
                };
            });
        return data;
    }
};
