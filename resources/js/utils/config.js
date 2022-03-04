import React from 'react'

export function url(path) {
  if (path[0] === '/') {
    return `/react${path}`
  }
  return `/react/${path}`
}

export const APP_NAME = 'Ecommerce'
export const APP_RELEASE_YEAR = '2018'
export const APP_VERSION = '3.0.0'
