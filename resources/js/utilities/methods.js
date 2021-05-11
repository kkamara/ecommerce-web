import { TOKEN_NAME, CACHE_HASH_NAME } from "../constants";

export const convertArrayToGETParams = params => {
    let data = [];
    for (let key in params) {
        if (params[key].length > 0) {
            data.push(`${key}=${params[key]}`);
        }
    }
    return data.join("&");
};

export const getAuthToken = () => {
    const token = localStorage.getItem(TOKEN_NAME);

    return token;
};

export const getCacheHashToken = () => {
    const token = localStorage.getItem(CACHE_HASH_NAME);
    console.log("token", token);
    if (!token) return storeNewCacheHashToken();
    return token
};

export const storeNewCacheHashToken = () => {
    const result = Math.floor(new Date().getTime() * Math.random(0, 1000000))
    localStorage.setItem(
        CACHE_HASH_NAME,
        result
    );
    return result;
};
