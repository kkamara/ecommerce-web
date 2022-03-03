import HttpService from './HttpService'

export const LoadProfile = () => {
    const http = new HttpService()
    let profileUrl = "users/view-profile"
    const tokenId = "user-token"

    return http.getData(profileUrl, tokenId).then(data => {
        console.log(data)
         return data
    }).catch((error) => {
        console.log(error)
         return error 
    })
}
