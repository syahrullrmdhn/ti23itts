type AdminUser = {
  id: number
  name: string
  email: string
}

type LoginPayload = {
  login?: string
  email?: string
  password: string
}

type LoginResponse = {
  user: AdminUser
  token: string
}

const STORAGE_KEY = 'ti23-admin-session'

export const useAdminAuth = () => {
  const config = useRuntimeConfig()
  const token = useState<string | null>('admin-token', () => null)
  const user = useState<AdminUser | null>('admin-user', () => null)
  const initialized = useState<boolean>('admin-auth-ready', () => false)
  const isAuthenticated = computed(() => Boolean(token.value))

  const persist = () => {
    if (process.server) {
      return
    }

    if (token.value && user.value) {
      localStorage.setItem(
        STORAGE_KEY,
        JSON.stringify({
          token: token.value,
          user: user.value,
        }),
      )

      return
    }

    localStorage.removeItem(STORAGE_KEY)
  }

  const setSession = (sessionToken: string, sessionUser: AdminUser) => {
    token.value = sessionToken
    user.value = sessionUser
    initialized.value = true
    persist()
  }

  const clearSession = () => {
    token.value = null
    user.value = null
    initialized.value = true
    persist()
  }

  const restore = () => {
    if (process.server || initialized.value) {
      return
    }

    const savedSession = localStorage.getItem(STORAGE_KEY)

    if (!savedSession) {
      initialized.value = true
      return
    }

    try {
      const session = JSON.parse(savedSession) as LoginResponse
      token.value = session.token
      user.value = session.user
    } catch {
      localStorage.removeItem(STORAGE_KEY)
    } finally {
      initialized.value = true
    }
  }

  const login = async (payload: LoginPayload) => {
    const response = await $fetch<LoginResponse>(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: payload,
    })

    setSession(response.token, response.user)

    return response.user
  }

  const logout = async () => {
    const activeToken = token.value
    clearSession()

    if (!activeToken) {
      return
    }

    try {
      await $fetch(`${config.public.apiBase}/logout`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${activeToken}`,
        },
      })
    } catch {
      // Frontend state stays logged out even if the API session was already invalid.
    }
  }

  return {
    token,
    user,
    isAuthenticated,
    restore,
    login,
    logout,
    clearSession,
  }
}
