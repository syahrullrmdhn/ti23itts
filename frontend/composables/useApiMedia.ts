export const useApiMedia = () => {
  const config = useRuntimeConfig()
  const backendBase = config.public.apiBase.replace(/\/api\/?$/, '')
  const defaultAvatar = `data:image/svg+xml;utf8,${encodeURIComponent(`
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800">
      <rect width="800" height="800" fill="#111827" />
      <circle cx="400" cy="280" r="140" fill="#22c55e" />
      <path d="M160 680c0-132.55 107.45-240 240-240s240 107.45 240 240" fill="#22c55e" />
    </svg>
  `)}`

  const mediaUrl = (path?: string | null) => {
    if (!path) {
      return defaultAvatar
    }

    if (/^https?:\/\//i.test(path)) {
      return path
    }

    return `${backendBase}/storage/${path.replace(/^\/+/, '')}`
  }

  return { mediaUrl }
}
