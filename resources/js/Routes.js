import React from 'react'
import {
    Switch,
    Route,
    Redirect,
} from 'react-router-dom'

import PrivateRoute from './PrivateRoute'
import { Guard } from './Guard'

import Header from './components/layouts/Header'

import Home from "./components/pages/HomeComponent"

import { url } from './utils/config'

function Routes(){
  return (
    <>
      <Header/>
      <Switch>
        <Route path={url("/")} component={Home}/>  

        {/*Redirect if not authenticated */} 
        {/* <Guard 
            path={url("/user" )}
            token="user-token" 
            routeRedirect={url("/user/login" )}
            component={PrivateRoute}
        /> */}
      </Switch>
    </>
  )
}

export default Routes
