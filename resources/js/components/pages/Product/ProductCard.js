import React, { useEffect, } from 'react'
import { Link, } from 'react-router-dom'
import { PRODUCT, } from '../../../utils/pageRoutes'

import Card from '@mui/material/Card'
import CardHeader from '@mui/material/CardHeader'
import CardMedia from '@mui/material/CardMedia'
import CardContent from '@mui/material/CardContent'
import CardActions from '@mui/material/CardActions'
import IconButton from '@mui/material/IconButton'
import Typography from '@mui/material/Typography'
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart'
import { url, } from '../../../utils/config'
import { useDispatch, useSelector, } from 'react-redux'
import { getTheme, } from '../../../redux/actions/themeActions'

import './ProductCard.css'

export default function ProductCard({ product, index, }) {  
  const dispatch = useDispatch()
  const state = useSelector(state => ({ theme: state.theme, }))

  useEffect(() => {
    dispatch(getTheme())
  }, [])

  renderTheme()

  function renderTheme() {
    if ('dark' === state.theme.data) {
      styles.productColor = '#fff'
      styles.companyName.color = '#67676f'
      styles.product.backgroundColor = '#000'
      styles.product.color = '#fff'
      styles.shortDesc.color = '#67676f'
      styles.review.color = '#fff'
      styles.cost.color = '#fff'
      styles.cartIcon.color = 'lightgray'
    } else {
      styles.productColor = '#000'
      styles.companyName.color = '#67676f'
      styles.product.backgroundColor = '#fff'
      styles.shortDesc.color = '#67676f'
      styles.product.color = '#000'
      styles.review.color = '#000'
      styles.cost.color = '#000'
      styles.cartIcon.color = '#67676f'
    }
  }

  const { 
    id,
    slug,
    name,
    formatted_cost,
    company,
    review,
    image_path,
  } = product
  
  return (
    <Link 
      className='product-container'
      dusk={`product-${index}`} 
      to={url(PRODUCT.replace(':slug', slug))}
    >
      <Card sx={styles.product}>
        <CardMedia
          component='img'
          height='194'
          image={image_path}
          alt={name}
        />
        <CardHeader
          title={<p style={styles.productName}>{name}</p>}
          subheader={company.name}
          titleTypographyProps={{
            sx: { color: styles.productColor, }
          }}
          subheaderTypographyProps={{
            sx: { color: styles.companyName, }
          }}
        />
        <CardContent>
          <Typography 
            className='text-right' 
            variant='body1' 
            sx={{ color: styles.cost, }}
          >
            {formatted_cost}
          </Typography>
        </CardContent>
        <CardActions style={styles.cardActions} disableSpacing>
          <Typography 
            sx={{ color: styles.review, }}
            variant='body1' 
            color='CaptionText'>
            {'0.00' !== review ? `Rated ${review}` : null}
          </Typography>
          <IconButton variant='contained' aria-label='add to card'>
            <AddShoppingCartIcon sx={{ color: styles.cartIcon.color }}/>
          </IconButton>
        </CardActions>
      </Card>
    </Link>
  )
}

const styles = {
  product: { 
    maxWidth: 200, 
    display: 'inline-block', 
    marginRight: 2,
    marginLeft: 2,
    marginBottom: 2,
    marginTop: 2,
  },
  cardActions: {
    justifyContent: 'space-between',
  },
  productColor: {
    color: '#000',
  },
  productName: {
    fontSize: 18,
    textAlign: 'left',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    width: 165,
  },
  companyName: {
    color: '#67676f',
    fontSize: 12,
  },
  cost: {
    color: '#000',
  },
  shortDesc: {
    color: '#67676f',
  },
  review: {
    color: '#000',
  },
  cartIcon: {
    color: '#67676f',
  },
}
