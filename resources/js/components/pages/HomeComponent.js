import React from 'react'
import { Button } from '@material-ui/core'
import { useStyles } from '../../styles/styles'
import { useHistory } from 'react-router-dom' 

import { url } from '../../utils/config'

function HomeComponent() {
  const history = useHistory()  
  const classes = useStyles()
  const gotToDashboard = (e, url) => {
      history.push(url)
  }

  return (
    <>
      <div className={classes.homeRoot}>
        <div className={classes.containerDiv}>
          <Button 
            variant="contained" 
            className={classes.extraBtnStyle} 
            onClick={e => gotToDashboard(e, url('/user'))} 
            size="large" 
            color="primary"
          >
            User Dashboard
          </Button>  
        </div>
      </div>
    </>       
  )
} 

export default HomeComponent
