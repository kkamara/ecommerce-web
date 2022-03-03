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
import Login from "./components/pages/LoginComponent"
import Register from "./components/pages/RegisterComponent"

import { url } from './utils/config'

function Routes(){
  return (
    <>
      <Header/>
      <Switch>
        <Route exact path={url("/")} render={props => (
            <Redirect to={{ pathname: url('/home') }} />
        )}/>

        <Route path={url("/home")} component={Home}/>
        <Route path={url("/user/login")} component={Login}/>
        <Route path={url("/user/register")} component={Register}/>      

        {/*Redirect if not authenticated */} 
        <Guard 
            path={url("/user" )}
            token="user-token" 
            routeRedirect={url("/user/login" )}
            component={PrivateRoute}
        />
      </Switch>
    </>
  )
}

export default Routes
