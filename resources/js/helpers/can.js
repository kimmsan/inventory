import store from '~/store'

export default (permissions) => {
  // Get the user permission
  const superAdmin =  store.getters['auth/user'] ? store.getters['auth/user'].account_role : null
  const userPermissions = store.getters['auth/user'] ? store.getters['auth/user'].permissions : null
  let canEnter = false

  // If user account_role is 0 (its like super admin no need any permission)
  if (superAdmin === 0) {
    canEnter = true
    return canEnter// return true
  }

  // If user account_role is 1 and don't have any permisisons
  if (!userPermissions || !permissions) {
    return canEnter // return false
  }

  if (!Array.isArray(permissions)) {
    canEnter = userPermissions.includes(permissions)
  } else {
    permissions.forEach((permission) => {
      if (userPermissions.includes(permission)) {
        canEnter = true
      }
    })
  }
  return canEnter
}
