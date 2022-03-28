import React from 'react'
import { useSelector, } from 'react-redux'
import Drawer from '@mui/material/Drawer'

export default function RightDrawer({ 
  open,
  onClose,
  children,
  customLightDrawerStyles,
  customDarkDrawerStyles,
}) {
  const theme = useSelector(state => state.theme)

  let styles
  
  switch (theme.data) {
    case 'light':
      styles = {...lightStyles}
      if (customLightDrawerStyles) {
        styles = {...styles, customLightDrawerStyles,}
      }
      break
    case 'dark':
      styles = {...darkStyles}
      if (customDarkDrawerStyles) {
        styles = {...styles, customDarkDrawerStyles,}
      }
      break
    default:
      return null
  }

  setWindowSize()
  function setWindowSize() {
    if (window.innerWidth < 650) {
      styles.drawer.width = 200
    } else {
      styles.drawer.width = 600
    }
  }
  
  return (
    <Drawer
      anchor={'right'}
      open={open}
      onClose={onClose}
      sx={{ ".MuiDrawer-paperAnchorRight": styles.drawer, }}
    >
      {children}
    </Drawer>
  )
}

const lightStyles = {
  drawer: {
    width: 600,
    backgroundColor: '#fff',
  },
}

const darkStyles = {
  drawer: {
    width: 600,
    backgroundColor: '#000',
  },
}
