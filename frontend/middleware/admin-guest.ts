export default defineNuxtRouteMiddleware((to, from) => {
  if (process.server) {
    return
  }

  const auth = useAdminAuth()
  auth.restore()

  if (auth.isAuthenticated.value) {
    return navigateTo('/admin/dashboard')
  }
})
