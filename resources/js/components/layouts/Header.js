import React, { useEffect } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useHistory } from 'react-router-dom' 
import { makeStyles, } from '@material-ui/core'
import { APP_NAME, } from '../../utils/config'
import { HOME, } from '../../utils/pageRoutes'

export default function Header(props) {
  const history = useHistory()
  
//   const dispatch = useDispatch()
//   const authResponse = useSelector(state => state.auth)

  const logOut = () => {
    // dispatch(LogoutAction())
    history.push("/login")
  }

  const login = () => {
    history.push("/login")
  }

  const token = localStorage.getItem('user-token')
  //console.log(token)

  // useEffect(() => {
  //   if(authResponse !== "" && authResponse.success === true){
  //       alert(authResponse.message)
  //       localStorage.removeItem('user-token')
  //       //history.push("/user/login")    
  //   } 
  //   return () => {}
  // }, [authResponse])

  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
      <div className="container">
          <a className="navbar-brand" href={HOME}>{APP_NAME}</a>
          <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span className="navbar-toggler-icon"></span>
          </button>

          <div className="collapse navbar-collapse" id="navbarSupportedContent">
              <ul className="navbar-nav mr-auto">
                  <li className="nav-item">
                      <a className="nav-link" href={HOME}>Home <span className="sr-only">(current)</span></a>
                  </li>
                  <div className="nav-item">
                      <a 
                          className="nav-link" 
                          href="products/?sort_by=pop"
                          dusk="most-popular"
                      >
                          Most Popular
                      </a>
                  </div>
                  <div className="nav-item">
                      <a 
                          className="nav-link" 
                          href="products/?sort_by=top"
                          dusk="top-rated"
                      >
                          Top Rated
                      </a>
                  </div>
              </ul>
              <ul className="navbar-nav mr-auto">
                  <form className="form-inline my-2 my-lg-0">
                      <input 
                          name='query' 
                          className="form-control mr-sm-2" 
                          type="search" 
                          placeholder="Find Your Product" 
                          aria-label="Search"
                          dusk="search-products-in"
                      />
                      <button 
                          className="btn btn-outline-success my-2 my-sm-0" 
                          type="submit"
                          dusk="search-products-btn"
                      >
                          Search
                      </button>
                  </form>
              </ul>
              <ul className="navbar-nav mr-right">
                  {/* when authenticated */}
                      <li className="nav-item dropdown">
                          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              My Stuff
                          </a>
                          <div className="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a className="dropdown-item" href="billingHome">Billing Cards</a>
                              <a className="dropdown-item" href="addressHome">Addresses</a>
                              <div className="dropdown-divider"></div>
                              <a className="dropdown-item" href="orderHome">Order History</a>
                              <div className="dropdown-divider"></div>
                              {/* when vendor role */}
                                <a className="dropdown-item" href="route('companyProductCreate', auth()->user()->company->slug)">Add a Product</a>
                                <a className="dropdown-item" href="route('companyProductHome', auth()->user()->company->slug)">My Products</a>
                              {/* endwhen */}
                              {/* when mod role */}
                                <a className="dropdown-item" href="route('modHubHome')">Moderator's Hub</a>
                              {/* endwhen */}
                              {/* when guest user */}
                                <a className="dropdown-item" href="route('vendorCreate')">Become a vendor</a>
                              {/* endwhen */}
                              <div className="dropdown-divider"></div>
                              <a className="dropdown-item" href="route('userEdit', auth()->user()->slug)">User Settings</a>
                              <a className="dropdown-item" href="route('logout')">Logout</a>
                          </div>
                      </li>
                  {/* elsewhen */}
                      <li className="nav-item">
                              <a className="nav-link" href="route('registerHome')">
                              <span>
                                  <i className="fa fa-user-plus" aria-hidden="true"></i>
                              </span>
                              <span>Register</span>
                          </a>
                      </li>
                      <li className="nav-item">
                              <a className="nav-link" href="route('login')">
                              <span>
                                  <i className="fa fa-sign-in" aria-hidden="true"></i>
                              </span>
                              <span>Login</span>
                          </a>
                      </li>
                  {/* endwhen */}
                  <li className="nav-item">
                      <a className="nav-link" href="route('cartShow')">
                          <span>
                              <i className="fa fa-cart-plus" aria-hidden="true"></i>
                          </span>
                          <span>Cart (cartCount)</span>
                      </a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
  )
}

const styles = makeStyles({
  body: {

  },
})
