import React from 'react'

import splash from '../assets/splash.jpg'

export default function Loader() {
  return (
    <img 
      style={styles.image} 
      src={splash} 
      alt='Loading'
    />
  )
}

const styles = {
  image: {
    marginTop: 30,
    borderRadius: 10,
  },
}
