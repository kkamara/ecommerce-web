
export function url(path) {
  if (path[0] === '/') {
    return `/react${path}`
  }
  return `/react/${path}`
}
