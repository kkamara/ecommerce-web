import React from 'react'
import { Button, } from '@material-ui/core'
import { makeStyles, } from '@material-ui/core/styles'
import { useHistory, } from 'react-router-dom'

export default function HomeComponent() {
  const history = useHistory()
  
  return (
    <>
      <div className={styles.body}>
        <h1>Test</h1>
        <Button 
          variant="contained" 
          // className={styles.extraBtnStyle} 
          onClick={() => {}} 
          size="large" 
          color="primary"
        >
          Test button
        </Button>
      </div>
    </>       
  )
} 

const styles = makeStyles({
  body: {
    backgroundcolor: 'yellow',
  },
})
