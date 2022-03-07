import React from 'react'
import { 
    APP_NAME, 
    APP_RELEASE_YEAR,
    APP_VERSION,
} from '../../utils/config'

export default function Footer() {
  return (
    <footer className='footer' style={{ marginTop:30, }}>
      <div className='container text-center'>
        <span className='text-muted'>
          {APP_NAME} &copy; {APP_RELEASE_YEAR}. App version {APP_VERSION}
        </span>
      </div>
    </footer>
    )
}
