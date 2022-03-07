import React, { 
  useState, 
  useEffect, 
  Fragment,
} from 'react'
import { url, } from '../../../utils/config'
import Error from '../../Error'
import Loader from '../../Loader'
import moment from 'moment'

export default function ProductComponent({ match }) {
  const [loading, setLoading] = useState(false)

  // useEffect(() => {
  //   setTimeout(() => {
  //     setLoading(false)
  //   }, 2000)
  // }, [])

  function handleCreateRating() {}

  function formatReviewContent(content) {
    return content.split('<br/>').map((text, index) => (
      <Fragment key={index}>{text}<br/></Fragment>
    ))
  }

  if (loading) {
    return <Loader />
  }

  console.log('params', match.params)
  const product = {
    id: 939,
    name: 'Aute sint ullamco nulla voluptate irure.',
    short_description: 'Minim reprehenderit anim laboris occaecat laborum deserunt quis elit do ex.',
    long_description: 'Dolore laboris fugiat laborum ex quis irure velit officia.',
    product_details: 'Irure irure exercitation velit consequat labore.',
    formatted_cost: '2.69',
    image_path: 'https://www.backmarket.co.uk/cdn-cgi/image/format=auto,quality=75,width=640/https://d1eh9yux7w8iql.cloudfront.net/product_images/None_6ca6aa3e-b703-49da-b392-bf30944fae79.jpg',
    shippable: true,
    free_delivery: true,
    user: {
      id: 702,
      is_logged_in: true,
      is_product_owner: true,
      has_permission_to_review: true,
      company: {
        id: 496,
        slug: 'Kessler, Windler and Hessel',
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

  function getProductReviews() {
    return product.reviews.map((review, key) => (
      <div key={key} className='card'>
        {false === review.has_been_flagged_five_times ? 
          <>
            <div className='card-body'>
              <div className='card-text'>
                <div className='float-left'>
                  Product Rated { review.score } / 5
                  {product.user.is_logged_in && 
                    product.user.id === review.user.id ?
                    `by <strong>you</strong>` :
                    null}
                </div>
                <div className='float-right'>
                  Posted { moment(review.created_at).format('YYYY-MM-DD hh:mm') }
                </div>

                <br/>
                <br/>

                <p>
                  { formatReviewContent(review.content) }
                </p>

              </div>
            </div>
            <div className='card-footer'>
              <a
                onChange={() => {}}
                className='float-right btn btn-sm btn-default'
              >
                <small>Flag this review</small>
              </a>
            </div> 
          </> :
          <div className='card-body'>
            <small>This comment is currently under review.</small>
          </div>}
      </div>
    ))
  }

  return (
    <div className='container' id='app'>
      <div className='row'>
        <div className='col-md-9'>
          <table className='table table-striped'>
            <tbody>
              <tr className='text-center'>
                <th scope='row' colSpan='2'>
                  <img src={product.image_path} className='img-responsive'/>
                  <h4 dusk='product-name'>
                    { product.name }
                  </h4>
                </th>
              </tr>
              <tr>
                <td scope='row'>Description</td>
                <td>
                  { product.short_description }
                  <br/>
                  <br/>
                  { product.long_description }
                </td>
              </tr>
              <tr>
                <td scope='row'>Product Details</td>
                <td>
                { product.product_details }
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div className='col-md-3'>
          <ul className='list-group'>
            <li className='list-group-item'>
            <h3>
              { product.formatted_cost }
            </h3>
            <small>
              {product.shippable ?
                'This item is shippable' :
                'This item is <strong>not</strong> shippable'}
            </small>
            <br/>
            {product.free_delivery ?
              <small>Free Delivery</small> :
              null}
            </li>
            <li className='list-group-item'>
              {/* {product.user.is_logged_in && product.user._is_product_owner ?
                <> */}
                  <a 
                    dusk='edit-btn'
                    href={url(`company/${product.user.company.id}/product/${product.id}]`)}
                    className='btn btn-warning btn-sm pull-left'
                  >
                    Edit item
                  </a>
                  <a 
                    dusk='delete-btn'
                    href={url(`company/${product.user.company.slug}/product/${product.slug}]`)}
                    className='btn btn-danger btn-sm pull-right'
                  >
                    Delete item
                  </a>
                {/* </> :
                <> */}
                  <a 
                    dusk='add-to-cart-btn'
                    className='btn btn-primary btn-sm'
                    onChange={() => {}}
                  >
                    Add to cart
                  </a>
                {/* </>} */}
            </li>
          </ul>
        </div>
      </div>
      <div className='row'>
        <div className='col-md-12'>
          <div className='card'>
            <div className='card-header'>
              <div className='lead'>
                Reviews {product.review !== '0.00' ? `(Average  ${ product.review })` : null}
              </div>
            </div>
            <div className='card-body'>
              <div className='card-text'>
                {product.reviews.length ?
                  getProductReviews() :
                  'No reviews for this product.'}
              </div>
            </div>
            <div className='card-footer'>
              {product.user.has_permission_to_review ?
                <>
                  <Error message='Missing rating' />
                  <br />
                  <form onSubmit={handleCreateRating}>
                    <div className='form-group float-left'>
                      <label>
                        <select dusk='rating' name='rating' className='form-control'>
                          <option value=''>Choose a rating</option>
                          <option value='0'>0</option>
                          <option value='1'>1</option>
                          <option value='2'>2</option>
                          <option value='3'>3</option>
                          <option value='4'>4</option>
                          <option value='5'>5</option>
                        </select>
                      </label>
                    </div>

                    <div className='form-group'>
                      <textarea 
                        dusk='content'
                        className='form-control' 
                        name='content' 
                        type='text' 
                        placeholder='Your review...'></textarea>
                    </div>

                    <div className='form-group float-right'>
                      <input 
                        dusk='submit-btn'
                        type='submit' 
                        className='form-group btn btn-primary'
                      />
                    </div>
                  </form>
                </> : 
                null}
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
