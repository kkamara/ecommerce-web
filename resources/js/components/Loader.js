import React from 'react'

import splash from '../assets/splash.jpg'

import './Loader.css'

export default function Loader() {
  return (
    <div className='container' id='app'>
      <div className="text-center">
        <img 
          className='loader'
          src={splash} 
          alt='Loading'
        />
      </div>
    </div>
  )
}
