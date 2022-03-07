import React from 'react'

export default class HttpService
{
    url = 'https://cartcommerce.herokuapp.com/api'
    // url = 'http://localhost:80/api'

    postData = async (item, added_url, tokenId) => {
        let requestOptions = this.postRequestOptions({ item, })
        if (tokenId) {
            const token = await localStorage.getItem(tokenId)
            requestOptions = this.postRequestOptions({ token, item, })
        }

        return fetch(this.url+'/'+added_url,requestOptions).then(
            response=>response.json()
        )
    }

    getData = async (added_url, tokenId) => {
        let requestOptions = this.getRequestOptions()
        if (tokenId) {
            const token = await localStorage.getItem(tokenId)
            requestOptions = this.getRequestOptions(token)
        }

        return fetch(this.url+'/'+added_url,requestOptions).then(
            response=>response.json()
        )
    }

    getRequestOptions = (token) => {
        const requestOptions = {
            method: 'GET',
            headers: { 'Content-type' : 'application/json', }
        }
        if (token) {
            requestOptions.headers.Authorization = 'Bearer ' +token
        }
        return requestOptions
    }

    postRequestOptions = ({ token, item, }) => {
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-type' : 'application/json', },
            body : JSON.stringify(item)
        }
        if (token) {
            requestOptions.headers.Authorization = 'Bearer ' +token
        }
        return requestOptions
    }
}
