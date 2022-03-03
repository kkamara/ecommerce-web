import React, { useState } from "react"
import { Link } from "react-router-dom"
import { useDispatch } from 'react-redux'
import { 
  Button, 
  TextField, 
  Card, 
} from "@material-ui/core"
import AccountCircleIcon from "@material-ui/icons/AccountCircle"

import { RegisterAction } from '../../redux/actions/AuthActions'
import { useStyles } from "../../styles/styles"

import { url } from '../../utils/config'

function RegisterComponent() {
  const classes = useStyles()
  const dispatch = useDispatch()
  //const authResponse = useSelector(state=>state.userAuth.authResponse)
  
  const [fields, setState] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  })

  const handleFieldChange = e => {
    setState({
        ...fields,
        [e.target.id] : e.target.value
    })
  }

  const UserRegister = (e) => {
    e.preventDefault()
    console.log(fields)
    const passwordMatch = checkPasswordMatch(fields.password, fields.password_confirmation)

    if(passwordMatch === true){
      alert('passwords dont match. please check your password again')
      return
    }
    dispatch(RegisterAction(fields))
  }

  const checkPasswordMatch = (password,password_confirmation) => {
    return  password !== password_confirmation ? true : false 
  }

  return (
    <div>
      <div className={classes.centerItem}>
        <Card>
          <h2>
            <b>Welcome to Register User Page</b>
          </h2>
           
          <form onSubmit={UserRegister}>
            <div>
              <TextField
                type="text"
                className={classes.fullWidth}
                required
                margin="normal"
                variant="outlined"
                label="name"
                id="name"
                value={fields.name}
                onChange={handleFieldChange}
              />
            </div>

            <div>
              <TextField
                type="email"
                className={classes.fullWidth}
                required
                margin="normal"
                variant="outlined"
                label="email"
                id="email"
                value={fields.email}
                onChange={handleFieldChange}
              />
            </div>

            <div>
              <div>
                <TextField
                  className={classes.fullWidth}
                  label="Password"
                  type="password"
                  margin="normal"
                  variant="outlined"
                  required
                  id="password"
                  value={fields.password}
                  onChange={handleFieldChange}
                />
              </div>

              <div>
                <TextField
                  className={classes.fullWidth}
                  label="Confirm Password"
                  type="password"
                  required
                  margin="normal"
                  variant="outlined"
                  id="password_confirmation"
                  value={fields.password_confirmation}
                  onChange={handleFieldChange}
                />
              </div>

              <div>
                <Button
                  type="submit"
                  className={classes.fullWidth}
                  variant="contained"
                  color="primary"
                  endIcon={<AccountCircleIcon />}
                >
                  <b>Register</b>
                </Button>
                <br />

                <div className={classes.linkContainer}>
                  <Link to={url("/user/login")}>Login Here</Link>
                </div>
              </div>

              <div className={classes.linkContainer}>
                <Link to={url("/home")}>Back To Home Page </Link>
              </div>

              <div></div>
            </div>
          </form>
        </Card>
      </div>
    </div>
  )
}

export default RegisterComponent
