import HttpService from './HttpService'

export const LoadProducts = () => {
    const http = new HttpService()

    return http.getData("products").then(data => {
         console.log(data)
         return data
    }).catch((error) => {
        console.log(error)
         return error 
    })
}
