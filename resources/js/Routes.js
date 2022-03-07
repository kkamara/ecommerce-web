import React from 'react'
import {
    Switch,
    Route,
    Redirect,
} from 'react-router-dom'

import PrivateRoute from './PrivateRoute'
import { Guard } from './Guard'

import Header from './components/layouts/Header'
import Footer from './components/layouts/Footer'

import Home from './components/pages/HomeComponent'
import Product from './components/pages/Product/ProductComponent'

import { url } from './utils/config'

function Routes(){
  return (
    <>
      <Header/>
      <Switch>
        <Route path={url('/products/:productSlug')} component={Product}/>  
        <Route path={url('/')} component={Home}/>  

        {/*Redirect if not authenticated */} 
        {/* <Guard 
            path={url('/user' )}
            token='user-token' 
            routeRedirect={url('/user/login' )}
            component={PrivateRoute}
        /> */}
      </Switch>
      <Footer/>
    </>
  )
}

export default Routes
