import React, { useEffect } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useHistory } from 'react-router-dom' 
import { makeStyles, } from '@material-ui/core'

export default function Header(props) {
  const history = useHistory()
  
  const dispatch = useDispatch()
  const authResponse = useSelector(state=>state.auth)

  const logOut = () => {
    // dispatch(LogoutAction())
    history.push("/login")
  }

  const login = () => {
    history.push("/login")
  }

  const token = localStorage.getItem('user-token')
  //console.log(token)

  // useEffect(() => {
  //   if(authResponse !== "" && authResponse.success === true){
  //       alert(authResponse.message)
  //       localStorage.removeItem('user-token')
  //       //history.push("/user/login")    
  //   } 
  //   return () => {}
  // }, [authResponse])

  return (
    <div className={styles.body}>
    </div>
  )
}

const styles = makeStyles({
  body: {

  },
})
