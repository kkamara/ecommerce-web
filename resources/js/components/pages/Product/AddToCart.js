import React from 'react'
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart'
import IconButton from '@mui/material/IconButton'
import { iconStyles, } from '../../../theme'

export default function AddToCart({ product, }) {
  return (
    <IconButton 
      variant='contained' 
      aria-label='add to cart'
      dusk='add-to-cart-btn'
      onClick={() => {}}
    >
      <AddShoppingCartIcon color='primary' sx={iconStyles}/>
    </IconButton>
  )
}
