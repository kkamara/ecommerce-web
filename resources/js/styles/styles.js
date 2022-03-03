import { makeStyles } from '@material-ui/core/styles'

export const useStyles = makeStyles(theme => ({
    homeRoot: {
      display: 'flex',
      '& > *': {
        margin: theme.spacing(1),
      },
    },
    
    containerDiv: {
      flex:'auto',
      position: 'fixed',
      top: '40%',
      left: '42.5%',
    },
    extraBtnStyle:{
      margin:'2%'
    },
    centerItem:{
      flex:'auto',
      top:'15%',
      position:'relative',
      left:'25%',
      right:'25%',
      width:'50%',
      marginBottom: '50px',
      marginTop: '60px'
    },
    fullWidth:{
      width:'90%',
     marginBottom:'25px',
     marginTop:'20px'
    },
    linkContainer:{
     marginBottom:'20px'
    },
    authResponse:{
      fontWeight:'bolder'
    },
    title: {
      flexGrow: 1,
    },
   fullWidthProfile:{
     width:'70%',
     marginRight:'15%',
     marginLeft:'15%',
     marginTop:'80px'
   },
   link: {
     color: 'white',
     textDecoration: "none"

   },
}))
