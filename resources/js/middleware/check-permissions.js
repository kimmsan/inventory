import store from '~/store'
import can from '~/helpers/can'

export default async (to, from, next) => {
  const reqiredPermissions = to.meta.permissions

  if (!from.name) {
    store.dispatch('auth/fetchUser').then(() => {
      const canEnter = can(reqiredPermissions)
      if (!canEnter) {
        return next({ name: 'permission-denied' })
      }
      return next()
    })
  } else {
    const canEnter = can(reqiredPermissions)
    if (!canEnter) {
      return next({ name: 'permission-denied' })
    }
    return next()
  }
}
