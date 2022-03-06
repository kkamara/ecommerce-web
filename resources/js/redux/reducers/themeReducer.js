import React from 'react'
import * as types from '../types'

const initState = {
  data: null,
  error: false,
  loading: true,
}

export default function themeReducer (state = initState, action) {
  switch (action.type) {
    
    case types.LOAD_THEME_PENDING:
    case types.SET_THEME_PENDING:
      return {
        ...state,
        loading: true,
      }

    case types.LOAD_THEME_SUCCESS:
    case types.SET_THEME_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    case types.LOAD_THEME_ERROR:
    case types.SET_THEME_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
