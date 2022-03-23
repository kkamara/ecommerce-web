import React, { useState, useEffect, } from 'react'
import { useSelector, } from 'react-redux'
import Drawer from '@mui/material/Drawer'
import ListItem from '@mui/material/ListItem'
import ListItemText from '@mui/material/ListItemText'

export default function EditProduct({ product, }) {  
  const [open, setOpen] = useState(false)
  const theme = useSelector(state => state.theme)

  let styles
  
  switch (theme.data) {
    case 'light':
      styles = {...lightStyles}
      break
    case 'dark':
      styles = {...darkStyles}
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
    <>
      <a 
        dusk='edit-btn'
        onClick={() => setOpen(true)}
        className='btn btn-warning btn-sm pull-left'
      >
        Edit item
      </a>
      <Drawer
        anchor={'right'}
        open={open}
        onClose={() => setOpen(false)}
        sx={{ ".MuiDrawer-paperAnchorRight": styles.drawer, }}
      >
        <ListItem 
          style={styles.listItem} 
          button 
          key={1}
        >
          <ListItemText 
            primary={"text"} 
            sx={{
              ".MuiListItemText-root": {
                backgroundColor: styles.listItemText.backgroundColor,
              },
              ".MuiListItemText-primary": {
                fontWeight: styles.listItemText.fontWeight,
              },
            }}
          />
        </ListItem>
      </Drawer>
    </>
  )
}

const lightStyles = {
  drawer: {
    width: 600,
    backgroundColor: '#fff',
  },
  listItem: {
    backgroundColor: 'lightgray',
  },
  listItemText: {
    backgroundColor: 'lightgray',
    fontWeight: 800,
  },
}

const darkStyles = {
  drawer: {
    width: 600,
    backgroundColor: '#000',
  },
  listItem: {
    backgroundColor: 'lightgray',
  },
  listItemText: {
    backgroundColor: 'lightgray',
    fontWeight: 800,
  },
}
