import React, { useState, useEffect, } from 'react'
import { useSelector, } from 'react-redux'
import CardActions from '@mui/material/CardActions'
import Typography from '@mui/material/Typography'
import Loader from '../../Loader'

import { capitalize, } from '../../../utils/config'
import './ProductComponent.css'
import EditProduct from './EditProduct'
import DeleteProduct from './DeleteProduct'
import AddToCart from './AddToCart'
import Card from '../../layouts/Card'
import ProductReviews from './ProductReviews'

export default function ProductComponent({ match }) {
  const [loading, setLoading] = useState(true)
  const theme = useSelector(state => state.theme)

  useEffect(() => {
    setTimeout(() => {
      setLoading(false)
    }, 2000)
  }, [])

  let styles
  
  switch (theme.data) {
    case 'light':
      styles = {...lightStyles}
      break
    case 'dark':
      styles = {...darkStyles}
      break
    default:
      return null
  }

  if (loading) {
    return <Loader />
  }

  const product = {
    id: 939,
    name: capitalize('Aute sint ullamco nulla voluptate'),
    short_description: 'Minim reprehenderit anim laboris occaecat laborum deserunt quis elit do ex.',
    long_description: 'Dolore laboris fugiat laborum ex quis irure velit officia.',
    product_details: 'Irure irure exercitation velit consequat labore.',
    formatted_cost: '£2.69',
    image_path: '/image/products/default/not-found.jpg',
    shippable: true,
    free_delivery: true,
    user: {
      id: 702,
      is_logged_in: true,
      is_product_owner: true,
      has_permission_to_review: true,
      company: {
        id: 496,
        slug: 'kessler-windler-and-hessel',
      },
    },
    review: '2.33',
    reviews: [
      {
        id: 249,
        has_been_flagged_five_times: false,
        created_at: 1629921145000,
        content: `Minim est sit officia magna nisi qui ea sit nulla. Amet nulla enim laboris sunt aliquip deserunt voluptate. Eu id ea adipisicing velit eu do dolor. Minim anim fugiat incididunt aliquip nostrud deserunt nisi ullamco nulla cillum incididunt laboris nostrud. Aliquip fugiat nostrud elit minim nulla amet laborum Lorem.<br/>Do labore anim fugiat sunt officia do. Esse sint veniam exercitation consectetur nulla id deserunt fugiat. Est minim consequat consequat mollit sunt mollit irure nisi minim et aliquip. Nostrud incididunt velit in minim magna est cillum ea irure ex amet id consequat esse. Minim aliqua proident nulla duis irure anim pariatur mollit enim sint. Est qui nostrud culpa anim reprehenderit ipsum velit eu. Cillum quis exercitation cillum culpa aliqua exercitation velit anim occaecat voluptate ad ex sint.<br/>Nulla nulla dolor fugiat dolore eu eiusmod. Sunt mollit id consequat ullamco. Eu commodo Lorem cupidatat nulla aliquip. Aliquip minim cillum et magna tempor consectetur veniam adipisicing excepteur ex ullamco dolor in. Et qui est id pariatur minim ex labore qui cillum. Quis dolore nostrud duis ea qui tempor consectetur sint. Duis commodo occaecat consequat exercitation enim ut aute aliquip pariatur ipsum cillum et in.<br/>Tempor nulla adipisicing culpa minim nulla ad magna aliquip pariatur duis. Nostrud fugiat incididunt laboris adipisicing ex proident sint quis sunt tempor do. Pariatur nostrud Lorem non elit.<br/>Ipsum id duis tempor labore in est est proident dolor ipsum enim voluptate cillum. Sunt occaecat officia culpa exercitation nisi aliqua ad ea elit irure qui exercitation ut et. Excepteur sit nulla reprehenderit cupidatat Lorem nulla eu proident eu sint aliquip. Commodo fugiat proident esse deserunt enim eiusmod labore laboris qui aliquip mollit reprehenderit aliquip. Amet minim tempor eu cillum.`,
        user: {
          made_by_user_logged_in: true,
        },
        score: 5,
      },
      {
        id: 472,
        has_been_flagged_five_times: false,
        created_at: 1641747085000,
        content: 'voluptate. Eu id ea adipisicing velit eu do dolor',
        user: {
          made_by_user_logged_in: false,
        },
        score: 2,
      },
      {
        id: 38,
        has_been_flagged_five_times: true,
        created_at: 1630226330000,
        content: 'Sunt officia minim irure proident non adipisicing in officia non aliquip sint mollit.',
        user: {
          made_by_user_logged_in: false,
        },
        score: 0
      },
    ],
  }

  return (
    <div className='container product' style={styles.container}>
      <div className='row'>
        <div className='col-md-9'>
          <table className={theme.data === 'light' ? 'table-light' : 'table-dark'}>
            <tbody>
              <tr className='text-center'>
                <th scope='row' colSpan='3'>
                  <img 
                    src={product.image_path} 
                    className='img-responsive product-image'
                  />
                  <Typography dusk='product-name' variant='h4'>{product.name}</Typography>
                </th>
              </tr>
              <tr>
                <td scope='row'>Description</td>
                <td width={150}></td>
                <td>
                  { product.short_description }
                  <br/>
                  <br/>
                  { product.long_description }
                </td>
              </tr>
              <tr>
                <td scope='row'>Product Details</td>
                <td width={150}></td>
                <td>
                { product.product_details }
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div className='col-md-3'>
          <Card>            
            <Typography variant='h3'>{product.formatted_cost}</Typography>
            <br />
            <Typography variant='subtitle1'>
              {product.shippable ?
                'This item is shippable' :
                'This item is <strong>not</strong> shippable'}
            </Typography>
            {product.free_delivery ?
              <Typography variant='subtitle1'>Free Delivery</Typography> :
              null}
            <br/>
            <hr />
            <CardActions style={styles.cardActions} disableSpacing>
              <div className='d-flex align-items-end"'>
                <AddToCart product={product}/>
              </div>
              <div className='d-flex align-items-end"'>
                <EditProduct product={product}/>
                <DeleteProduct product={product}/>
              </div>
            </CardActions>
          </Card>
        </div>
      </div>
      <br />
      <div className='row'>
        <div className='col-md-12'>
          <ProductReviews product={product}/>
        </div>
      </div>
    </div>
  )
}

const lightStyles = {
  container: null,
}

const darkStyles = {
  container: {
    backgroundColor: 'rgb(52 58 64)',
  },
}
