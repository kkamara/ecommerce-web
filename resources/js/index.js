import React from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { Helmet } from 'react-helmet'

import App from './App'
import store from './redux/store'
import reportWebVitals from './reportWebVitals'

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

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals()
