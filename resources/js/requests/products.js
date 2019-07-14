import { APP_URL } from "../constants";

function convertArrayToGETParams(params) {
  let data = [];
  for (let key in params) {
    if (params[key].length > 0) {
      data.push(`${key}=${params[key]}`);
    }
  }
  return data.join("&");
}

export const getProducts = async (pageNumber = null, params = {}) => {
  var url = APP_URL + "/products";

  let GETVars = convertArrayToGETParams(params);

  if (pageNumber) {
    url += `?page=${pageNumber}`;

    if (GETVars.length > 0) {
      url += `&${GETVars}`;
    }
  } else {
    if (GETVars.length > 0) {
      url += `?${GETVars}`;
    }
  }
  url = encodeURI(url);
  console.log("querying server for " + url);
  const data = await fetch(url)
    .then(res => res.json())
    .then(json => {
      return {
        isLoaded: true,
        products: json.products
      };
    })
    .catch(json => {
      return {
        isLoaded: true,
        products: {}
      };
    });
  return data;
};
