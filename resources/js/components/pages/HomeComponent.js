import React, { useEffect, Fragment, } from 'react'
import { useHistory, Redirect, } from 'react-router-dom'
import { useDispatch, useSelector, } from 'react-redux'
import { getProducts, } from '../../redux/actions/productActions'
import { getTheme, } from '../../redux/actions/themeActions'
import ProductCard from './Product/ProductCard'
import { ERROR, } from '../../utils/pageRoutes'
import Loader from '../Loader'
import Paginator from '../Paginator'

export default function HomeComponent() {
  const history = useHistory()
  
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    products: state.products,
    theme: state.theme,
  }))

  const { 
    data, 
    error, 
    loading, 
  } = state.products

  useEffect(() => {
    dispatch(getProducts())
    dispatch(getTheme())
  }, [])

  renderTheme()

  function renderTheme() {
    if ('dark' === state.theme.data) {    
      document.body.style.backgroundColor = '#1a2027'
      document.body.style.color = 'rgb(178, 186, 194)'
    } else {
      document.body.style.backgroundColor = '#fff'
      document.body.style.color = '#000'
    }
  }

  if (loading) {
    return <Loader />
  }

  if (error) {
    console.error(error)
    return <Redirect to={ERROR} />
  }

  function onPageChange(event, newPage) {
    dispatch(getProducts(newPage))
  }
  
  function renderProducts() {
    if (!data.data.length) {
      return '<p>No products available.</p>'
    }

    return data.data.map((product, index) => 
      <Fragment key={index}>
        {index !== 0 && index % 4 === 0 ? <div className='block'></div> : null}

        <ProductCard 
          index={index} 
          product={product} 
        />
      </Fragment>
    )
  }

  return (
    <>
      <div className={'container'} id='home'>
        <br />
        <div className='text-left'>
          <div><strong>Page:</strong> {data.meta.current_page}</div>
          <div><strong>Page Count:</strong> {data.meta.last_page}</div>
          <div><strong>Displayed Items:</strong> {data.meta.per_page}</div>
          <div><strong>Items:</strong> {data.meta.total}</div>
        </div>
        <div className='list-group'>
          <div className='container'>
            {renderProducts()}
          </div>
        </div>

        <br/>
        
        <Paginator
          onChange={onPageChange}
          page={data.meta.current_page}
          count={data.meta.last_page} 
        />
      </div>
    </>       
  )
}
