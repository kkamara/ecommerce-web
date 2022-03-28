import React, { Fragment, useState, } from 'react'
import { useSelector, } from 'react-redux'
import Typography from '@mui/material/Typography'
import moment from 'moment'
import tinycron from 'tinycron'
import Card from '../../../layouts/Card'
import Error from '../../../Error'

export default function ProductReviews({ product, }) {
  const [open, setOpen] = useState(false)
  const theme = useSelector(state => state.theme)
  
  switch (theme.data) {
    case 'light':
      styles = {...styles, ...lightStyles}
      break
    case 'dark':
      styles = {...styles, ...darkStyles}
      break
    default:
      return null
  }

  function handleCreateRating(e) {
    e.preventDefault()
  }

  function formatReviewContent(content) {
    return content.split('<br/>').map((text, index) => (
      <Fragment key={index}>{text}<br/></Fragment>
    ))
  }

  function getProductReviews() {
    return product.reviews.map((review, key) => (
      <div key={key}>
        {false === review.has_been_flagged_five_times ? 
          <>
            <div className='row'>
              <div className='col-md-6'>
                Product Rated { review.score } / 5
                {product.user.is_logged_in && 
                  product.user.id === review.user.id ?
                  `by <strong>you</strong>` :
                  null}
              </div>
              <div className={`col-md-6 ${window.innerWidth > 650 ? 'text-right' : null}`}>
                Posted { (new tinycron(moment(review.created_at).toDate())).toNow().toLowerCase() }&nbsp;
                ({moment(review.created_at).format('Y-MM-DD H:m')})
              </div>

              <br/>
              <br/>
            </div>
            <div className="row">
              <div className='col-md-12'>
                <Typography variant='caption'>
                  { formatReviewContent(review.content) }
                </Typography>
              </div>
            </div>
            <div className='row'>
              <div className='col-md-12'>
                <a
                  onClick={() => {}}
                  className='float-right btn btn-sm btn-default'
                  style={styles.flagReviewBtn}
                >
                  <small>Flag this review</small>
                </a>
              </div>
            </div>
            <br />
          </> :
          <div style={styles.flaggedReview}>
            <strong>This comment is currently under review.</strong>
          </div>}
      </div>
    ))
  }

  function getPortal() {
    return (
      <>
        {product.reviews.length ?
          getProductReviews() :
          'No reviews for this product.'}
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
                  className='form-group btn btn-success'
                  value='Save'
                />
              </div>
            </form>
          </> : 
          null}
      </>
    )
  }

  return (
    <Card style={{ container: styles.container, }}>
      <div className='lead'>
        <span onClick={() => setOpen(!open)}>
          Reviews {product.review !== '£0.00' ? 
            `(Average  ${ product.review })` : 
            null}
        </span>
      </div>
      <br />
      {open ? getPortal() : null}
    </Card>
  )
}

let styles = {
  container: {
    textAlign: 'left',
  },
  flaggedReview: {
    marginTop: 30,
    marginBottom: 30,
    color: 'gray',
  },
}

const lightStyles = {
  container: {
    textAlign: 'left',
  },
  flagReviewBtn: {
    color: '#000',
  },
}

const darkStyles = {
  container: {
    textAlign: 'left',
  },
  flagReviewBtn: {
    backgroundColor: 'gray',
  },
  flaggedReview: {
    ...styles.flaggedReview,
    borderTop: '1px solid #fff',
    borderBottom: '1px solid #fff',
  },
}
