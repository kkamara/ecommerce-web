import React, { useEffect, useState, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useHistory, } from 'react-router-dom'
import { Helmet, } from "react-helmet"
import { APP_NAME, } from '../../utils/config'
import { HOME, } from '../../utils/pageRoutes'
import Switch from '../Switch'
import { url, } from '../../utils/config'
import { setTheme, getTheme, } from '../../redux/actions/themeActions'

export default function Header(props) {
  const history = useHistory()
  
  const dispatch = useDispatch()
  const state = useSelector(state => ({ theme: state.theme, }))
  const [switchValue, setSwitchValue] = useState(false)
  
  useEffect(() => {
    dispatch(getTheme())
  }, [])

  useEffect(() => {
    renderSwitch()
  }, [state.theme.data])

  // useEffect(() => {
  //   if(authResponse !== '' && authResponse.success === true){
  //       alert(authResponse.message)
  //       localStorage.removeItem('user-token')
  //       //history.push('/user/login')    
  //   } 
  //   return () => {}
  // }, [authResponse])

  const logOut = () => {
    // dispatch(LogoutAction())
    history.push(url('/login'))
  }

  const login = () => {
    history.push(url('/login'))
  }

  function handleThemeToggle(event) {
    let theme = 'light'
    if (event.target.checked) {
      theme = 'dark'
    }
    dispatch(setTheme(theme))
  }

  function renderSwitch() {
    if (
      'dark' === state.theme.data && 
      switchValue !== 'on'
    ) {
      setSwitchValue(true)
    } else if (switchValue !== false) {
      setSwitchValue(false)
    }
  }
  
  return (
    <>
      {state.theme.data === 'dark' ? (
        <Helmet>
          <style type="text/css">{`
            body {
              background-color: #343a40 !important;
            }
          `}</style>
        </Helmet>
      ) : (
        <Helmet>
          <style type="text/css">{`
            body {
              background-color: #fff !important;
            }
          `}</style>
        </Helmet>
      )}
      <nav className={`navbar navbar-expand-lg navbar-${state.theme.data} bg-${state.theme.data}`}>
        <div className='container'>
          <a className='navbar-brand' href={url(HOME)}>{APP_NAME}</a>
          <button className='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
              <span className='navbar-toggler-icon'></span>
          </button>

          <div className='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul className='navbar-nav mr-auto'>
              <li className='nav-item'>
                <a className='nav-link' href={url(HOME)}>Home <span className='sr-only'>(current)</span></a>
              </li>
              <div className='nav-item'>
                <a 
                  className='nav-link' 
                  href={url('products/?sort_by=pop')}
                  dusk='most-popular'
                >
                  Most Popular
                </a>
              </div>
              <div className='nav-item'>
                <a 
                  className='nav-link' 
                  href={url('products/?sort_by=top')}
                  dusk='top-rated'
                >
                  Top Rated
                </a>
              </div>
            </ul>
            <ul className='navbar-nav mr-auto'>
              <form className='form-inline my-2 my-lg-0'>
                <input 
                  name='query' 
                  className='form-control mr-sm-2' 
                  type='search' 
                  placeholder='Find Your Product' 
                  aria-label='Search'
                  dusk='search-products-in'
                />
                <button 
                  className='btn btn-outline-success my-2 my-sm-0' 
                  type='submit'
                  dusk='search-products-btn'
                >
                  Search
                </button>
              </form>
            </ul>
            <ul className='navbar-nav mr-right'>
                {/* when authenticated */}
              <li className='nav-item dropdown'>
                  <a className='nav-link dropdown-toggle' href={url('#')} id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    My Stuff
                  </a>
                <div className='dropdown-menu' aria-labelledby='navbarDropdown'>
                  <a className='dropdown-item' href={url('billingHome')}>Billing Cards</a>
                  <a className='dropdown-item' href={url('addressHome')}>Addresses</a>
                  <div className='dropdown-divider'></div>
                  <a className='dropdown-item' href={url('orderHome')}>Order History</a>
                  <div className='dropdown-divider'></div>
                  {/* when vendor role */}
                  <a className='dropdown-item' href={url('companyProductCreate')}>Add a Product</a>
                  <a className='dropdown-item' href={url('companyProductHome')}>My Products</a>
                  {/* endwhen */}
                  {/* when mod role */}
                  <a className='dropdown-item' href={url('modHubHome')}>Moderator's Hub</a>
                  {/* endwhen */}
                  {/* when guest user */}
                  <a className='dropdown-item' href={url('vendorCreate')}>Become a vendor</a>
                  {/* endwhen */}
                  <div className='dropdown-divider'></div>
                  <a className='dropdown-item' href={url('userEdit')}>User Settings</a>
                  <a className='dropdown-item' href={url('logout')}>Logout</a>
                </div>
              </li>
              {/* elsewhen */}
              <li className='nav-item'>
                  <a className='nav-link' href={url('login')}>
                    <span>
                        <i className='fa fa-sign-in' aria-hidden='true'></i>
                    </span>
                    <span>Login</span>
                  </a>
              </li>
              {/* endwhen */}
              <li className='nav-item'>
                <a className='nav-link' href={url('cartShow')}>
                <span>
                  <i className='fa fa-cart-plus' aria-hidden='true'></i>
                </span>
                <span>Cart (cartCount)</span>
                </a>
                </li>
                <li className='nav-item'>
                <span>
                  <Switch 
                    onChange={handleThemeToggle}
                    checked={switchValue}
                  />  
                </span>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </>
  )
}
