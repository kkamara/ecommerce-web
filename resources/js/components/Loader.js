import React from 'react'

import splash from '../assets/splash.jpg'

import './Loader.css'

export default function Loader() {
  return (
    <img 
      className='loader'
      src={splash} 
      alt='Loading'
    />
  )
}
