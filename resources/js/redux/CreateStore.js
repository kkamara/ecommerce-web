import {
  createStore, 
  applyMiddleware, 
  compose,
} from 'redux'
import thunk from 'redux-thunk'
import RootReducer from "./reducers/RootReducer"

let store

if (window.__REDUX_DEVTOOLS_EXTENSION__) {
  store = createStore(
      RootReducer,
      compose(
        applyMiddleware(thunk),
        window.__REDUX_DEVTOOLS_EXTENSION__(),
      ),
  )
} else {
  store = createStore(
    RootReducer,
    compose(
      applyMiddleware(thunk),
    ),
  )
}

export {
  store,
}
