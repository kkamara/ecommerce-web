import React from 'react'

/**
 * 
 * @param {string} path
 * @returns {string}
 */
export function url(path) {
  if (path[0] === '/') {
    return `/v4${path}`
  }
  return `/v4/${path}`
}

export const APP_NAME = 'Ecommerce'
export const APP_RELEASE_YEAR = '2018'
export const APP_VERSION = '3.0.0'
