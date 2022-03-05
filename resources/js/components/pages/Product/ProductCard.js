import React from 'react'
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

export default function ProductCard({ product, index, }) {
  const { 
    id,
    name,
    formatted_cost,
    company,
    review,
    image_path,
    short_description,
  } = product

  return (
    <Link dusk={`product-${index}`} to={url(PRODUCT.replace(':id', id))}>
      <Card sx={styles.product}>
        <CardHeader
          title={name}
          subheader={company.name}
        />
        <CardMedia
          component="img"
          height="194"
          image={image_path}
          alt={name}
        />
        <CardContent>
          <Typography 
            className='text-right' 
            variant="body1" 
            color="CaptionText"
          >
            {formatted_cost}
          </Typography>
          <Typography variant="body2" color="text.secondary">
            {short_description}
          </Typography>
        </CardContent>
        <CardActions style={styles.cardActions} disableSpacing>
          <Typography variant="body1" color="CaptionText">
            {'0.00' !== review ? `Rated ${review}` : null}
          </Typography>
          <IconButton aria-label="add to card">
            <AddShoppingCartIcon />
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
}
