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

export function capitalize(subject) {
  let result = ''
  for (const item of subject.split(' ')) {
    const newItem = item.charAt(0).toUpperCase() + item.slice(1)
    if (0 === result.length) {
      result = newItem
    } else {
      result += ' ' + newItem
    }
  }
  return result
}

export const APP_NAME = 'Ecommerce'
export const APP_RELEASE_YEAR = '2018'
export const APP_VERSION = '4.0.1'
