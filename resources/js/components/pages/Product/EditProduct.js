import React, { useState, } from 'react'
import { useSelector, } from 'react-redux'
import ListItem from '@mui/material/ListItem'
import ListItemText from '@mui/material/ListItemText'
import EditIcon from '@mui/icons-material/Edit'
import IconButton from '@mui/material/IconButton'
import RightDrawer from '../../layouts/RightDrawer'
import { iconStyles, } from '../../../theme'

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
  
  return (
    <>
      <IconButton 
        dusk='edit-btn'
        onClick={() => setOpen(true)}
        variant='contained' 
        aria-label='edit icon'
      >
        <EditIcon color='warning' sx={iconStyles}/>
      </IconButton>
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
