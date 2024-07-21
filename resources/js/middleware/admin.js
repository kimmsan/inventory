import store from '~/store'

export default (to, from, next) => {
  if (store.getters['auth/user'].account_role == 1 || store.getters['auth/user'].account_role == 0) {
    next()
  } else {
    next({ name: 'home' })
  }
}
