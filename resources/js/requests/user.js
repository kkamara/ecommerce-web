import { APP_URL } from "../constants";

export const getCurrentUser = async () => {
    let url = APP_URL + "/user/authenticate";
    const token = localStorage.getItem("recipe-toke");

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
