import React from 'react'
import Card from '@mui/material/Card'
import { useSelector, } from 'react-redux'


export default function({ 
  children, 
  style: customStyles,
  lightStyles: customLightStyles, 
  darkStyles: customDarkStyles, 
}) {
  const theme = useSelector(state => state.theme)

  let styles
  
  switch (theme.data) {
    case 'light':
      styles = {...lightStyles}
      if (customStyles && customStyles.container) {
        styles.container = {...styles.container, ...customStyles.container,}
      }
      if (customLightStyles && customLightStyles.container) {
        styles.container = {...styles.container, ...customLightStyles.container,}
      }
      break
    case 'dark':
      styles = {...darkStyles}
      if (customStyles && customStyles.container) {
        styles.container = {...styles.container, ...customStyles.container,}
      }
      if (customDarkStyles && customDarkStyles.container) {
        styles.container = {...styles.container, ...customDarkStyles.container,}
      }
      break
    default:
      return null
  }
  
  return (
    <Card sx={styles.container}>
      {children}
    </Card>
  )
}

const lightStyles = {
  container: {
    paddingLeft: 2,
    paddingRight: 2,
    textAlign: 'center',
  },
}

const darkStyles = {
  container: {
    backgroundColor: 'rgb(52 58 64)',
    color: '#fff',
    paddingLeft: 2,
    paddingRight: 2,
    textAlign: 'center',
  },
}
