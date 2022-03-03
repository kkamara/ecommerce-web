import React from 'react'
import {
  Switch,
  Route,
  Redirect,
} from 'react-router-dom'

import Home from './components/pages/HomeComponent'

export default function PrivateRoute(props) {
  return (
    <div>
      {/*<Header/>*/}
       <Switch>
          <Route exact path={`${props.match.path}/`} component={Home}/>
          <Route exact path={props.match.path} render={props=> (
            <Redirect to={{ pathname: `${props.match.path}/` }} />
          )} />
       </Switch>
    </div>
  )
}
