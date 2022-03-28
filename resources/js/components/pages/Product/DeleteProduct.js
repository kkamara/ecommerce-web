import React, { useState, } from 'react'
import { useSelector, } from 'react-redux'
import ListItem from '@mui/material/ListItem'
import ListItemText from '@mui/material/ListItemText'
import RightDrawer from '../../layouts/RightDrawer'

export default function DeleteProduct({ product, }) {  
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
  
  return (
    <>
      <a 
        dusk='delete-btn'
        onClick={() => setOpen(true)}
        className='btn btn-danger btn-sm pull-right'
      >
        Delete item
      </a>
      <RightDrawer open={open} onClose={() => setOpen(false)}
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
      </RightDrawer>
    </>
  )
}

const lightStyles = {
  listItem: {
    backgroundColor: 'lightgray',
  },
  listItemText: {
    backgroundColor: 'lightgray',
    fontWeight: 800,
  },
}

const darkStyles = {
  listItem: {
    backgroundColor: 'lightgray',
  },
  listItemText: {
    backgroundColor: 'lightgray',
    fontWeight: 800,
  },
}
