import React, { useEffect, Fragment, } from 'react'
import { useHistory, Redirect, } from 'react-router-dom'
import { useDispatch, useSelector, } from 'react-redux'
import { getProducts, } from '../../redux/actions/productActions'
import ProductCard from './Product/ProductCard'
import { ERROR, } from '../../utils/pageRoutes'
import Pagination from '@mui/material/Pagination';
import Loader from '../Loader'

export default function HomeComponent() {
  const history = useHistory()
  
  const dispatch = useDispatch()
  const products = useSelector(state => state.products)

  const { 
    data, 
    error, 
    loading, 
  } = products

  useEffect(() => {
    dispatch(getProducts())
  }, [])

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
      <div className={'container'}>
        <div className="list-group">
          <div className="container">
            {renderProducts()}
          </div>
        </div>

        <br/>
        
        <div className="text-center">
          <Pagination 
            onChange={onPageChange}
            page={data.meta.current_page}
            count={data.meta.last_page} 
            showFirstButton 
            showLastButton
          />
        </div>
      </div>
    </>       
  )
}
