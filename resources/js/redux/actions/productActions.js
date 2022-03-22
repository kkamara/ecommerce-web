import React from 'react'
import { LoadProducts, } from '../../services/productService'
import * as types from '../types'

export function getProducts(page) {
  try {
    return async dispatch => {
      dispatch({
        type: types.LOAD_PRODUCTS_PENDING,
        payload: true,
      })
           
      await LoadProducts(page).then(res => {
        dispatch(response(types.LOAD_PRODUCTS_SUCCESS, res))
      }, err => {
        dispatch(response(types.LOAD_PRODUCTS_ERROR, err))
      })
    }
  } catch (err) {
    dispatch(response(types.LOAD_PRODUCTS_ERROR, err))
  }
}

const response = (type, payload) => ({ type, payload, })
