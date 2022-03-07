import React, { useEffect, } from 'react'
import Pagination from '@mui/material/Pagination'
import { makeStyles, } from '@material-ui/core'
import { useDispatch, useSelector, } from 'react-redux'
import { getTheme, } from '../redux/actions/themeActions'

export default function Paginator({
  onChange,
  count,
  page,
}) {  
  const dispatch = useDispatch()
  const theme = useSelector(state => state.theme)

  useEffect(() => {
    dispatch(getTheme())
  }, [])

  const classes = useStyles();

  if ('dark' === theme.data) {
    return (
      <Pagination 
        classes={classes}
        color='secondary'
        onChange={onChange}
        page={page}
        count={count} 
        showFirstButton 
        showLastButton
      />
    )
  }

  return (
    <Pagination 
      onChange={onChange}
      page={page}
      count={count} 
      showFirstButton 
      showLastButton
    />
  )
}

const useStyles = makeStyles(() => ({
  ul: {
    '& .MuiPaginationItem-root': {
      color: 'yellow'
    }
  },
}));
