import React from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { Helmet } from 'react-helmet'

import App from './App'
import store from './redux/store'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

import './index.css'
import favicon from './favicon.png'

ReactDOM.render(
  <React.StrictMode>
    <Helmet>
      <link 
        rel="icon" 
        type="image/png"
        href={favicon}
      />
    </Helmet>
    <Provider store={store}>
      <App />
    </Provider>
  </React.StrictMode>,
  document.getElementById('app')
)
