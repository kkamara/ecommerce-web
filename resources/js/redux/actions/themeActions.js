
import React from 'react'
import * as types from '../types'

export function getTheme() {
  try {
    return async dispatch => {
      dispatch({
        type: types.LOAD_THEME_PENDING,
        payload: true,
      })
           
      let theme = localStorage.getItem('theme')
      if (null === theme) {
        theme = 'light'
      }
      dispatch(response(types.LOAD_THEME_SUCCESS, theme))
    }
  } catch (err) {
    dispatch(response(types.LOAD_THEME_ERROR, err))
  }
}

export function setTheme(theme) {
  try {
    return dispatch => {
      dispatch({
        type: types.SET_THEME_PENDING,
        payload: true,
      })
           
      localStorage.setItem('theme', theme)
      dispatch(response(types.SET_THEME_SUCCESS, theme))
    }
  } catch (err) {
    dispatch(response(types.SET_THEME_ERROR, err))
  }
}

const response = (type, payload) => ({ type, payload, })
