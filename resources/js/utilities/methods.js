export function convertArrayToGETParams(params) {
    let data = [];
    for (let key in params) {
        if (params[key].length > 0) {
            data.push(`${key}=${params[key]}`);
        }
    }
    return data.join("&");
}
