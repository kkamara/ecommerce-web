import React from 'react'
import { combineReducers, } from 'redux'
import productsReducer from './productsReducer'
import themeReducer from './themeReducer'

export default combineReducers({
   products: productsReducer,
   theme: themeReducer,
})
