import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import { isUserLoggedIn } from './utils'
import routes from '~pages'
import { canNavigate } from '@layouts/plugins/casl'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    ...setupLayouts(routes),
  ],
})

// Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards

router.beforeEach((to) => {
  const isLoggedIn = isUserLoggedIn()


  // if (!canNavigate(to)) {
  //
  //   if (!isLoggedIn)
  //     return next({ name: 'login', query: { to: to.name !== 'index' ? to.fullPath : undefined } })
  //
  //   // If logged in => not authorized
  //   return next({ name: 'not-authorized' })
  // }
  //
  // // Redirect if logged in
  // if (to.meta.redirectIfLoggedIn && isLoggedIn)
  //   next('/')
  //
  // return next()
  if (canNavigate(to)) {
    console.log(to.meta)
    if (to.meta.redirectIfLoggedIn && isLoggedIn)
      return '/'
  }
  else {
    if (isLoggedIn)
      return { name: 'not-authorized' }
    else
      return { name: 'login', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
  }
})

export default router
