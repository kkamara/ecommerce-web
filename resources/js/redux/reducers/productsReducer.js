import * as types from '../types'

const initState = {
  data: [],
  error: false,
  loading: false,
}

export default function productsReducer (state = initState, action) {
  switch (action.type) {
    
    case types.LOAD_PRODUCTS_PENDING:
      return {
        ...state,
        loading: action.payload,
      }

    case types.LOAD_PRODUCTS_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    case types.LOAD_PRODUCTS_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
  