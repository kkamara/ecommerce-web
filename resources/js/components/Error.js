import React from 'react'
import Alert from '@mui/material/Alert'

export default function Error({ message, }) {
  return <Alert 
    variant="outlined" 
    severity="error"
    onClose={() => {}}
    style={styles.container}
  >
    {message}
  </Alert>
}

const styles = {
  container: {
    fontSize: 18,
  },
}
