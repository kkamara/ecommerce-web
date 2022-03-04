import React from 'react'
import HttpService from './HttpService'
import { PRODUCT_SEARCH, } from '../utils/apiRoutes'

export const LoadProducts = (page) => {
  return new Promise((resolve, reject) => {
    let url = PRODUCT_SEARCH
    if (page) {
      url = `${PRODUCT_SEARCH}?page=${page}`
    }
    return new HttpService()
      .getData(url)
      .then(resolve)
      .catch(reject)
  })
}
