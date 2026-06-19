export default defineNuxtRouteMiddleware(() => {
  if (import.meta.server) {
    return
  }

  const auth = useAdminAuth()
  auth.restore()

  if (!auth.isAuthenticated.value) {
    return navigateTo('/admin')
  }
})
