export const useApiMedia = () => {
  const config = useRuntimeConfig()
  const backendBase = config.public.apiBase.replace(/\/api\/?$/, '')

  const mediaUrl = (path?: string | null) => {
    if (!path) {
      return 'https://placehold.co/800x800/111827/22c55e?text=TI%2723'
    }

    if (/^https?:\/\//i.test(path)) {
      return path
    }

    return `${backendBase}/storage/${path.replace(/^\/+/, '')}`
  }

  return { mediaUrl }
}
