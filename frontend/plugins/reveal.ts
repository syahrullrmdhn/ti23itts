type RevealElement = HTMLElement & {
  __revealObserver?: IntersectionObserver
}

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.directive('reveal', {
    getSSRProps() {
      return {}
    },
    mounted(el: RevealElement, binding) {
      if (!import.meta.client) return

      const variant = typeof binding.value === 'string' ? binding.value : 'up'

      el.classList.add('reveal-ready', `reveal-${variant}`)

      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        el.classList.add('reveal-visible')
        return
      }

      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return

            el.classList.add('reveal-visible')
            observer.unobserve(entry.target)
          })
        },
        {
          threshold: 0.16,
          rootMargin: '0px 0px -10% 0px',
        },
      )

      observer.observe(el)
      el.__revealObserver = observer
    },
    unmounted(el: RevealElement) {
      el.__revealObserver?.disconnect()
    },
  })
})
